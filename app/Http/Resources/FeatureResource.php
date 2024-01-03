<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FeatureResource extends JsonResource
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
            "id"        => $this->id,
            "name"      => $this->name,
            "code"      => $this->code,
            "description" => $this->description,
            "type"      => $this->type,
            "limit"     => $this->limit,
            "created_at"=> $this->created_at->format('d-m-y'),
        ];
    }
}
