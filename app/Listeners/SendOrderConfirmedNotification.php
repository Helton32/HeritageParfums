<?php

namespace App\Listeners;

use App\Events\OrderPaid;
use App\Jobs\SendOrderConfirmedEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendOrderConfirmedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(OrderPaid $event): void
    {
        // Déclencher l'envoi de l'email de confirmation
        SendOrderConfirmedEmail::dispatch($event->order);
    }

    /**
     * Handle a job failure.
     */
    public function failed(OrderPaid $event, \Throwable $exception): void
    {
        \Log::error('Échec du listener pour OrderPaid: ' . $exception->getMessage());
    }
}
