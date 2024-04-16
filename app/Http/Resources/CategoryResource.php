<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            "id"            => $this->id ,
            "name"          => $this->name ,
            "sort_order"    => $this->sort_order ,
            "kpis"          => KpiResource::collection($this->kpis),
            // "user"          => new UserResource($this->user) ,
            "created_at"    => $this->created_at->format('d-m-y') ,
        ];
    }
}
