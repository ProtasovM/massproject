<?php

namespace App\Listeners;

use App\Events\RequestAnsweredEvent;

class RequestAnsweredUserEmailNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(RequestAnsweredEvent $event): void
    {
        //
    }
}
