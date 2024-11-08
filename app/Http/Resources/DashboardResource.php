<?php

namespace App\Http\Resources;

use App\Enums\ChartsEnum;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
//        dd($this->charts);
//        return parent::toArray($request);
        return [
            'id'    => $this->id,
            'name'  => $this->name,
//            'chatrs' => ChartResource::collection($this->whenLoaded('charts')) ,
            'charts' => ChartResource::collection($this->whenLoaded('charts', function () {
                return $this->charts->map(function ($chart) {
                    return new ChartResource($chart, $this);
                });
            })),
//            'entries'=> EntryResource::collection($this->kpi->entries),
//            'chart' => ChartsEnum::class($this->chart)->value,
//            "user" => new UserResource($this->user),
            'kpi'   => new KpiResource($this->kpi),

        ];
    }

}
