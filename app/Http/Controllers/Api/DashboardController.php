<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiTrait;
use App\Models\Dashboard;
use App\Http\Controllers\Controller;
use App\Http\Requests\DashboardRequest;
use App\Repositories\DashboardRepository;
use App\Http\Resources\DashboardResource;

class DashboardController extends Controller
{
    use ApiTrait;


    private $dashboardRepo;

    public function __construct( DashboardRepository $dashboardRepo)
    {
        $this->dashboardRepo = $dashboardRepo;
    }


    public function index()
    {
        $dashboards = Dashboard::where('user_id' , auth()->user()->id)->with('charts')->get();
        if ( !$dashboards->isEmpty())
        {
            return $this->responseJson(DashboardResource::collection($dashboards));
        }

        return $this->responseJsonFailed("there's no dashboards yet");
    }

    public function store(DashboardRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = auth()->user()->id ;

        $dashboard = $this->dashboardRepo->create( $data );

        if ($dashboard) {
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

        if ($dashboard) {
            return $this->responseJson(new DashboardResource($dashboard));
        }

        return $this->responseJsonFailed();
    }

    public function destroy($id)
    {
        $this->dashboardRepo->destroy($id);

        return $this->responseJson([
            'message' => 'dashboard deleted successfully',
        ]);
    }
}
