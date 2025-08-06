<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderShipped extends Mailable
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
        return $this->subject('Votre commande ' . $this->order->order_number . ' a été expédiée - Heritage Parfums')
                    ->view('emails.order-shipped')
                    ->with([
                        'order' => $this->order,
                        'items' => $this->order->items()->with('product')->get(),
                    ]);
    }
}
