<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class EntryResource extends JsonResource
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
            "entry_date" => Carbon::parse($this->entry_date)->format('d F Y'),
            "actual" => $this->actual ,
            "target"=> $this->target,
            "notes" => $this->notes,
            "day" => $this->day ,
            "weekNo" => $this->weekNo ,
            "month" => $this->month ,
            "quarter" => $this->quarter,
            "year" => $this->year ,
            "range_date" => $this->range_date ,
            "created_at" => $this->created_at->format('d-m-y'),
            "user" => new UserResource($this->user),
            "kpi" => new KpiResource($this->kpi) ,
        ];
    }

}