<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    // ID du produit unique "Éternelle Rose"
    private $productId = 1;

    public function index()
    {
        $cart = $this->getCart();
        $product = Product::find($this->productId);
        
        if (!$product) {
            return redirect('/')->with('error', 'Produit introuvable.');
        }

        $cartData = [
            'product' => $product,
            'quantity' => $cart['quantity'] ?? 0,
            'subtotal' => ($cart['quantity'] ?? 0) * $product->price,
            'tax_amount' => (($cart['quantity'] ?? 0) * $product->price) * 0.20,
            'shipping_amount' => (($cart['quantity'] ?? 0) * $product->price) >= 150 ? 0 : 9.90,
        ];

        $cartData['total'] = $cartData['subtotal'] + $cartData['tax_amount'] + $cartData['shipping_amount'];

        return view('cart.index', compact('cartData'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:5' // Limite pour édition limitée
        ]);

        $product = Product::find($this->productId);
        
        if (!$product || !$product->isInStock()) {
            return response()->json([
                'success' => false,
                'message' => 'Ce produit n\'est plus disponible.'
            ]);
        }

        $quantity = $request->quantity;
        $cart = $this->getCart();

        // Pour un produit unique, on remplace la quantité
        $cart = [
            'product_id' => $this->productId,
            'quantity' => $quantity,
            'added_at' => now()->toDateTimeString()
        ];

        $this->saveCart($cart);

        return response()->json([
            'success' => true,
            'message' => 'Éternelle Rose ajouté au panier !',
            'cart_count' => $quantity
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0|max:5'
        ]);

        $quantity = $request->quantity;

        if ($quantity == 0) {
            $this->clear();
            return response()->json([
                'success' => true,
                'cart_count' => 0
            ]);
        }

        $cart = [
            'product_id' => $this->productId,
            'quantity' => $quantity,
            'updated_at' => now()->toDateTimeString()
        ];

        $this->saveCart($cart);

        return response()->json([
            'success' => true,
            'cart_count' => $quantity
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
        $cart = $this->getCart();
        
        return response()->json([
            'count' => $cart['quantity'] ?? 0
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
        $product = Product::find($this->productId);

        if (!$product || !isset($cart['quantity'])) {
            return [
                'items' => [],
                'subtotal' => 0,
                'tax_amount' => 0,
                'shipping_amount' => 9.90,
                'total' => 9.90,
                'count' => 0
            ];
        }

        $quantity = $cart['quantity'];
        $subtotal = $product->price * $quantity;
        $taxAmount = $subtotal * 0.20;
        $shippingAmount = $subtotal >= 150 ? 0 : 9.90;
        $total = $subtotal + $taxAmount + $shippingAmount;

        $items = [[
            'product_id' => $product->id,
            'name' => $product->name,
            'type' => $product->type,
            'size' => $product->size,
            'price' => $product->price,
            'quantity' => $quantity,
            'total' => $subtotal,
            'image' => $product->main_image
        ]];

        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'shipping_amount' => $shippingAmount,
            'total' => $total,
            'count' => $quantity
        ];
    }

    public function getCartCount()
    {
        $cart = $this->getCart();
        return $cart['quantity'] ?? 0;
    }
}