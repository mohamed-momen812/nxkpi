<?php

namespace App\Http\Resources;

use App\Models\Entry;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
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
            "id" => $this->id,
            "name" => $this->name,
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
            "category_id" => $this->category_id ,
            'icon' => $this->icon,
            "created_at" => $this->created_at->format('d-m-y') ,
//            'entries'   => EntryResource::collection($this->entries()->lastWeek()->get()),
            'entries'   => $this->enable == 0 ? 'Disabled' : EntryResource::collection($this->getEntries()),
        ];
    }

    public function getEntries()
    {
        if(request()->has('entry_date')){
            $entry_date = Carbon::createFromFormat('Y-m-d', request()->input('entry_date'));

            $dates = [];

            switch ($this->frequency->name){
                case "Daily":
                    $from = $entry_date->copy()->subWeek()->addDay();
                    $to = $entry_date->copy();

                    while ($from->lte($to)) {
                        $dates[] = $from->copy();

                        $from->addDay();
                    }
                    break;
                case "Weekly":
                    $from = $entry_date->copy()->subWeek(6);
                    $to = $entry_date->copy();

                    while ($from->lte($to)) {
                        $dates[] = $from->copy();

                        $from->addWeek();
                    }
                    break;
                case "Monthly":
                    $from = $entry_date->copy()->subMonth(6);
                    $to = $entry_date->copy();

                    while ($from->lte($to)) {
                        $dates[] = $from->copy();

                        $from->addMonth();
                    }
                    break;
                case "Quarterly":
                    $from = $entry_date->copy()->subMonth(18); // 5 past quarters
                    $to = $entry_date->copy();

                    while ($from->lte($to)) {
                        $dates[] = $from->copy();

                        $from->addMonths(3);
                    }
                    break;
                case "Yearly":
                    $from = $entry_date->copy()->subYear(6);
                    $to = $entry_date->copy();

                    while ($from->lte($to)) {
                        $dates[] = $from->copy();

                        $from->addYear();
                    }
                    break;
            }

            $entries = [];

            foreach($dates as $date){
                // if($this->id == 2) dd($dates);
                $entry = Entry::where('kpi_id', $this->id)->where('entry_date', $date->format('y-m-d'))->first();
                if($this->frequency->name == "Weekly"){
                    $weekNo = $date->weekOfYear;
                    $entry = Entry::where('kpi_id', $this->id)->where('weekNo', $weekNo)->first();
                }elseif($this->frequency->name == "Monthly"){
                    $month = $date->month;
                    $entry = Entry::where('kpi_id', $this->id)->where('month', $month)->first();
                }elseif($this->frequency->name == "Quarterly"){
                    $year = $date->year;
                    $quarter = $date->quarter;
                    $entry = Entry::where('kpi_id', $this->id)->where('year', $year)->where('quarter', $quarter)->first();
                }elseif($this->frequency->name == "Yearly"){
                    $year = $date->year;
                    $entry = Entry::where('kpi_id', $this->id)->where('year', $year)->first();
                }
                // dd($date->format('y-m-d'));
                //     dd($entry->entry_date == $date->format('y-m-d'));
                if($entry){
                    $entries[] = $entry;
                }else{
                    $emptyEntry = new Entry();
                    $emptyEntry->kpi_id = $this->id;
                    $emptyEntry->entry_date = $date;
                    $emptyEntry->target = $this->user_target ?? null;

                    $entries[] = $emptyEntry;
                }
            }
            return $entries;
        }else{
            $entries = $this->entries;
            return $entries;
            // if(request()->has('entry_date')){
            //     $entries = $entries->filter(function ($item){
            //         return $item->entry_date == request()->entry_date;
            //     });
            // }
            // // return $entries;
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

}
