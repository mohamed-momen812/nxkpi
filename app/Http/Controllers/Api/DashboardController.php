<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\KpiRepositoryInterface;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ApiTrait;
    private $kpiRepo;
    private $entry;
    private $collectionCount;
    public function __construct(KpiRepositoryInterface $kpiRepoInterface , EntryController $entry)
    {
        $this->kpiRepo = $kpiRepoInterface ;
        $this->entry = $entry ;
    }

    public function totalRatio($kpi_id)
    {
        $kpi_actuals = $this->totalActual($kpi_id);
        $kpi_actual_target = $this->kpiTarget($kpi_id) * $this->collectionCount ;
        $equat = ( ($kpi_actuals - $kpi_actual_target) / $kpi_actual_target ) * 100 ;

        $ratio = eval( "return $equat ;" );

        return $this->responseJson($ratio . "%");
    }

    public function totalActual($kpi_id)
    {
        $entries = $this->entry->getEntriesByKpi($kpi_id);
        $this->collectionCount = $entries->count();
        $actuals = $entries->pluck('actual')->toArray();
        $totalActuals = array_sum($actuals);
        return $totalActuals;
    }//total of actual values in entries of passed kpi

    public function kpiTarget($kpi_id)
    {
        $kpi = $this->kpiRepo->find($kpi_id);

        if ($kpi == null) return "kpi not found";

        $target = $kpi->user_target;

        if ($target == null) return "kpi hasn't Target";

        return $target;
    }

}
