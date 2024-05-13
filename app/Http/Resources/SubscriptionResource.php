<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{

    public static $model = \Rennokki\Plans\Models\PlanSubscriptionModel::class;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            "id"                    => $this->id,
            "starts_on"             => $this->starts_on->format('F j, Y g:i A'),
            "expires_on"            => $this->expires_on->format('F j, Y g:i A'),
            "grace_until"           => $this->grace_until->format('F j, Y g:i A'),
            "cancelled_on"          => $this->cancelled_on?->format('F j, Y g:i A'),
            "payment_method"        => $this->payment_method,
            "is_paid"               => $this->is_paid,
            "charging_price"        => $this->charging_price,
            "charging_currency"     => $this->charging_currency,
            "is_recurring"          => $this->is_recurring,
            "recurring_each_days"   => $this->recurring_each_days,
            "updated_at"            => $this->updated_at->format('F j, Y g:i A'),
            "created_at"            => $this->created_at->format('F j, Y g:i A'),
            "plan"                  => $this->plan,
        ];
    }
}
