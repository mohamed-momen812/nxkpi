<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\KpiResource;
use App\Interfaces\KpiRepositoryInterface;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    use ApiTrait;
    private $kpiRepo;
    public function __construct(KpiRepositoryInterface $kpiRepo)
    {
        $this->kpiRepo = $kpiRepo;
    }

    public function topPerform()
    {
        $kpis = $this->kpiRepo->all();
        $sortedKpis = $kpis->reject(function ($kpi) {
            return $kpi->totalRatio() === null;
        })->sortByDesc(function ($kpi) {
            return $kpi->totalRatio();
        });

        return $this->responseJson(KpiResource::collection($sortedKpis));
    }

    public function worstPerform()
    {
        $kpis = $this->kpiRepo->all();
        $sortedKpis = $kpis->reject(function ($kpi) {
            return $kpi->totalRatio() === null;
        })->sortBy(function ($kpi) {
            return $kpi->totalRatio();
        });

        return $this->responseJson(KpiResource::collection($sortedKpis));
    }

    public function multipliKpis()
    {
        $kpis = $this->kpiRepo->all();
        return $this->responseJson(KpiResource::collection($kpis));
    }


}
