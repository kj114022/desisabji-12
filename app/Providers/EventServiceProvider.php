<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\MarketChangedEvent;
use App\Events\UserRoleChangedEvent;
use App\Events\OrderChangedEvent;
use App\Listeners\UpdateMarketEarningTableListener;
use App\Listeners\ChangeClientRoleToManager;
use App\Listeners\UpdateUserDriverTableListener;
use App\Listeners\UpdateOrderEarningTable;
use App\Listeners\UpdateOrderDriverTable;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        MarketChangedEvent::class => [
            UpdateMarketEarningTableListener::class,
            ChangeClientRoleToManager::class,
        ],
        UserRoleChangedEvent::class => [
            UpdateUserDriverTableListener::class,
        ],
        OrderChangedEvent::class => [
            UpdateOrderEarningTable::class,
            UpdateOrderDriverTable::class,
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
