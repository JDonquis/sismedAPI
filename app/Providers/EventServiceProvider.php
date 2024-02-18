<?php

namespace App\Providers;

use App\Events\EntryCreated;
use App\Events\EntryDeleted;
use App\Events\InventoryLoteCreated;
use App\Events\InventoryLoteDeleted;
use App\Events\OutputCreated;
use App\Events\OutputDeleted;
use App\Listeners\HandleDestinyEntriesAfterOutputCreated;
use App\Listeners\HandleDestinyInventoryAfterOutputCreated;
use App\Listeners\HandleInventoryAfterEntryCreated;
use App\Listeners\HandleInventoryAfterEntryDeleted;
use App\Listeners\HandleInventoryAfterOutputCreated;
use App\Listeners\HandleInventoryAfterOutputDeleted;
use App\Listeners\RefreshInventoryGeneral;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        EntryCreated::class => [
            HandleInventoryAfterEntryCreated::class,
        ],
        EntryDeleted::class => [
            HandleInventoryAfterEntryDeleted::class,
        ],

        OutputCreated::class => [
            HandleInventoryAfterOutputCreated::class,
            HandleDestinyInventoryAfterOutputCreated::class,
            // HandleDestinyEntriesAfterOutputCreated::class,
        ],
        OutputDeleted::class => [
            HandleInventoryAfterOutputDeleted::class,
        ],
        InventoryLoteCreated::class => [
            RefreshInventoryGeneral::class,
        ],
        InventoryLoteDeleted::class => [
            RefreshInventoryGeneral::class,
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
