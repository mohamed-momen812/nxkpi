<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionRequest;
use App\Http\Resources\PlanResource;
use App\Http\Resources\SubscriptionResource;
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
        
        $subscription = $user->subscribeTo($plan, $duration, $isRecurring, $isPaid, $startsOn);
        return $this->responseJson( new SubscriptionResource($subscription) );
    }

    public function cancelSubscription()
    {
        $user = auth()->user();
        $user->cancelCurrentSubscription();
        return $this->responseJson( $data = null );
    }
}