<?php

namespace App\Listeners;

use App\Events\OrderShipped as OrderShippedEvent;
use App\Jobs\SendOrderShippedEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendOrderShippedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(OrderShippedEvent $event): void
    {
        // Déclencher l'envoi de l'email d'expédition
        SendOrderShippedEmail::dispatch($event->order);
    }

    /**
     * Handle a job failure.
     */
    public function failed(OrderShippedEvent $event, \Throwable $exception): void
    {
        \Log::error('Échec du listener pour OrderShipped: ' . $exception->getMessage());
    }
}
