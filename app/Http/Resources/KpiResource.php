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
                    // $period = CarbonPeriod::create('2018-06-14', '2018-06-20');

                    // // Iterate over the period
                    // foreach ($period as $date) {
                    //     echo $date->format('Y-m-d');
                    // }

                    // // Convert the period to an array of dates
                    // $dates = $period->toArray();
                    $from = $entry_date->copy()->subWeek();
                    $to = $entry_date->copy();

                    while ($from->lte($to)) {
                        $dates[] = $from->copy();

                        $from->addDay();
                    }

                    break;
                    // if($this->id == 2)dd($dates);
                case "Weakly":
                    $scope = ['from' => $entry_date, 'to'=> $entry_date->subWeek(6)];

                    $currentDate = $scope['from']->copy()->startOfWeek();

                    while ($currentDate->lte($scope['to'])) {
                        $dates[] = $currentDate->copy();
                        $currentDate->addWeek();
                    }
                    break;
                case "Monthly":
                    $scope = ['from' => $entry_date, 'to'=> $entry_date->subMonth(6)];

                    $currentDate = $scope['to']->copy()->startOfMonth();

                    for ($i = 0; $i < 7; $i++) {
                        $dates[] = $currentDate->copy();
                        $currentDate->subMonth();
                    }
                case "Quarterly":
                    $scope = ['from' => $entry_date, 'to'=> $entry_date->subMonth(18)];

                    $currentDate = $scope['to']->copy()->startOfMonth();

                    for ($i = 0; $i < 6; $i++) {
                        $dates[] = $currentDate->copy()->startOfQuarter();
                        $currentDate->subMonths(3);
                    }
                case "Yearly":
                    $scope = ['from' => $entry_date, 'to'=> $entry_date->subYear(6)];

                    $currentDate = $scope['to']->copy()->startOfYear();

                    for ($i = 0; $i < 6; $i++) {
                        $dates[] = $currentDate->copy()->startOfYear();
                        $currentDate->subYear();
                    }
            }

            $entries = [];

            foreach($dates as $date){
                // if($this->id == 2) dd($this->entries()->first()->entry_date);
                $entry = Entry::where('kpi_id', $this->id)->where('entry_date', $date->format('y-m-d'))->first();

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
