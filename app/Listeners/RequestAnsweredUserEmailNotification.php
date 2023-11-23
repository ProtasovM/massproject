<?php

namespace App\Listeners;

use App\Events\RequestAnsweredEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class RequestAnsweredUserEmailNotification implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(RequestAnsweredEvent $event): void
    {
        //Mail::send();
    }
}
