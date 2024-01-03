<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
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
            "id"            => $this->id,
            "name"          => $this->name,
            "code"          => $this->code,
            "tag"           => $this->tag,
            "description"   => $this->description,
            "price"         => $this->price,
            "currency"      => $this->currency,
            "duration in days" => $this->duration,
            "created_at"    => $this->created_at->format('d-m-y'),
            "features"      => FeatureResource::collection($this->whenLoaded("features")),
        ];
    }
}
