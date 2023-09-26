<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\KpiResource;
use App\Interfaces\KpiRepositoryInterface;
use App\Traits\ApiTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    use ApiTrait;
    private $kpiRepo;
    public function __construct(KpiRepositoryInterface $kpiRepo)
    {
        $this->kpiRepo = $kpiRepo;
    }

    public function topPerform(Request $request)
    {

        $kpis = $this->kpiRepo->search($request);
//        $kpis = $this->kpiRepo->all();

//        $kpis = $kpis->filter(function ($kpi) use ($request) {
//            $createdAt = Carbon::parse($kpi->created_at);
//            return $createdAt->between(Carbon::parse($request->from), Carbon::parse($request->to ?? now() )) ;
//        });
//        // Filter based on user IDs
//        if ( $request->filled('user_ids') ) {
//            $kpis = $kpis->filter(function ($kpi) use ($request) {
//                return $kpi->users->pluck('id')->intersect($request->user_ids)->count() > 0;
//            });
//        }
        $sortedKpis = $kpis->reject(function ($kpi) {
            return $kpi->totalRatio() === null;
        })->sortByDesc(function ($kpi) {
            return $kpi->totalRatio();
        });

        return $this->responseJson(KpiResource::collection($sortedKpis));
    }

    public function worstPerform(Request $request)
    {
        $kpis = $this->kpiRepo->search($request);
        $sortedKpis = $kpis->reject(function ($kpi) {
            return $kpi->totalRatio() === null;
        })->sortBy(function ($kpi) {
            return $kpi->totalRatio();
        });

        return $this->responseJson(KpiResource::collection($sortedKpis));
    }

    public function multipliKpis(Request $request)
    {
        $kpis = $this->kpiRepo->search($request);
        return $this->responseJson(KpiResource::collection($kpis));
    }

    public function kpiPerformance(Request $request)
    {
        $kpis = $this->kpiRepo->search($request);
        $sortedKpis = $kpis->reject(function ($kpi) {
            return $kpi->totalRatio() === null;
        })->sortByDesc(function ($kpi) {
            return $kpi->totalRatio();
        });

        return $this->responseJson(new KpiResource($sortedKpis->first()));
    }

    public function userKpis()
    {
        $kpis = auth()->user()->kpis;
        return $this->responseJson( KpiResource::collection($kpis) );
    }
}
