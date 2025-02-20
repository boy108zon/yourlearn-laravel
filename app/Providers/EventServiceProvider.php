<?php

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use App\Listeners\LogActivityListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class => [
            LogActivityListener::class,
        ],
        Logout::class => [
            LogActivityListener::class,
        ],
        Failed::class => [
            LogActivityListener::class,
        ],
    ];

    
    public function boot()
    {
        parent::boot();

    }
}
