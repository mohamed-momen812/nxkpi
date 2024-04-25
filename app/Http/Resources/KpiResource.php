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
//        dd($this->entries()->lastWeek()->get());
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
            'equation_result' => $this->result_equation ,
            'thresholds' => $this->thresholds,
            'enable' => $this->enable,
            'working_weeks' => $this->working_weeks,
            'total_ratio' => $this->totalRatio(),
            // "user" => new UserResource($this->user) ,
            "frequency" => new FrequencyResource($this->frequency) ,
        //    "category" => new CategoryResource($this->category) ,
            "created_at" => $this->created_at->format('d-m-y') ,
//            'entries'   => EntryResource::collection($this->entries()->lastWeek()->get()),
            'entries'   => $this->enable == 0 ? 'Disabled' : EntryResource::collection($this->getEntries()),
        ];
    }

    public function getEntries()
    {
        $entries = $this->entries;
        if(request()->has('entry_date')){
            $entries = $entries->filter(function ($item){
                return $item->entry_date == request()->entry_date;
            });
        }

        return $entries;
        // return $this->entries->filter(function ($item){
        //     switch ($this->frequency->name){
        //         case "Daily":
        //             return $item->entry_date >= now()->subWeek();
        //             break;
        //         case "Weakly":
        //             return $item->entry_date >= now()->subWeek(6) ;
        //             break;
        //         case "Monthly":
        //             return $item->entry_date >= now()->subMonth(6);
        //         case "Quarterly":
        //             return $item->entry_date >= now()->subMonth(18);
        //         case "Yearly":
        //             return  $item->entry_date >= now()->subYear(6);
        //         default :
        //             return $item;
        //     }
        // });
    }

}
