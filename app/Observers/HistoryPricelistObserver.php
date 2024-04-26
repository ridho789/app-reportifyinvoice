<?php

namespace App\Observers;

use App\Models\Pricelist;
use App\Models\History;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class HistoryPricelistObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the Pricelist "created" event.
     */
    public function created(Pricelist $pricelist): void
    {
        //
    }

    /**
     * Handle the Pricelist "updated" event.
     */
    public function updated(Pricelist $pricelist): void
    {
        $extingHistoryPricelist = History::where('id_changed_data', $pricelist->id_pricelist)->first();

        if ($extingHistoryPricelist) {
            $extingHistoryPricelist->update([
                'older_data' => json_encode($pricelist->getOriginal()),
                'changed_data' => json_encode($pricelist->getChanges()),
                'user_id' => auth()->id(),
                'revcount' => ++$extingHistoryPricelist->revcount
            ]);

        } else {
            History::create([
                'id_changed_data' => $pricelist->id_pricelist,
                'scope' => 'pricelist',
                'older_data' => json_encode($pricelist->getOriginal()),
                'changed_data' => json_encode($pricelist->getChanges()),
                'action' => 'update',
                'user_id' => auth()->id(),
                'revcount' => 1
            ]);
        }
    }

    /**
     * Handle the Pricelist "deleted" event.
     */
    public function deleted(Pricelist $pricelist): void
    {
        //
    }

    /**
     * Handle the Pricelist "restored" event.
     */
    public function restored(Pricelist $pricelist): void
    {
        //
    }

    /**
     * Handle the Pricelist "force deleted" event.
     */
    public function forceDeleted(Pricelist $pricelist): void
    {
        //
    }
}
