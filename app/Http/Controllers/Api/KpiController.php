<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\KpiRequest;
use App\Http\Resources\KpiResource;
use App\Interfaces\KpiRepositoryInterface;
use Illuminate\Http\Request;

class KpiController extends Controller
{
    private $kpiRepo ;
    public function __construct(KpiRepositoryInterface $kpiRepoInterface)
    {
        $this->kpiRepo = $kpiRepoInterface ;
    }

    public function index()
    {
        $kpis = $this->kpiRepo->allWithPaginate();
        return KpiResource::collection($kpis);
    }

    public function create()
    {
        //
    }

    public function store(KpiRequest $request)
    {

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
        return KpiResource::make($kpi);
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
