<?php

namespace App\Traits;

use Carbon\Carbon;
use Rennokki\Plans\Traits\HasPlans;

trait OurHasPlansTrait
{
    use HasPlans;

    // don't take tag
    public function ourHasActiveSubscription()
    {
        return (bool) $this->ourActiveSubscription();
    }

    // don't take tag
    public function ourActiveSubscription()
    {
        // dd($this->ourCurrentSubscription()
        // //            ->paid()
        //             ->notCancelled()
        //             ->with(['usages', 'features'])
        //             ->first());
        return $this->ourCurrentSubscription()
//            ->paid()
            ->notCancelled()
            ->with(['usages', 'features'])
            ->first();
    }

    // don't take tag
    public function ourCurrentSubscription()
    {
        return $this
            ->subscriptions()
            // ->when($tag, fn ($q) => $q->whereHas('plan', fn ($query) => $query->where('tag', $tag)))
            ->where('starts_on', '<', Carbon::now())
            ->where('grace_until', '>', Carbon::now())
            ->orderByDesc('starts_on');
    }

    // don't take tag
    public function ourCancelCurrentSubscription()
    {
        if (! $this->ourHasActiveSubscription()) {
            // return false;
            return [
                'success' => false,
                'error' => 'No active subscription found.'
            ]; // false;
        }

        $activeSubscription = $this->ourActiveSubscription();

        if ($activeSubscription->isCancelled() || $activeSubscription->isPendingCancellation()) {
            // return false;
            return [
                'success' => false, // false
                'error' => 'Subscription is already cancelled.'
            ];
        }

        $activeSubscription->update([
            'cancelled_on' => Carbon::now(),
            'is_recurring' => false,
        ]);

        event(new \Rennokki\Plans\Events\CancelSubscription($this, $activeSubscription));

        return $activeSubscription;
    }
}
