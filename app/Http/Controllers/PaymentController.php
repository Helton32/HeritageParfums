<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Stripe\PaymentIntent;
use Stripe\Webhook;

class PaymentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('stripe.secret_key'));
    }

    public function checkout()
    {
        $cartController = new CartController();
        $cartData = $cartController->getCartData();

        if (empty($cartData['items'])) {
            return redirect()->route('cart')->with('error', 'Votre panier est vide.');
        }

        return view('checkout.index', compact('cartData'));
    }

    public function createSession(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'billing_address_line_1' => 'required|string|max:255',
            'billing_address_line_2' => 'nullable|string|max:255',
            'billing_city' => 'required|string|max:255',
            'billing_postal_code' => 'required|string|max:10',
            'billing_country' => 'required|string|max:2',
            'shipping_carrier' => 'required|string',
            'shipping_method' => 'required|string',
            'shipping_amount' => 'required|numeric|min:0',
            'relay_point_id' => 'nullable|string'
        ]);

        $cartController = new CartController();
        $cartData = $cartController->getCartData();

        if (empty($cartData['items'])) {
            return response()->json(['error' => 'Panier vide'], 400);
        }

        // Recalculate totals with selected shipping
        $subtotal = $cartData['subtotal'];
        $taxAmount = $cartData['tax_amount'];
        $shippingAmount = $request->shipping_amount;
        $total = $subtotal + $taxAmount + $shippingAmount;

        try {
            // Create order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'status' => 'pending',
                'payment_status' => 'pending',
                'customer_email' => $request->customer_email,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'billing_address_line_1' => $request->billing_address_line_1,
                'billing_address_line_2' => $request->billing_address_line_2,
                'billing_city' => $request->billing_city,
                'billing_postal_code' => $request->billing_postal_code,
                'billing_country' => $request->billing_country,
                'shipping_address_line_1' => $request->billing_address_line_1,
                'shipping_address_line_2' => $request->billing_address_line_2,
                'shipping_city' => $request->billing_city,
                'shipping_postal_code' => $request->billing_postal_code,
                'shipping_country' => $request->billing_country,
                'shipping_carrier' => $request->shipping_carrier,
                'shipping_method' => $request->shipping_method,
                'carrier_options' => [
                    'relay_point_id' => $request->relay_point_id,
                    'selected_at' => now()->toDateTimeString()
                ],
                'shipping_weight' => $this->calculatePackageWeight($cartData['items']),
                'package_dimensions' => $this->getPackageDimensions($cartData['items']),
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'shipping_amount' => $shippingAmount,
                'total_amount' => $total,
                'currency' => 'EUR',
            ]);

            // Create order items
            foreach ($cartData['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'],
                    'product_type' => $item['type'],
                    'product_size' => $item['size'],
                    'product_price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total_price' => $item['total'],
                ]);
            }

            // Create Stripe line items
            $lineItems = [];
            foreach ($cartData['items'] as $item) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $item['name'],
                            'description' => $item['type'] . ' - ' . $item['size'],
                        ],
                        'unit_amount' => intval($item['price'] * 100), // Stripe works in cents
                    ],
                    'quantity' => $item['quantity'],
                ];
            }

            // Add shipping if applicable
            if ($cartData['shipping_amount'] > 0) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Frais de livraison',
                        ],
                        'unit_amount' => intval($cartData['shipping_amount'] * 100),
                    ],
                    'quantity' => 1,
                ];
            }

            // Add tax
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'TVA (20%)',
                    ],
                    'unit_amount' => intval($cartData['tax_amount'] * 100),
                ],
                'quantity' => 1,
            ];

            // Create Stripe Checkout Session
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('payment.cancel'),
                'customer_email' => $request->customer_email,
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                ],
            ]);

            // Update order with Stripe session ID
            $order->update(['stripe_session_id' => $session->id]);

            return response()->json(['url' => $session->url]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');
        
        if (!$sessionId) {
            return redirect()->route('home')->with('error', 'Session de paiement invalide.');
        }

        try {
            $session = StripeSession::retrieve($sessionId);
            $order = Order::where('stripe_session_id', $sessionId)->first();

            if (!$order) {
                return redirect()->route('home')->with('error', 'Commande introuvable.');
            }

            if ($session->payment_status === 'paid') {
                $order->markAsPaid();
                
                // Clear cart
                Session::forget('cart');
                
                // Decrement stock
                foreach ($order->items as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->decrementStock($item->quantity);
                    }
                }
            }

            return view('payment.success', compact('order'));

        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Erreur lors de la vérification du paiement.');
        }
    }

    public function cancel()
    {
        return view('payment.cancel');
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
        } catch (\Exception $e) {
            return response('Webhook signature verification failed.', 400);
        }

        // Handle the event
        switch ($event['type']) {
            case 'checkout.session.completed':
                $session = $event['data']['object'];
                $order = Order::where('stripe_session_id', $session['id'])->first();
                
                if ($order && $session['payment_status'] === 'paid') {
                    $order->markAsPaid();
                    
                    // Decrement stock
                    foreach ($order->items as $item) {
                        $product = Product::find($item->product_id);
                        if ($product) {
                            $product->decrementStock($item->quantity);
                        }
                    }
                }
                break;

            case 'payment_intent.succeeded':
                // Handle successful payment intent
                break;

            case 'payment_intent.payment_failed':
                // Handle failed payment
                break;

            default:
                // Unexpected event type
                return response('Unexpected event type', 400);
        }

        return response('Success', 200);
    }

    /**
     * Calculate package weight based on items
     */
    private function calculatePackageWeight($items)
    {
        $weight = 0;
        foreach ($items as $item) {
            // Poids standard d'un parfum : 150g pour 30ml, 200g pour 50ml, 300g pour 100ml
            $productWeight = match($item['size']) {
                '30ml' => 0.15,
                '50ml' => 0.2,
                '75ml' => 0.25,
                '100ml' => 0.3,
                default => 0.2
            };
            $weight += $productWeight * $item['quantity'];
        }
        
        // Ajouter le poids de l'emballage (100g)
        $weight += 0.1;
        
        return round($weight, 3);
    }

    /**
     * Get package dimensions based on items
     */
    private function getPackageDimensions($items)
    {
        // Dimensions standard pour nos parfums
        $itemCount = array_sum(array_column($items, 'quantity'));
        
        if ($itemCount == 1) {
            return ['length' => 15, 'width' => 10, 'height' => 8]; // cm
        } elseif ($itemCount <= 3) {
            return ['length' => 20, 'width' => 15, 'height' => 10];
        } else {
            return ['length' => 25, 'width' => 20, 'height' => 12];
        }
    }
}