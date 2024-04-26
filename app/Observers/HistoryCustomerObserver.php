<?php

namespace App\Observers;

use App\Models\Customer;
use App\Models\History;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class HistoryCustomerObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the Customer "created" event.
     */
    public function created(Customer $customer): void
    {
        //
    }

    /**
     * Handle the Customer "updated" event.
     */
    public function updated(Customer $customer): void
    {
        $extingHistoryCustomer = History::where('id_changed_data', $customer->id_customer)->first();

        if ($extingHistoryCustomer) {
            $extingHistoryCustomer->update([
                'older_data' => json_encode($customer->getOriginal()),
                'changed_data' => json_encode($customer->getChanges()),
                'user_id' => auth()->id(),
                'revcount' => ++$extingHistoryCustomer->revcount
            ]);

        } else {
            History::create([
                'id_changed_data' => $customer->id_customer,
                'scope' => 'customer',
                'older_data' => json_encode($customer->getOriginal()),
                'changed_data' => json_encode($customer->getChanges()),
                'action' => 'update',
                'user_id' => auth()->id(),
                'revcount' => 1
            ]);
        }
    }

    /**
     * Handle the Customer "deleted" event.
     */
    public function deleted(Customer $customer): void
    {
        //
    }

    /**
     * Handle the Customer "restored" event.
     */
    public function restored(Customer $customer): void
    {
        //
    }

    /**
     * Handle the Customer "force deleted" event.
     */
    public function forceDeleted(Customer $customer): void
    {
        //
    }
}
