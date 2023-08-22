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
        $user = auth()->user();
        $kpis = $user->kpis ;
//        $kpis = $this->kpiRepo->allWithPaginate();

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
        if (!$request->category_id) {
            $categories = auth()->user()->categories() ;
            $defaultCat = $categories->firstWhere('name', 'Default');
            $input['category_id'] = ($defaultCat) ? $defaultCat->id : null ;
        }
        $kpi = $this->kpiRepo->create($input);

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
        $kpi = $this->kpiRepo->find($id);
        $user = auth()->user();
        $kpis = $user->kpis ;

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
        $user = auth()->user();
        $kpis = $user->kpis ;

        if (!$kpis->contains($kpi)){
            return $this->responseJsonFailed('Kpi Not Found');
        }
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
        $kpi = $this->kpiRepo->find($id);
        $user = auth()->user();
        $kpis = $user->kpis ;

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

}
