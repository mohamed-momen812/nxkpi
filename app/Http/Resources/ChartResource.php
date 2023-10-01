<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChartResource extends JsonResource
{

    protected $dashboard;

    public function __construct($resource, $dashboard)
    {
        parent::__construct($resource);
        $this->dashboard = $dashboard;
    }
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
            'id'        => $this->id,
            'type'      => $this->type,
            'entries'   => EntryResource::collection( $this->dashboard->kpi->entries)
        ];
    }
}
