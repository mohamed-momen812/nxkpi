<?php

namespace App\Traits;

use App\Models\Company;
use Carbon\Carbon;
use Rennokki\Plans\Models\PlanModel;

trait SubscriptionTrait
{

    public function subscribeToPlan(Company $company, PlanModel $plan, $subscriptionType = 'monthly')
    {
        $startsOn = Carbon::now();
        // $expiresOn = $subscriptionType === 'yearly' ? Carbon::now()->addYear() : Carbon::now()->addMonth();
        if( $plan->code === "free_trail" ){
            $duration = 7;
            $isRecurring = false;
            $isPaid = false;
        }else{
            $duration = $subscriptionType === 'yearly' ? 365 : 30;
            $isRecurring = true;
            $isPaid = true;
        }

        // $subscription = $user->subscribeTo($plan, $duration, $isRecurring, $isPaid, $startsOn);
        $subscription = $company->subscribeTo($plan, $duration, $isRecurring, $isPaid, $startsOn);
        return $subscription;
    }

}
