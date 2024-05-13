<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionRequest;
use App\Http\Resources\PlanResource;
use App\Http\Resources\SubscriptionResource;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Rennokki\Plans\Models\PlanModel;

class PlanFeatureController extends Controller
{
    public function index()
    {
        $plans = PlanModel::with('features')->get();

        return $this->responseJson( PlanResource::collection($plans) );
    }

    public function show($id)
    {
        $plan = PlanModel::with('features')->findOrFail($id);

        return $this->responseJson( new PlanResource($plan) );
    }

    public function subscribeToPlan(SubscriptionRequest $request)
    {
        $user = auth()->user();
        $company = $user->company()->first();

        $plan = PlanModel::find( $request->plan_id );

        $startsOn = Carbon::now();
        if( $plan->code === "free_trail" ){
            $duration = 7;
            $isRecurring = false;
            $isPaid = false;
        }else{
            $duration = 30;
            $isRecurring = true;
            $isPaid = true;
        }

        // $subscription = $user->subscribeTo($plan, $duration, $isRecurring, $isPaid, $startsOn);
        $subscription = $company->subscribeTo($plan, $duration, $isRecurring, $isPaid, $startsOn);
        return $this->responseJson( new SubscriptionResource($subscription) );
    }

    public function cancelSubscription()
    {
        $user = auth()->user();
        $company = $user->company()->first();

        $cancel = $company->ourCancelCurrentSubscription();
        if($cancel['success']){
            return $this->responseJson();
        }
        return $this->responseJsonFailed($cancel['error']);
    }

    public function getCurrent()
    {
        $user = auth()->user();
        $company = $user->company()->first();
        $plan_tag = request()->plan_tag;
        $checkSubscription = $company->ourHasActiveSubscription();

        if($checkSubscription){
            $subscription = $company->ourActiveSubscription();
            return $this->responseJson( new SubscriptionResource($subscription) );
        }

        return $this->responseJsonFailed('No Active Subscription');
    }

    public function upgradeSubscription(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'start_now' => 'boolean',
            'plan_type' => 'in:yearly,monthly',
        ]);

        $user = auth()->user();
        $company = $user->company()->first();
        $newPlan = PlanModel::find( $request->plan_id );
        $paln_type = $request->plan_type == 'yearly' ? 365 : 30;

        $newSubscription = $company->upgradeCurrentPlanTo($newPlan, $paln_type, $request->start_now);

        return $this->responseJson( new SubscriptionResource($newSubscription) );
    }

    public function getAvailable()
    {
        $user = auth()->user();
        $company = $user->company()->first();

        $checkSubscription = $company->ourHasActiveSubscription();

        if($checkSubscription){
            $subscription = $company->ourActiveSubscription();
           
            $availablePlans = PlanModel::where('price', '>', $subscription->plan->price)->get();
        }else{
            $availablePlans = PlanModel::where('price', '>', 0)->get();
        }

        return $this->responseJson( PlanResource::collection($availablePlans) );
    }

}
