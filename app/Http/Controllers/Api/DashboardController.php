<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiTrait;
use App\Models\Dashboard;
use App\Http\Controllers\Controller;
use App\Http\Resources\DashboardResource;
use App\Interfaces\KpiRepositoryInterface;


class DashboardController extends Controller
{
    use ApiTrait;

    private $kpiRepo;

    public function __construct(KpiRepositoryInterface $kpiRepoInterface)
    {
        $this->kpiRepo = $kpiRepoInterface ;
    }

    public function totalRatio($kpi_id)
    {
        $kpi = $this->kpiRepo->find($kpi_id);

        return $this->responseJson($kpi->totalRatio() . "%");
    }

    public function totalActual($kpi_id)
    {
        $kpi = $this->kpiRepo->find($kpi_id);

        return $kpi->actualTotal();
    }

    public function kpiTarget($kpi_id)
    {
        $kpi = $this->kpiRepo->find($kpi_id);

        return $kpi->target();
    }

    public function index()
    {
        $dashboards = Dashboard::where('user_id' , auth()->user()->id)->get();
        if ($dashboards)
        {
            return $this->responseJson(DashboardResource::collection($dashboards));
        }

        return $this->responseJsonFailed();
    }
}
