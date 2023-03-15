<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\KpiRequest;
use App\Http\Resources\KpiResource;
use App\Interfaces\KpiRepositoryInterface;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class KpiController extends Controller
{
    use ApiTrait;
    private $kpiRepo ;
    public function __construct(KpiRepositoryInterface $kpiRepoInterface)
    {
        $this->kpiRepo = $kpiRepoInterface ;
    }

    public function index()
    {
        $kpis = $this->kpiRepo->allWithPaginate();

        if ($kpis){
            return $this->responseJson(KpiResource::collection($kpis),'kpis retrieved successfully');
        }
        return $this->responseJsonFailed();
    }

    public function create()
    {
        //
    }

    public function store(KpiRequest $request)
    {
        $input = $request->validated();
        $input['user_id'] = auth()->id() ;
        $input['frequency_id'] = $request->input('frequency_id' , 1 );
        $input['category_id'] = $request->input('category_id' , null );
        $input['format'] = $request->input('format' , '1,234' ) ;
        $input['aggregated'] = $request->input('aggregated' ,'Sum Totals');
        $input['direction'] = request('direction' , 'none');
        $input['target_calculated'] = request('target_calculated' , false);
        $kpi = $this->kpiRepo->create($input);

        if ($kpi){
            return $this->responseJson(KpiResource::make($kpi) , 'Kpi created successfully');
        }
        return $this->responseJsonFailed();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $kpi = $this->kpiRepo->find($id);

        if ($kpi){
            return $this->responseJson(KpiResource::make($kpi));
        }
        return $this->responseJsonFailed('Kpi Not Found');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(KpiRequest $request, $id)
    {
        $input = $request->validated();
        $input['user_id'] = auth()->id() ;
        $input['frequency_id'] = $request->input('frequency_id' , 1 );
        $input['category_id'] = $request->input('category_id' , null );
        $input['format'] = $request->input('format' , '1,234' ) ;
        $input['aggregated'] = $request->input('aggregated' ,'Sum Totals');
        $input['direction'] = request('direction' , 'none');
        $input['target_calculated'] = request('target_calculated' , false);
        $kpi = $this->kpiRepo->update($input,$id);

        if ($kpi){
            return $this->responseJson(KpiResource::make($kpi) , 'Kpi updated successfully');
        }
        return $this->responseJsonFailed();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $kpi = $this->kpiRepo->destroy($id);

        if ($kpi){
            return $this->responseJson('kpi deleted successfully');
        }
        return $this->responseJsonFailed();
    }
}
