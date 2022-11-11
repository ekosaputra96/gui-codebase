<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        // add users management to admilte sidebar menu
        Event::listen(BuildingMenu::class, function (BuildingMenu $event) {
            // Add some items to the menu...
            if(auth()->user()->hasRole('superadministrator')){
                $event->menu->addAfter('change_password', [
                    'text'  => 'Users Management',
                    'icon'  => 'fas fa-fw fa-users-cog',
                    'url'   => 'laratrust'
                ]);
            }
        });
    }
}
