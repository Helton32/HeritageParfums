<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Afficher la page de confirmation de commande
     */
    public function confirmation($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
                     ->with('items.product')
                     ->first();

        if (!$order) {
            return redirect()->route('home')->with('error', 'Commande introuvable.');
        }

        return view('orders.confirmation', compact('order'));
    }

    /**
     * Afficher les détails d'une commande
     */
    public function show($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
                     ->with('items.product')
                     ->first();

        if (!$order) {
            return redirect()->route('home')->with('error', 'Commande introuvable.');
        }

        return view('orders.show', compact('order'));
    }
}
