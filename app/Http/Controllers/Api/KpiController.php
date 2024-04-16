<?php

namespace App\Http\Controllers\Api;

use App\Models\Kpi;
use App\Traits\ApiTrait;
use App\Models\Equation;
use App\Enums\FormatEnum;
use App\Http\Requests\KpiRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\KpiResource;
use App\Http\Controllers\Controller;
use App\Interfaces\KpiRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class KpiController extends Controller
{
    use ApiTrait;

    private $kpiRepo;

    public function __construct(KpiRepositoryInterface $kpiRepoInterface)
    {
        $this->kpiRepo = $kpiRepoInterface ;
    }

    public function index()
    {
        $kpis = auth()->user()->kpis ;
        
        if (request()->has('name')) {
            $name = strtolower(request()->input('name'));
        
            $kpis = $kpis->filter(function ($kpi) use ($name) {
                
                return strpos(strtolower($kpi->name), $name) !== false;
            });
        }

        if ( !$kpis->isEmpty() ){
            return $this->responseJson(KpiResource::collection($kpis),'kpis retrieved successfully');
        }

        return $this->responseJsonFailed("there's no kpis yet");
    }

    public function exportExcelExample()
    {
        $publicUrl = url('storage/kpis/KPIs-Import-Example.xlsx');
        return $this->responseJson($publicUrl);
    }

    public function create()
    {
        //
    }

    public function store(KpiRequest $request)
    {
        $input = $request->validated();

        $input['category_id'] = (isset($input['category_id'])) ? $input['category_id'] : 1 ;

        $kpi = $this->kpiRepo->create($input);
        auth()->user()->kpis()->attach($kpi->id);
        if ($kpi){
            $equation = ($request->equation) ? $this->setEquation($kpi) : null ;
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
        $this->authorize('view-kpis');
        $kpi = $this->kpiRepo->find($id);
        $kpis = auth()->user()->kpis ;

        if ($kpis->contains($kpi)){
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
        $kpi = $this->kpiRepo->find($id);
        $kpis = auth()->user()->kpis ;

        if (!$kpis->contains($kpi)){
            return $this->responseJsonFailed('Kpi Not Found');
        }
        $input = $request->validated();

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

    public function enableOrDisable(Kpi $kpi)
    {
        $this->kpiRepo->enableOrDisable($kpi);
        return $this->responseJson(KpiResource::make($kpi));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $kpi = $this->kpiRepo->find($id);
        $kpis = auth()->user()->kpis ;

        if (!$kpis->contains($kpi)){
            return $this->responseJsonFailed('Kpi Not Found');
        }

        if($kpi == null) return $this->responseJsonFailed($message='kpi not found');

        $kpi = $this->kpiRepo->destroy($id);

        if ($kpi){
            return $this->responseJson($message="kpi deleted successfully");
        }
        return $this->responseJsonFailed();
    }

    public function setEquation(Kpi $kpi)
    {
        $equation = Equation::create([
            'equat_body' => $kpi->equation,
            'kpi_id' => $kpi->id,
        ]);
        preg_match_all('#\#(.*?)\##', $kpi->equation, $match);
        $kpis_id = $match[1];

        foreach($kpis_id as $kpi){
            $equations_kpi = DB::table('equations_kpis')->insert([
                'equation_id' => $equation->id ,
                'kpi_id' => $kpi ,
            ]);
        }
    }


    public function totalRatio(Kpi $kpi)
    {
        if($kpi->aggregated == "Average"){
            return $this->responseJson($kpi->totalRatio() );
        }else{
            return $this->responseJson($kpi->actualTotal() );
        }
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

}
