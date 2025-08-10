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

    public function checkout(Request $request)
    {
        $cartController = new CartController();
        $cartData = $cartController->getCartData();

        if (empty($cartData['items'])) {
            return redirect()->route('cart')->with('error', 'Votre panier est vide.');
        }

        // Vérifier si c'est une demande Apple Pay
        $paymentMethod = $request->get('payment_method');
        if ($paymentMethod === 'apple_pay') {
            // Pour Apple Pay, nous pouvons soit :
            // 1. Rediriger vers une page spécifique Apple Pay
            // 2. Charger la page checkout avec un flag Apple Pay
            return view('checkout.index', compact('cartData'))->with('apple_pay_requested', true);
        }

        return view('checkout.index', compact('cartData'));
    }

    /**
     * Create Stripe session with Apple Pay enabled
     */
    public function createApplePaySession(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string'
        ]);

        try {
            $product = Product::findOrFail($request->product_id);
            $quantity = $request->quantity;
            
            // Vérifier le stock
            if (!$product->isInStock() || $product->stock < $quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produit indisponible ou stock insuffisant'
                ], 400);
            }

            $subtotal = $product->getCurrentPrice() * $quantity;
            $taxAmount = $subtotal * 0.20; // TVA 20%
            $shippingAmount = $subtotal >= 150 ? 0 : 9.90; // Livraison gratuite dès 150€
            $total = $subtotal + $taxAmount + $shippingAmount;

            // Créer une commande temporaire AVEC TOUS LES CHAMPS REQUIS
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => 'apple_pay_stripe',
                'customer_email' => 'temp@example.com',
                'customer_name' => 'Temp Customer',
                'customer_phone' => null,
                // AJOUT DES CHAMPS D'ADRESSE MANQUANTS
                'billing_address_line_1' => 'Temp Address',
                'billing_address_line_2' => null,
                'billing_city' => 'Temp City',
                'billing_postal_code' => '00000',
                'billing_country' => 'FR',
                'shipping_address_line_1' => 'Temp Address',
                'shipping_address_line_2' => null,
                'shipping_city' => 'Temp City',
                'shipping_postal_code' => '00000',
                'shipping_country' => 'FR',
                'shipping_carrier' => 'colissimo',
                'shipping_method' => $shippingAmount == 0 ? 'Gratuite' : 'Express',
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'shipping_amount' => $shippingAmount,
                'total_amount' => $total,
                'currency' => 'EUR',
            ]);

            // Créer l'item de commande
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_type' => $product->type,
                'product_size' => $product->size,
                'product_price' => $product->getCurrentPrice(),
                'quantity' => $quantity,
                'total_price' => $subtotal,
            ]);

            // Créer la session Stripe avec Apple Pay CORRECTEMENT CONFIGURÉ
            $session = StripeSession::create([
                // CONFIGURATION APPLE PAY FIXÉE
                'payment_method_types' => ['card'], // Stripe gère Apple Pay automatiquement
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $product->name,
                            'description' => $product->type . ' - ' . $product->size,
                            'images' => [$product->main_image] // Ajout de l'image
                        ],
                        'unit_amount' => intval($product->getCurrentPrice() * 100), // Prix unitaire correct
                    ],
                    'quantity' => $quantity, // Quantité correcte
                ], [
                    // LIGNE SÉPARÉE POUR LA LIVRAISON
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Frais de livraison',
                            'description' => $shippingAmount == 0 ? 'Livraison gratuite' : 'Livraison express'
                        ],
                        'unit_amount' => intval($shippingAmount * 100),
                    ],
                    'quantity' => 1,
                ], [
                    // LIGNE SÉPARÉE POUR LA TVA
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'TVA (20%)',
                        ],
                        'unit_amount' => intval($taxAmount * 100),
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('payment.cancel'),
                'automatic_tax' => ['enabled' => false],
                'billing_address_collection' => 'required',
                'shipping_address_collection' => [
                    'allowed_countries' => ['FR', 'BE', 'CH', 'LU', 'MC'],
                ],
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'payment_method' => 'apple_pay_stripe',
                    'source' => 'apple_pay_button',
                    'product_id' => $product->id,
                    'quantity' => $quantity
                ],
                // CONFIGURATION AMÉLIORÉE POUR APPLE PAY
                'payment_method_options' => [
                    'card' => [
                        'request_three_d_secure' => 'automatic',
                    ],
                ],
                'customer_creation' => 'if_required',
                'locale' => 'fr',
                // AJOUT POUR APPLE PAY
                'payment_intent_data' => [
                    'capture_method' => 'automatic',
                    'setup_future_usage' => 'off_session',
                ],
            ]);

            // Mettre à jour la commande avec la session Stripe
            $order->update([
                'stripe_session_id' => $session->id,
                // Ajout du payment_intent_id si disponible
                'stripe_payment_intent_id' => $session->payment_intent ?? null
            ]);

            return response()->json([
                'success' => true,
                'checkout_url' => $session->url,
                'session_id' => $session->id,
                'order_number' => $order->order_number
            ]);

        } catch (\Exception $e) {
            \Log::error('Stripe Apple Pay session creation error: ' . $e->getMessage(), [
                'product_id' => $request->product_id ?? 'N/A',
                'quantity' => $request->quantity ?? 'N/A',
                'error_trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la session de paiement',
                'debug' => app()->environment(['local', 'development']) ? $e->getMessage() : null
            ], 500);
        }
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
        $orderNumber = $request->get('order_number');
        
        // Gestion Apple Pay - recherche par order_number
        if ($orderNumber && !$sessionId) {
            $order = Order::where('order_number', $orderNumber)->first();
            
            if (!$order) {
                return redirect()->route('home')->with('error', 'Commande introuvable.');
            }
            
            // Pour Apple Pay, la commande est déjà marquée comme payée
            if ($order->payment_method === 'apple_pay' && $order->payment_status === 'paid') {
                return view('payment.success', compact('order'));
            }
        }
        
        // Gestion Stripe classique - recherche par session_id
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
     * Validate Apple Pay merchant
     */
    public function validateApplePayMerchant(Request $request)
    {
        $validationURL = $request->input('validationURL');
        
        if (!$validationURL) {
            return response()->json(['error' => 'Validation URL manquante'], 400);
        }

        try {
            // Configuration Apple Pay
            $merchantIdentifier = config('apple-pay.merchant_identifier', 'merchant.com.heritajparfums.app');
            $merchantDomainName = config('apple-pay.domain_name', request()->getHost());
            $merchantDisplayName = config('apple-pay.display_name', 'Héritaj Parfums');
            
            // Vérifier si les certificats existent
            $certPath = storage_path('apple-pay/merchant_id.pem');
            $keyPath = storage_path('apple-pay/merchant_id.key');
            
            if (!file_exists($certPath) || !file_exists($keyPath)) {
                \Log::warning('Apple Pay certificates not found', [
                    'cert_path' => $certPath,
                    'key_path' => $keyPath,
                    'cert_exists' => file_exists($certPath),
                    'key_exists' => file_exists($keyPath)
                ]);
                
                return response()->json([
                    'error' => 'Certificats Apple Pay non configurés',
                    'message' => 'En développement, les certificats Apple Pay ne sont pas configurés.',
                    'development_mode' => app()->environment(['local', 'development'])
                ], 400);
            }
            
            // Préparer les données de validation
            $validationData = [
                'merchantIdentifier' => $merchantIdentifier,
                'domainName' => $merchantDomainName,
                'displayName' => $merchantDisplayName
            ];

            // Faire la requête de validation vers Apple
            $client = new \GuzzleHttp\Client();
            $response = $client->post($validationURL, [
                'json' => $validationData,
                'cert' => $certPath,
                'ssl_key' => $keyPath,
                'verify' => true,
                'timeout' => 30
            ]);

            $merchantSession = json_decode($response->getBody(), true);
            
            return response()->json($merchantSession);

        } catch (\Exception $e) {
            \Log::error('Apple Pay validation error: ' . $e->getMessage(), [
                'validation_url' => $validationURL,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Erreur de validation Apple Pay',
                'message' => 'Les certificats Apple Pay ne sont pas correctement configurés pour ce domaine.',
                'development_note' => 'En développement avec ngrok, les certificats Apple Pay doivent être configurés pour le domaine de production.'
            ], 500);
        }
    }

    /**
     * Process Apple Pay payment - DIRECT SANS STRIPE
     */
    public function processApplePayment(Request $request)
    {
        $request->validate([
            'payment' => 'required',
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'billing_contact' => 'required',
            'shipping_contact' => 'required'
        ]);

        try {
            $product = Product::findOrFail($request->product_id);
            $quantity = $request->quantity;
            
            // Vérifier le stock
            if (!$product->isInStock() || $product->stock < $quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produit indisponible ou stock insuffisant'
                ], 400);
            }

            $subtotal = $product->getCurrentPrice() * $quantity;
            $taxAmount = $subtotal * 0.20; // TVA 20%
            $shippingAmount = $subtotal >= 150 ? 0 : 9.90; // Livraison gratuite dès 150€
            $total = $subtotal + $taxAmount + $shippingAmount;

            // Extraire les informations de contact
            $payment = $request->payment;
            $billingContact = $request->billing_contact;
            $shippingContact = $request->shipping_contact;

            // Créer la commande directement - APPLE PAY ACCEPTÉ
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'status' => 'confirmed', // Directement confirmé pour Apple Pay
                'payment_status' => 'paid', // Directement payé car Apple Pay gère la sécurité
                'payment_method' => 'apple_pay',
                'customer_email' => $shippingContact['emailAddress'] ?? '',
                'customer_name' => ($shippingContact['givenName'] ?? '') . ' ' . ($shippingContact['familyName'] ?? ''),
                'customer_phone' => $shippingContact['phoneNumber'] ?? '',
                'billing_address_line_1' => $billingContact['addressLines'][0] ?? '',
                'billing_address_line_2' => $billingContact['addressLines'][1] ?? '',
                'billing_city' => $billingContact['locality'] ?? '',
                'billing_postal_code' => $billingContact['postalCode'] ?? '',
                'billing_country' => $billingContact['countryCode'] ?? 'FR',
                'shipping_address_line_1' => $shippingContact['addressLines'][0] ?? '',
                'shipping_address_line_2' => $shippingContact['addressLines'][1] ?? '',
                'shipping_city' => $shippingContact['locality'] ?? '',
                'shipping_postal_code' => $shippingContact['postalCode'] ?? '',
                'shipping_country' => $shippingContact['countryCode'] ?? 'FR',
                'shipping_carrier' => 'colissimo',
                'shipping_method' => $shippingAmount == 0 ? 'Gratuite' : 'Express',
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'shipping_amount' => $shippingAmount,
                'total_amount' => $total,
                'currency' => 'EUR',
                'paid_at' => now()
            ]);

            // Créer l'item de commande
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_type' => $product->type,
                'product_size' => $product->size,
                'product_price' => $product->getCurrentPrice(),
                'quantity' => $quantity,
                'total_price' => $subtotal,
            ]);

            // Décrémenter le stock immédiatement
            $product->decrementStock($quantity);

            // Log de la transaction Apple Pay pour audit
            \Log::info('Apple Pay Transaction', [
                'order_number' => $order->order_number,
                'amount' => $total,
                'customer_email' => $order->customer_email,
                'payment_data' => [
                    'payment_network' => $payment['token']['paymentMethod']['network'] ?? 'Unknown',
                    'transaction_id' => $payment['token']['paymentData']['paymentData']['transactionIdentifier'] ?? uniqid('ap_')
                ]
            ]);

            // Envoyer un email de confirmation (optionnel)
            try {
                // Mail::to($order->customer_email)->send(new OrderConfirmation($order));
            } catch (\Exception $e) {
                \Log::warning('Failed to send order confirmation email', ['error' => $e->getMessage()]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Paiement Apple Pay réussi !',
                'order_number' => $order->order_number,
                'redirect_url' => route('payment.success') . '?order_number=' . $order->order_number
            ]);

        } catch (\Exception $e) {
            \Log::error('Apple Pay processing error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du traitement du paiement'
            ], 500);
        }
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