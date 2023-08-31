<?php

namespace App\Http\Controllers\Api;

use App\Models\Chart;
use App\Traits\ApiTrait;
use App\Enums\ChartsEnum;
use Illuminate\Http\Request;
use App\Http\Requests\ChartRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChartResource;
use App\Repositories\ChartRepository;
use App\Http\Requests\AttachKpiRequest;
use App\Interfaces\KpiRepositoryInterface;

class ChartController extends Controller
{
    use ApiTrait;

    private $chartRepository;

    public function __construct(ChartRepository $chartRepository)
    {
        $this->chartRepository = $chartRepository;
    }

    public function index(Request $request)
    {
        $charts = Chart::where('dashboard_id' , $request->dashboard_id)->get();

        if ($charts)
        {
            return $this->responseJson(ChartResource::collection($charts));
        }

        return $this->responseJsonFailed();
    }

    public function store(ChartRequest $request)
    {
        dd($request->validated());
        $chart = $this->chartRepository->create($request->validated());

        return $chart ? $this->responseJson(new ChartResource($chart)) : $this->responseJsonFailed();
    }

    public function show(Chart $chart)
    {
        return $this->responseJson(new ChartResource($chart->load('kpis')));
    }

    public function update(ChartRequest $request, Chart $chart)
    {
        $chart = $this->chartRepository->update($request->validated(), $chart->id);

        return $chart ? $this->responseJson(new ChartResource($chart)) : $this->responseJsonFailed();
    }

    public function destroy($id)
    {
        $this->chartRepository->destroy($id);

        return $this->responseJson([
            'message' => 'Chart deleted successfully',
        ]);
    }

    public function getChartTypes()
    {
        return $this->responseJson(ChartsEnum::collection());
    }

    public function attachKpi(AttachKpiRequest $request)
    {
        $chart = $this->chartRepository->find($request->chart_id);

        $chart->attach($request->kpi_id);

        return $this->responseJson([
            'message' => 'Kpi Attached successfully',
        ]);
    }
}
