<?php

namespace App\Jobs;

use App\Mail\OrderShipped;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendOrderShippedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;

    /**
     * Create a new job instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->order->customer_email)
                ->send(new OrderShipped($this->order));

            Log::info('Email d\'expédition envoyé pour la commande: ' . $this->order->order_number);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de l\'email d\'expédition pour la commande ' . $this->order->order_number . ': ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Échec d\'envoi de l\'email d\'expédition pour la commande ' . $this->order->order_number . ': ' . $exception->getMessage());
    }
}
