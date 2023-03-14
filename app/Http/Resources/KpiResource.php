<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KpiResource extends JsonResource
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
            "id" => $this->id ,
            "name" => $this->name ,
            "description" => $this->description,
            "user_target" => $this->user_target ,
            "sort_order" => $this->sort_order,
            "user" => new UserResource($this->user) ,
            "frequency" => new FrequencyResource($this->frequency) ,
            "category" => new CategoryResource($this->category) ,
            "created_at" => $this->created_at->format('d-m-y') ,
        ];
    }

}
