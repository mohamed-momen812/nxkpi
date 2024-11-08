<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TenantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
//        return parent::toArray($request);
        return [
            "id"                => $this->id,
            "user_id"           => $this->user_id,
            "tenancy_db_name"   => $this->tenancy_db_name,
            "domains"           => DomainResource::collection($this->domains),

        ];
    }
}
