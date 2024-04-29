<?php

namespace App\Observers;

use App\Models\SeaShipmentLine;
use App\Models\History;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class HistorySeaShipmentLineObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the SeaShipmentLine "created" event.
     */
    public function created(SeaShipmentLine $seaShipmentLine): void
    {
        //
    }

    /**
     * Handle the SeaShipmentLine "updated" event.
     */
    public function updated(SeaShipmentLine $seaShipmentLine): void
    {
        $extingHistorySeaShipmentLine = History::where('id_changed_data', $seaShipmentLine->id_sea_shipment_line)->where('scope', 'seaShipmentLine')->first();

        if ($extingHistorySeaShipmentLine) {
            $extingHistorySeaShipmentLine->update([
                'older_data' => json_encode($seaShipmentLine->getOriginal()),
                'changed_data' => json_encode($seaShipmentLine->getChanges()),
                'user_id' => auth()->id(),
                'revcount' => ++$extingHistorySeaShipmentLine->revcount
            ]);

        } else {
            History::create([
                'id_changed_data' => $seaShipmentLine->id_sea_shipment_line,
                'scope' => 'seaShipmentLine',
                'older_data' => json_encode($seaShipmentLine->getOriginal()),
                'changed_data' => json_encode($seaShipmentLine->getChanges()),
                'action' => 'update',
                'user_id' => auth()->id(),
                'revcount' => 1
            ]);
        }
    }

    /**
     * Handle the SeaShipmentLine "deleted" event.
     */
    public function deleted(SeaShipmentLine $seaShipmentLine): void
    {
        //
    }

    /**
     * Handle the SeaShipmentLine "restored" event.
     */
    public function restored(SeaShipmentLine $seaShipmentLine): void
    {
        //
    }

    /**
     * Handle the SeaShipmentLine "force deleted" event.
     */
    public function forceDeleted(SeaShipmentLine $seaShipmentLine): void
    {
        //
    }
}
