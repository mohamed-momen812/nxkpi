<?php

namespace App\Traits;

use App\Models\Entry;

trait KpiTrait
{
    public function target()
    {
        $target = $this->user_target;

        return $target;
    }

    public function actualTotal()
    {
        $entries = $this->getEntries();
        $this->collectionCount = $entries->count();
        $actuals = $entries->pluck('actual')->toArray();
        $totalActuals = array_sum($actuals);

        return $totalActuals;
    }

    public function totalRatio()
    {
        $kpi_actuals = $this->actualTotal($this->id);
        $kpi_actual_target = $this->target($this->id) * $this->collectionCount ;
        $equat = ( ($kpi_actuals - $kpi_actual_target) / $kpi_actual_target ) * 100 ;

        $ratio = eval( "return $equat ;" );

        return $this->responseJson($ratio . "%");
    }

    public function getEntries()
    {
        return Entry::where('kpi_id' , $this->id)->get();
    }
}