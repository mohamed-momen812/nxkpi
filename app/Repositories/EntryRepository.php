<?php

namespace App\Repositories;

use App\Interfaces\EntryRepositoryInterface;
use App\Models\Entry;
use App\Models\Kpi;
use Carbon\Carbon;

class EntryRepository extends BaseRepository implements EntryRepositoryInterface
{
    public function __construct(Entry $model)
    {
        parent::__construct($model);
    }

    public function getEntriesByKpi($kpi_id)
    {
        return $this->model->where('kpi_id' , $kpi_id)->get();
    }

    public function getLastWeekData()
    {
        $oneWeekAgo = Carbon::now()->subWeek();

        return $this->model->where('created_at', '>=', $oneWeekAgo)->groupBy();
    }

    public function createOrUpdate($data, Kpi $kpi)
    {
        // check if kpi frequency is weekly
        if($kpi->frequency->id == 2){
            // check if entry already exist by weekNo
            $entry = $this->model->where('kpi_id' , $kpi->id)->where('weekNo' , $data['weekNo'])->first();
            if($entry){
                $this->update($data , $entry->id);
            }else{
                $entry = $this->create($data);
            }
            return $entry;
        }elseif($kpi->frequency->id == 3){ // check if kpi frequency is monthly
            // check if entry already exist by month
            $entry = $this->model->where('kpi_id' , $kpi->id)->where('month', $data['month'])->first();
            if($entry){
                $this->update($data , $entry->id);
            }else{
                $entry = $this->create($data);
            }
            return $entry;
        }
    }
}
