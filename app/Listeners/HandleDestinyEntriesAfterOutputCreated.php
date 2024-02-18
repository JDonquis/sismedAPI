<?php

namespace App\Listeners;

use App\Models\Entry;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleDestinyEntriesAfterOutputCreated
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
    public function handle(object $event): void
    {   
        $entry = $event->newOutput;

        if($event->destiny !== 'nocode')
        {
        $lastEntryCode = Entry::where('entity_code',$event->destiny)->orderBy('entry_code','desc')->pluck('entry_code')->first();
                Entry::create(
                [
                    'user_id' => $entry['user_id'],
                    'entity_code' => $event->destiny,
                    'entry_code' => $lastEntryCode,
                    'product_id' => $entry['product_id'],
                    'condition_id' => $entry['condition_id'],
                    'quantity' => $entry['quantity'],
                    'organization_id' => $entry['organization_id'],
                    'guide' => $entry['guide'],
                    'expiration_date' => $entry['expiration_date'],
                    'authority_fullname' => $entry['authority_fullname'],
                    'authority_ci' => $entry['authority_ci'],
                    'day' => $entry['day'],
                    'month' => $entry['month'],
                    'year' => $entry['year'],
                    'description' => $entry['description'],
                    'lote_number' => $entry['lote_number'],
                    'arrival_time' => $entry['departure_time'],
                    'created_at' => $entry['created_at'],
                    'search' => $entry['search'],
                ]);
        }
    }
}
