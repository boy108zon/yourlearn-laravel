<?php

namespace App\Listeners;

use App\Models\LogActivity;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Request;

class LogActivityListener
{
    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle(object $event): void
    {
        
        if ($event instanceof Login) {
            $this->logLogin($event);
        }

        if ($event instanceof Logout) {
            $this->logLogout($event);
        }

        if ($event instanceof Failed) {
            $this->logFailedLogin($event);
        }
    }

    /**
     * Log a successful login.
     *
     * @param Login $event
     * @return void
     */
    protected function logLogin(Login $event): void
    {
        LogActivity::create([
            'event' => 'logged in',
            'description' => $event->user->name . ' logged in at ' . now()->format('m-d-Y H:i:s') . ' from IP ' . Request::ip(),
            'ip_address' => Request::ip(),
            'user_id' => $event->user->id, 
        ]);
    }

    /**
     * Log a logout event.
     *
     * @param Logout $event
     * @return void
     */
    protected function logLogout(Logout $event): void
    {
        LogActivity::create([
            'event' => 'logged out',
            'description' => $event->user->name . ' logged out at ' . now()->format('m-d-Y H:i:s'),
            'ip_address' => Request::ip(),
            'user_id' => $event->user->id, 
        ]);
    }

    /**
     * Log a failed login attempt.
     *
     * @param Failed $event
     * @return void
     */
    protected function logFailedLogin(Failed $event): void
    {
        LogActivity::create([
            'event' => 'login failed',
            'description' => 'Someone tried to login with username ' . $event->credentials['email'] . ' at ' . now()->format('m-d-Y H:i:s') . ' from IP ' . Request::ip(),
            'ip_address' => Request::ip(),
            'user_id' => null,  
        ]);
    }
}
