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
     * Create Stripe session with Apple Pay - VERSION HOSTINGER SIMPLIFIÉE
     */
    public function createApplePaySession(Request $request)
    {
        // Log initial avec toutes les infos de debug
        \Log::info('Apple Pay Session Request - Hostinger', [
            'timestamp' => now()->toDateTimeString(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'input_data' => $request->all(),
            'headers' => [
                'content_type' => $request->header('Content-Type'),
                'csrf_token' => $request->header('X-CSRF-TOKEN') ? 'present' : 'missing',
                'accept' => $request->header('Accept'),
            ],
            'server_info' => [
                'php_version' => PHP_VERSION,
                'app_env' => config('app.env'),
                'app_debug' => config('app.debug'),
                'app_url' => config('app.url'),
            ]
        ]);

        // Validation stricte avec messages détaillés
        $validator = \Validator::make($request->all(), [
            'product_id' => 'required|integer|min:1',
            'quantity' => 'required|integer|min:1|max:10',
            'payment_method' => 'required|string|in:apple_pay_stripe'
        ]);

        if ($validator->fails()) {
            \Log::error('Validation failed', ['errors' => $validator->errors()->toArray()]);
            return response()->json([
                'success' => false,
                'message' => 'Données invalides: ' . $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Vérification de la configuration Stripe AVANT TOUT
            $stripeSecretKey = config('stripe.secret_key');
            if (empty($stripeSecretKey)) {
                \Log::error('Stripe configuration missing', ['config_checked' => true]);
                return response()->json([
                    'success' => false,
                    'message' => 'Configuration de paiement non disponible',
                    'error_code' => 'STRIPE_CONFIG_MISSING'
                ], 500);
            }

            // Re-initialiser Stripe si nécessaire
            try {
                Stripe::setApiKey($stripeSecretKey);
                \Log::info('Stripe initialized successfully', ['key_type' => substr($stripeSecretKey, 0, 7)]);
            } catch (\Exception $e) {
                \Log::error('Stripe initialization failed', ['error' => $e->getMessage()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible d\'initialiser le service de paiement',
                    'error_code' => 'STRIPE_INIT_FAILED'
                ], 500);
            }

            // Récupération et vérification du produit
            try {
                $product = Product::findOrFail($request->product_id);
                \Log::info('Product found', [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->getCurrentPrice(),
                    'stock' => $product->stock,
                    'active' => $product->is_active ?? true
                ]);
            } catch (\Exception $e) {
                \Log::error('Product not found', ['product_id' => $request->product_id, 'error' => $e->getMessage()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Produit non trouvé',
                    'error_code' => 'PRODUCT_NOT_FOUND'
                ], 404);
            }

            $quantity = $request->quantity;
            
            // Vérifications de stock détaillées
            if (!method_exists($product, 'isInStock') || !$product->isInStock()) {
                \Log::warning('Product out of stock', ['product_id' => $product->id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Ce produit n\'est plus en stock',
                    'error_code' => 'OUT_OF_STOCK'
                ], 400);
            }
            
            if ($product->stock < $quantity) {
                \Log::warning('Insufficient stock', ['requested' => $quantity, 'available' => $product->stock]);
                return response()->json([
                    'success' => false,
                    'message' => "Stock insuffisant. Seulement {$product->stock} disponible(s)",
                    'error_code' => 'INSUFFICIENT_STOCK'
                ], 400);
            }

            // Calculs de prix avec vérifications
            $unitPrice = $product->getCurrentPrice();
            if (empty($unitPrice) || $unitPrice <= 0) {
                \Log::error('Invalid product price', ['price' => $unitPrice]);
                return response()->json([
                    'success' => false,
                    'message' => 'Prix du produit invalide',
                    'error_code' => 'INVALID_PRICE'
                ], 400);
            }

            $subtotal = round($unitPrice * $quantity, 2);
            $taxAmount = round($subtotal * 0.20, 2);
            $shippingAmount = $subtotal >= 150 ? 0 : 9.90;
            $total = round($subtotal + $taxAmount + $shippingAmount, 2);

            \Log::info('Price calculations completed', [
                'unit_price' => $unitPrice,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'shipping_amount' => $shippingAmount,
                'total' => $total
            ]);

            // Création de la commande avec try-catch séparé
            $order = null;
            try {
                $order = Order::create([
                    'order_number' => Order::generateOrderNumber(),
                    'status' => 'pending',
                    'payment_status' => 'pending',
                    'payment_method' => 'apple_pay_stripe',
                    'customer_email' => 'temp@example.com',
                    'customer_name' => 'Temp Customer',
                    'customer_phone' => null,
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

                \Log::info('Order created', ['order_id' => $order->id, 'order_number' => $order->order_number]);

            } catch (\Exception $e) {
                \Log::error('Order creation failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création de la commande',
                    'error_code' => 'ORDER_CREATION_FAILED'
                ], 500);
            }

            // Création de l'OrderItem
            try {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_type' => $product->type ?? 'Parfum',
                    'product_size' => $product->size ?? '50ml',
                    'product_price' => $unitPrice,
                    'quantity' => $quantity,
                    'total_price' => $subtotal,
                ]);

                \Log::info('OrderItem created');

            } catch (\Exception $e) {
                \Log::error('OrderItem creation failed', ['error' => $e->getMessage()]);
                if ($order) {
                    $order->delete();
                }
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'ajout des articles',
                    'error_code' => 'ORDER_ITEM_CREATION_FAILED'
                ], 500);
            }

            // Construction des URLs avec gestion des erreurs
            $baseUrl = config('app.url');
            if (empty($baseUrl) || $baseUrl === 'http://localhost') {
                $baseUrl = $request->getSchemeAndHttpHost();
                \Log::warning('APP_URL not configured, using detected URL', ['detected_url' => $baseUrl]);
            }

            $successUrl = rtrim($baseUrl, '/') . '/payment/success?session_id={CHECKOUT_SESSION_ID}';
            $cancelUrl = rtrim($baseUrl, '/') . '/payment/cancel';

            \Log::info('URLs prepared', ['base' => $baseUrl, 'success' => $successUrl, 'cancel' => $cancelUrl]);

            // Création de la session Stripe - VERSION SIMPLIFIÉE
            try {
                $sessionConfig = [
                    'payment_method_types' => ['card'],
                    'line_items' => [[
                        'price_data' => [
                            'currency' => 'eur',
                            'product_data' => [
                                'name' => $product->name,
                                'description' => sprintf('%s - %s', 
                                    $product->type ?? 'Parfum', 
                                    $product->size ?? '50ml'
                                ),
                            ],
                            'unit_amount' => intval($unitPrice * 100),
                        ],
                        'quantity' => $quantity,
                    ], [
                        'price_data' => [
                            'currency' => 'eur',
                            'product_data' => [
                                'name' => 'Frais de livraison',
                            ],
                            'unit_amount' => intval($shippingAmount * 100),
                        ],
                        'quantity' => 1,
                    ], [
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
                    'success_url' => $successUrl,
                    'cancel_url' => $cancelUrl,
                    'metadata' => [
                        'order_id' => (string)$order->id,
                        'order_number' => $order->order_number,
                        'product_id' => (string)$product->id,
                        'environment' => config('app.env', 'production'),
                    ],
                    'locale' => 'fr',
                ];

                \Log::info('Creating Stripe session', ['config_keys' => array_keys($sessionConfig)]);

                $session = StripeSession::create($sessionConfig);

                \Log::info('Stripe session created successfully', [
                    'session_id' => $session->id,
                    'url' => $session->url,
                    'status' => $session->status ?? 'unknown'
                ]);

            } catch (\Exception $e) {
                \Log::error('Stripe session creation failed', [
                    'error' => $e->getMessage(),
                    'error_type' => get_class($e),
                    'trace' => $e->getTraceAsString()
                ]);

                if ($order) {
                    $order->delete();
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création de la session de paiement',
                    'error_code' => 'STRIPE_SESSION_FAILED',
                    'stripe_error' => $e->getMessage()
                ], 500);
            }

            // Mise à jour de la commande avec l'ID de session
            try {
                $order->update([
                    'stripe_session_id' => $session->id,
                    'stripe_payment_intent_id' => $session->payment_intent ?? null
                ]);
            } catch (\Exception $e) {
                \Log::warning('Failed to update order with Stripe session ID', ['error' => $e->getMessage()]);
            }

            // Réponse finale
            $response = [
                'success' => true,
                'checkout_url' => $session->url,
                'session_id' => $session->id,
                'order_number' => $order->order_number,
                'message' => 'Session Apple Pay créée avec succès'
            ];

            \Log::info('Apple Pay session creation completed successfully', $response);

            return response()->json($response);

        } catch (\Exception $e) {
            \Log::error('Unexpected error in Apple Pay session creation', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Une erreur inattendue s\'est produite',
                'error_code' => 'UNEXPECTED_ERROR',
                'debug' => config('app.debug') ? $e->getMessage() : null
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
                // RÉCUPÉRER LES VRAIES DONNÉES APPLE PAY DEPUIS STRIPE
                $this->updateOrderWithStripeData($order, $session);
                
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
                    // RÉCUPÉRER LES VRAIES DONNÉES APPLE PAY DEPUIS STRIPE
                    $this->updateOrderWithStripeData($order, $session);
                    
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
     * Process Apple Pay payment - CORRIGÉ POUR EXTRAIRE CORRECTEMENT LES DONNÉES
     */
    public function processApplePayment(Request $request)
    {
        // Log détaillé des données reçues pour debugging
        \Log::info('Apple Pay Payment Request - Raw Data', [
            'all_request_data' => $request->all(),
            'payment_data' => $request->input('payment'),
            'billing_contact' => $request->input('billing_contact'),
            'shipping_contact' => $request->input('shipping_contact'),
            'headers' => $request->headers->all()
        ]);

        $request->validate([
            'payment' => 'required',
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
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

            // EXTRACTION AMÉLIORÉE DES DONNÉES APPLE PAY
            $payment = $request->input('payment', []);
            $billingContact = $request->input('billing_contact', []);
            $shippingContact = $request->input('shipping_contact', []);

            // Fonction helper pour extraire les données de contact de manière robuste
            $extractContactInfo = function($contact, $type = 'shipping') {
                // Logging pour voir la structure exacte
                \Log::info("Apple Pay Contact Data - {$type}", ['contact' => $contact]);
                
                $info = [
                    'email' => '',
                    'givenName' => '',
                    'familyName' => '',
                    'phoneNumber' => '',
                    'addressLines' => [],
                    'locality' => '',
                    'postalCode' => '',
                    'countryCode' => 'FR'
                ];

                if (!is_array($contact)) {
                    return $info;
                }

                // Extraction flexible - plusieurs formats possibles
                $info['email'] = $contact['emailAddress'] ?? $contact['email'] ?? '';
                $info['givenName'] = $contact['givenName'] ?? $contact['given_name'] ?? $contact['firstName'] ?? '';
                $info['familyName'] = $contact['familyName'] ?? $contact['family_name'] ?? $contact['lastName'] ?? '';
                $info['phoneNumber'] = $contact['phoneNumber'] ?? $contact['phone_number'] ?? $contact['phone'] ?? '';
                
                // Adresse - plusieurs formats possibles
                if (isset($contact['addressLines'])) {
                    $info['addressLines'] = is_array($contact['addressLines']) ? $contact['addressLines'] : [$contact['addressLines']];
                } elseif (isset($contact['address_lines'])) {
                    $info['addressLines'] = is_array($contact['address_lines']) ? $contact['address_lines'] : [$contact['address_lines']];
                } elseif (isset($contact['address'])) {
                    $info['addressLines'] = is_array($contact['address']) ? $contact['address'] : [$contact['address']];
                } elseif (isset($contact['street'])) {
                    $info['addressLines'] = [$contact['street']];
                }

                $info['locality'] = $contact['locality'] ?? $contact['city'] ?? $contact['town'] ?? '';
                $info['postalCode'] = $contact['postalCode'] ?? $contact['postal_code'] ?? $contact['zip'] ?? '';
                $info['countryCode'] = $contact['countryCode'] ?? $contact['country_code'] ?? $contact['country'] ?? 'FR';

                return $info;
            };

            // Extraire les informations avec la fonction helper
            $billing = $extractContactInfo($billingContact, 'billing');
            $shipping = $extractContactInfo($shippingContact, 'shipping');

            // Utiliser les données de shipping par défaut, fallback sur billing
            $customerEmail = $shipping['email'] ?: $billing['email'] ?: 'apple-pay@heritajparfums.com';
            $customerName = trim(($shipping['givenName'] ?: $billing['givenName']) . ' ' . ($shipping['familyName'] ?: $billing['familyName'])) ?: 'Client Apple Pay';
            $customerPhone = $shipping['phoneNumber'] ?: $billing['phoneNumber'] ?: null;

            // Adresses de livraison
            $shippingAddress1 = $shipping['addressLines'][0] ?? $billing['addressLines'][0] ?? 'Adresse Apple Pay';
            $shippingAddress2 = $shipping['addressLines'][1] ?? $billing['addressLines'][1] ?? null;
            $shippingCity = $shipping['locality'] ?: $billing['locality'] ?: 'Ville';
            $shippingPostalCode = $shipping['postalCode'] ?: $billing['postalCode'] ?: '00000';
            $shippingCountry = strtoupper($shipping['countryCode'] ?: $billing['countryCode'] ?: 'FR');

            // Adresses de facturation
            $billingAddress1 = $billing['addressLines'][0] ?? $shippingAddress1;
            $billingAddress2 = $billing['addressLines'][1] ?? $shippingAddress2;
            $billingCity = $billing['locality'] ?: $shippingCity;
            $billingPostalCode = $billing['postalCode'] ?: $shippingPostalCode;
            $billingCountry = strtoupper($billing['countryCode'] ?: $shippingCountry);

            // Log des données extraites pour vérification
            \Log::info('Apple Pay Extracted Data', [
                'customer_email' => $customerEmail,
                'customer_name' => $customerName,
                'customer_phone' => $customerPhone,
                'shipping_address' => [
                    'line_1' => $shippingAddress1,
                    'line_2' => $shippingAddress2,
                    'city' => $shippingCity,
                    'postal_code' => $shippingPostalCode,
                    'country' => $shippingCountry
                ],
                'billing_address' => [
                    'line_1' => $billingAddress1,
                    'line_2' => $billingAddress2,
                    'city' => $billingCity,
                    'postal_code' => $billingPostalCode,
                    'country' => $billingCountry
                ]
            ]);

            // Créer la commande avec les données extraites
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'payment_method' => 'apple_pay',
                'customer_email' => $customerEmail,
                'customer_name' => $customerName,
                'customer_phone' => $customerPhone,
                'billing_address_line_1' => $billingAddress1,
                'billing_address_line_2' => $billingAddress2,
                'billing_city' => $billingCity,
                'billing_postal_code' => $billingPostalCode,
                'billing_country' => $billingCountry,
                'shipping_address_line_1' => $shippingAddress1,
                'shipping_address_line_2' => $shippingAddress2,
                'shipping_city' => $shippingCity,
                'shipping_postal_code' => $shippingPostalCode,
                'shipping_country' => $shippingCountry,
                'shipping_carrier' => 'colissimo',
                'shipping_method' => $shippingAmount == 0 ? 'Gratuite' : 'Express',
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'shipping_amount' => $shippingAmount,
                'total_amount' => $total,
                'currency' => 'EUR',
                'paid_at' => now(),
                'notes' => 'Commande Apple Pay - Données extraites automatiquement'
            ]);

            // Créer l'item de commande
            $productPrice = $product->getCurrentPrice() ?? $product->price ?? 0;
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_type' => $product->type,
                'product_size' => $product->size,
                'product_price' => $productPrice,
                'quantity' => $quantity,
                'total_price' => $subtotal,
            ]);

            // Décrémenter le stock immédiatement
            $product->decrementStock($quantity);

            // Log de succès
            \Log::info('Apple Pay Transaction Success', [
                'order_number' => $order->order_number,
                'amount' => $total,
                'customer_email' => $order->customer_email,
                'shipping_address' => $order->shipping_address_line_1 . ', ' . $order->shipping_city,
                'payment_data' => [
                    'payment_network' => $payment['token']['paymentMethod']['network'] ?? 'Unknown',
                    'transaction_id' => $payment['token']['paymentData']['paymentData']['transactionIdentifier'] ?? uniqid('ap_')
                ]
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Paiement Apple Pay réussi !',
                'order_number' => $order->order_number,
                'redirect_url' => route('payment.success') . '?order_number=' . $order->order_number,
                'order_details' => [
                    'customer_name' => $order->customer_name,
                    'shipping_address' => $order->shipping_address_line_1 . ', ' . $order->shipping_city . ' ' . $order->shipping_postal_code,
                    'total_amount' => $order->formatted_total
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Apple Pay processing error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
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

    /**
     * MÉTHODE DE DEBUG POUR APPLE PAY - À SUPPRIMER EN PRODUCTION
     */
    public function debugApplePayData(Request $request)
    {
        \Log::info('Apple Pay Debug - All Request Data', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'headers' => $request->headers->all(),
            'all_data' => $request->all(),
            'json_data' => $request->json()->all(),
            'raw_content' => $request->getContent()
        ]);

        return response()->json([
            'message' => 'Debug data logged',
            'received_keys' => array_keys($request->all()),
            'payment_keys' => $request->has('payment') ? array_keys($request->input('payment', [])) : [],
            'billing_keys' => $request->has('billing_contact') ? array_keys($request->input('billing_contact', [])) : [],
            'shipping_keys' => $request->has('shipping_contact') ? array_keys($request->input('shipping_contact', [])) : []
        ]);
    }

    /**
     * NOUVELLE MÉTHODE: Mettre à jour la commande avec les vraies données Apple Pay depuis Stripe
     */
    private function updateOrderWithStripeData($order, $session)
    {
        // Vérifier si c'est une commande avec des données temporaires
        $needsUpdate = ($order->customer_email === 'temp@example.com' || 
                       $order->customer_name === 'Temp Customer' || 
                       $order->shipping_address_line_1 === 'Temp Address');

        if (!$needsUpdate) {
            \Log::info('Order already has real data, skipping update', ['order_number' => $order->order_number]);
            return;
        }

        \Log::info('Updating order with Stripe session data', [
            'order_number' => $order->order_number,
            'session_id' => $session['id'],
            'payment_method_types' => $session['payment_method_types'] ?? [],
            'customer_details' => $session['customer_details'] ?? null,
            'shipping_details' => $session['shipping_details'] ?? null
        ]);

        $updateData = [];
        
        // Extraire les informations client depuis Stripe
        if (isset($session['customer_details'])) {
            $customerDetails = $session['customer_details'];
            
            if (!empty($customerDetails['email'])) {
                $updateData['customer_email'] = $customerDetails['email'];
            }
            
            if (!empty($customerDetails['name'])) {
                $updateData['customer_name'] = $customerDetails['name'];
            }
            
            if (!empty($customerDetails['phone'])) {
                $updateData['customer_phone'] = $customerDetails['phone'];
            }
            
            // Adresse de facturation
            if (!empty($customerDetails['address'])) {
                $billingAddress = $customerDetails['address'];
                
                if (!empty($billingAddress['line1'])) {
                    $updateData['billing_address_line_1'] = $billingAddress['line1'];
                }
                if (!empty($billingAddress['line2'])) {
                    $updateData['billing_address_line_2'] = $billingAddress['line2'];
                }
                if (!empty($billingAddress['city'])) {
                    $updateData['billing_city'] = $billingAddress['city'];
                }
                if (!empty($billingAddress['postal_code'])) {
                    $updateData['billing_postal_code'] = $billingAddress['postal_code'];
                }
                if (!empty($billingAddress['country'])) {
                    $updateData['billing_country'] = strtoupper($billingAddress['country']);
                }
            }
        }
        
        // Extraire les informations de livraison depuis Stripe
        if (isset($session['shipping_details'])) {
            $shippingDetails = $session['shipping_details'];
            
            if (!empty($shippingDetails['name'])) {
                $updateData['customer_name'] = $shippingDetails['name'];
            }
            
            if (!empty($shippingDetails['address'])) {
                $shippingAddress = $shippingDetails['address'];
                
                if (!empty($shippingAddress['line1'])) {
                    $updateData['shipping_address_line_1'] = $shippingAddress['line1'];
                }
                if (!empty($shippingAddress['line2'])) {
                    $updateData['shipping_address_line_2'] = $shippingAddress['line2'];
                }
                if (!empty($shippingAddress['city'])) {
                    $updateData['shipping_city'] = $shippingAddress['city'];
                }
                if (!empty($shippingAddress['postal_code'])) {
                    $updateData['shipping_postal_code'] = $shippingAddress['postal_code'];
                }
                if (!empty($shippingAddress['country'])) {
                    $updateData['shipping_country'] = strtoupper($shippingAddress['country']);
                }
            }
        }
        
        // Si on n'a pas d'adresse de livraison, utiliser l'adresse de facturation
        if (empty($updateData['shipping_address_line_1']) && !empty($updateData['billing_address_line_1'])) {
            $updateData['shipping_address_line_1'] = $updateData['billing_address_line_1'];
            $updateData['shipping_address_line_2'] = $updateData['billing_address_line_2'] ?? null;
            $updateData['shipping_city'] = $updateData['billing_city'] ?? null;
            $updateData['shipping_postal_code'] = $updateData['billing_postal_code'] ?? null;
            $updateData['shipping_country'] = $updateData['billing_country'] ?? null;
        }
        
        // Si on n'a pas d'adresse de facturation, utiliser l'adresse de livraison
        if (empty($updateData['billing_address_line_1']) && !empty($updateData['shipping_address_line_1'])) {
            $updateData['billing_address_line_1'] = $updateData['shipping_address_line_1'];
            $updateData['billing_address_line_2'] = $updateData['shipping_address_line_2'] ?? null;
            $updateData['billing_city'] = $updateData['shipping_city'] ?? null;
            $updateData['billing_postal_code'] = $updateData['shipping_postal_code'] ?? null;
            $updateData['billing_country'] = $updateData['shipping_country'] ?? null;
        }
        
        // Ajouter une note pour traçabilité
        $currentNotes = $order->notes ?: '';
        $updateData['notes'] = $currentNotes . "\nMis à jour avec données Apple Pay via Stripe le " . now()->format('d/m/Y H:i:s');
        
        // Mettre à jour la commande si on a des données
        if (!empty($updateData)) {
            $order->update($updateData);
            
            \Log::info('Order updated successfully with Stripe data', [
                'order_number' => $order->order_number,
                'updated_fields' => array_keys($updateData),
                'customer_email' => $updateData['customer_email'] ?? 'not updated',
                'customer_name' => $updateData['customer_name'] ?? 'not updated',
                'shipping_address' => ($updateData['shipping_address_line_1'] ?? '') . ', ' . ($updateData['shipping_city'] ?? ''),
            ]);
        } else {
            \Log::warning('No data extracted from Stripe session', [
                'order_number' => $order->order_number,
                'session_id' => $session['id']
            ]);
        }
    }
}