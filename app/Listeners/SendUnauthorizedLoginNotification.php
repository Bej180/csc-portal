<?php 


namespace App\Listeners;

use App\Events\FailedLoginAttempt;
use App\Notifications\UnauthorizedLoginAttempt;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendUnauthorizedLoginNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(FailedLoginAttempt $event)
    {
        $user = $event->user;
        $user->notify(new UnauthorizedLoginAttempt());
    }
}