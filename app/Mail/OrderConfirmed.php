<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Confirmation de votre commande ' . $this->order->order_number . ' - Heritage Parfums')
                    ->view('emails.order-confirmed')
                    ->with([
                        'order' => $this->order,
                        'items' => $this->order->items()->with('product')->get(),
                    ]);
    }
}
