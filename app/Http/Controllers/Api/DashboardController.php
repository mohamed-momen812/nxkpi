<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\DashboardRequest;
use App\Repositories\DashboardRepository;
use App\Traits\ApiTrait;
use App\Models\Dashboard;
use App\Http\Controllers\Controller;
use App\Http\Resources\DashboardResource;
use App\Interfaces\KpiRepositoryInterface;


class DashboardController extends Controller
{
    use ApiTrait;

    private $kpiRepo;

    private $dashboardRepo;

    public function __construct(KpiRepositoryInterface $kpiRepoInterface, DashboardRepository $dashboardRepo)
    {
        $this->kpiRepo = $kpiRepoInterface ;
        $this->dashboardRepo = $dashboardRepo;
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

    public function store(DashboardRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = auth()->user()->id ;

        $dashboard = $this->dashboardRepo->create( $data );
        if ($dashboard)
        {
            return $this->responseJson(new DashboardResource($dashboard));
        }

        return $this->responseJsonFailed();
    }

    public function show(Dashboard $dashboard)
    {
        return $this->responseJson(new DashboardResource($dashboard->load('charts')));
    }
    public function update(DashboardRequest $request, $id)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->user()->id ;

        $dashboard = $this->dashboardRepo->update( $data, $id);
        if ($dashboard)
        {
            return $this->responseJson(new DashboardResource($dashboard));
        }

        return $this->responseJsonFailed();
    }

    public function destroy($id)
    {
        $dashboard = $this->dashboardRepo->destroy($id);

        return $this->responseJson([
            'message' => 'dashboard deleted successfully',
        ]);
    }
}
