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
            'format' =>$this->format,
            'direction' =>$this->direction,
            'aggregated' => $this->aggregated,
            'target_calculated' => $this->target_calculated,
            'equation' => $this->equation,
            'thresholds' => $this->thresholds,
            "user" => new UserResource($this->user) ,
            "frequency" => new FrequencyResource($this->frequency) ,
            "category" => new CategoryResource($this->category) ,
            "created_at" => $this->created_at->format('d-m-y') ,
        ];
    }

}
