<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CategoryResource;
use App\Imports\KpisImport;
use App\Models\Kpi;
use App\Traits\ApiTrait;
use App\Models\Equation;
use App\Enums\FormatEnum;
use App\Http\Requests\KpiRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\KpiResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\EnableOrDisableManyKpisRequest;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\KpiRepositoryInterface;
use App\Models\Category;
use App\Models\Frequency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class KpiController extends Controller
{
    use ApiTrait;

    private $kpiRepo;
    private $categoryRepo;

    public function __construct(KpiRepositoryInterface $kpiRepoInterface, CategoryRepositoryInterface $categoryRepositoryInterface)
    {
        $this->kpiRepo = $kpiRepoInterface ;
        $this->categoryRepo = $categoryRepositoryInterface;
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
            return $this->responseJson($this->dataPaginate(KpiResource::collection($kpis)),'kpis retrieved successfully');
        }

        return $this->responseJson("there's no kpis yet");
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
        $kpi->users()->attach(auth()->user()->id);
        if ($kpi){
            $equation = ($request->equation) ? $this->setEquation($kpi) : null ;
            $user_id = tenant()->user->id;
            $categories = $this->categoryRepo->getCategoriesByUserId($user_id);
            return $this->responseJson($this->dataPaginate(CategoryResource::collection($categories)));
            // return $this->responseJson(KpiResource::make($kpi) , 'Kpi created successfully');
        }
        return $this->responseJsonFailed();
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');
        $rows = Excel::toArray(new KpisImport, $file);

        $kpis = [];
        for($i=1; $i < count($rows); $i++){
            $kpiData = $this->prepareImporedKpi($rows[0][$i]);
           
            $kpi = $this->kpiRepo->create($kpiData);
            auth()->user()->kpis()->attach($kpi->id);
            $kpis[] = $kpi;
        }

        return $this->unifiedResponse();
        // return $this->responseJson(KpiResource::collection($kpis));
    }

    private function prepareImporedKpi($row)
    {
        $kpiData = [];
        $kpiData['name'] = $row[0];
        $kpiData['description'] = $row[1];
        $kpiData['category_id'] = Category::where('name', $row[2])->first()->id;
        $kpiData['format'] = $row[3];
        $kpiData['frequency_id'] = Frequency::where('name' , $row[4])->first()->id;
        $kpiData['direction'] = $row[5];
        $kpiData['user_target'] = $row[6];

        return $kpiData;
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
            $user_id = tenant()->user->id;
            $categories = $this->categoryRepo->getCategoriesByUserId($user_id);
            return $this->responseJson($this->dataPaginate(CategoryResource::collection($categories)));
            // return $this->responseJson(KpiResource::make($kpi) , 'Kpi updated successfully');
        }
        return $this->responseJsonFailed();
    }

    public function enableOrDisable(Kpi $kpi)
    {
        $this->kpiRepo->enableOrDisable($kpi, request()->enable);

        return $this->unifiedResponse();
        // return $this->responseJson(KpiResource::make($kpi));
    }

    public function enableOrDisableMany(EnableOrDisableManyKpisRequest $request)
    {
        foreach($request->kpi_ids as $id){
            $kpi = $this->kpiRepo->find($id);

            $this->kpiRepo->enableOrDisable($kpi, $request->enable);
        }
        $kpis = auth()->user()->kpis ;
        return $this->unifiedResponse();
        // return $this->responseJson(KpiResource::collection($kpis));
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
            return $this->unifiedResponse();
            // return $this->responseJson($message="kpi deleted successfully");
        }
        return $this->responseJsonFailed();
    }

    public function deleteMany(Request $request)
    {
        $request->validate([
            'kpi_ids' => 'required|array',
            'kpi_ids.*' => 'exists:kpis,id',
        ]);

        $kpi_ids = $request->input('kpi_ids');
        $kpis = auth()->user()->kpis ;
        foreach($kpi_ids as $kpi_id){
            if (!$kpis->contains($kpi_id)){
                return $this->responseJsonFailed('Kpi ID ' . $kpi_id . ' Not Found');
            }

            $kpi = $this->kpiRepo->destroy($kpi_id);
        }
        return $this->unifiedResponse();
        // return $this->responseJson("kpis deleted successfully");
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

    public function unifiedResponse()
    {
        $user_id = tenant()->user->id;
        $categories = $this->categoryRepo->getCategoriesByUserId($user_id);
        return $this->responseJson($this->dataPaginate(CategoryResource::collection($categories)));
    }

}
