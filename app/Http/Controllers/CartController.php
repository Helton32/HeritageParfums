<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cartData = $this->getCartData();
        
        return view('cart.index', compact('cartData'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        $product = Product::findOrFail($request->product_id);
        
        if (!$product->is_active || !$product->isInStock()) {
            return response()->json([
                'success' => false,
                'message' => 'Ce produit n\'est plus disponible.'
            ]);
        }

        $quantity = $request->quantity;
        $cart = $this->getCart();

        // Si le produit existe déjà dans le panier, on ajoute à la quantité existante
        if (isset($cart[$product->id])) {
            $newQuantity = $cart[$product->id]['quantity'] + $quantity;
            
            // Vérifier les limites de stock et quantité max
            if ($newQuantity > $product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => "Stock insuffisant. Seulement {$product->stock} unités disponibles."
                ]);
            }
            
            if ($newQuantity > 10) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quantité maximale de 10 unités par produit.'
                ]);
            }
            
            $cart[$product->id]['quantity'] = $newQuantity;
        } else {
            // Nouveau produit dans le panier
            $cart[$product->id] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'added_at' => now()->toDateTimeString()
            ];
        }

        $this->saveCart($cart);

        return response()->json([
            'success' => true,
            'message' => "{$product->name} ajouté au panier !",
            'cart_count' => $this->getCartCount()
        ]);
    }
    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:0|max:10'
        ]);

        $productId = $request->product_id;
        $quantity = $request->quantity;
        $cart = $this->getCart();

        if ($quantity == 0) {
            // Supprimer le produit du panier
            unset($cart[$productId]);
        } else {
            // Vérifier le stock
            $product = Product::findOrFail($productId);
            if ($quantity > $product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => "Stock insuffisant. Seulement {$product->stock} unités disponibles."
                ]);
            }

            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] = $quantity;
                $cart[$productId]['updated_at'] = now()->toDateTimeString();
            }
        }

        $this->saveCart($cart);

        return response()->json([
            'success' => true,
            'cart_count' => $this->getCartCount()
        ]);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer'
        ]);

        $productId = $request->product_id;
        $cart = $this->getCart();

        unset($cart[$productId]);
        $this->saveCart($cart);

        return response()->json([
            'success' => true,
            'cart_count' => $this->getCartCount()
        ]);
    }

    public function clear()
    {
        Session::forget('cart');
        
        return response()->json([
            'success' => true,
            'cart_count' => 0
        ]);
    }
    public function count()
    {
        return response()->json([
            'count' => $this->getCartCount()
        ]);
    }

    private function getCart()
    {
        return Session::get('cart', []);
    }

    private function saveCart($cart)
    {
        Session::put('cart', $cart);
    }

    public function getCartData()
    {
        $cart = $this->getCart();
        
        if (empty($cart)) {
            return [
                'items' => [],
                'subtotal' => 0,
                'tax_amount' => 0,
                'shipping_amount' => 9.90,
                'total' => 9.90,
                'count' => 0
            ];
        }

        $items = [];
        $subtotal = 0;
        $totalQuantity = 0;

        foreach ($cart as $productId => $cartItem) {
            $product = Product::find($productId);
            
            if (!$product || !$product->is_active) {
                // Supprimer les produits invalides du panier
                unset($cart[$productId]);
                continue;
            }

            $quantity = $cartItem['quantity'];
            $currentPrice = $product->getCurrentPrice(); // Utilise le prix avec promotion si applicable
            $itemTotal = $currentPrice * $quantity;
            
            $items[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'type' => $product->type,
                'size' => $product->size,
                'price' => $currentPrice,
                'original_price' => $product->price,
                'is_on_promotion' => $product->hasValidPromotion(),
                'promotion_percentage' => $product->getDiscountPercentage(),
                'quantity' => $quantity,
                'total' => $itemTotal,
                'image' => $product->main_image,
                'category' => $product->category
            ];

            $subtotal += $itemTotal;
            $totalQuantity += $quantity;
        }

        // Sauvegarder le panier nettoyé s'il y a eu des suppressions
        $this->saveCart($cart);

        $taxAmount = $subtotal * 0.20; // TVA 20%
        $shippingAmount = $subtotal >= 150 ? 0 : 9.90;
        $total = $subtotal + $taxAmount + $shippingAmount;

        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'shipping_amount' => $shippingAmount,
            'total' => $total,
            'count' => $totalQuantity
        ];
    }

    public function getCartCount()
    {
        $cart = $this->getCart();
        $totalQuantity = 0;

        foreach ($cart as $cartItem) {
            $totalQuantity += $cartItem['quantity'];
        }

        return $totalQuantity;
    }
}