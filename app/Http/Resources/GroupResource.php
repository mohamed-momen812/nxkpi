<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
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
            "sort_order" => $this->sort_order ,
            "created_at" => $this->created_at,
            "users"      => UserResource::collection($this->users),
        ];
    }
}
