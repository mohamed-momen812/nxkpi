<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EntryRequest;
use App\Http\Resources\EntryResource;
use App\Interfaces\EntryRepositoryInterface;
use App\Interfaces\KpiRepositoryInterface;
use App\Traits\ApiTrait;
use App\Traits\Helper;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EntryController extends Controller
{
    use ApiTrait , Helper;

    private $entryRepo;
    private $kpiRepo;

    public function __construct(EntryRepositoryInterface $entryRepoInterface,KpiRepositoryInterface $kpiRepoInterface)
    {
        $this->entryRepo = $entryRepoInterface ;
        $this->kpiRepo = $kpiRepoInterface ;
    }
    public function index()
    {
        $entries = $this->entryRepo->allWithPaginate();
        return $this->responseJson( EntryResource::collection($entries) , 'entries retrieved successfully');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EntryRequest $request)
    {
        $input = $request->validated();
        $input['user_id'] = auth()->id();
        $kpi = $this->kpiRepo->find($request->kpi_id);

        if($kpi == null) return "kpi not found";
        
        $input['target'] = $kpi->user_target ;
        $entries = $request->entries ;
        
        foreach($entries as $entry)
        {
            foreach($entry as $data)
            {
                $date = new Carbon($entry['date']);
                $input['entry_date'] = $date;
                $input['actual'] = $entry['actual'];
                $input['day'] = $date->day ;
                $input['week'] = $date->weekOfYear ;
                $input['month'] = $date->month;
                $input['quarter'] = $this->calcYearlyQuarter($date) ;
                $input['year'] = $date->year;
    
                $entry = $this->entryRepo->create($input);
    
                if($entry) continue;
                
                return $this->responseJsonFailed();
            }
        }
        
        $entriesForOneKpi = $this->entryRepo->getEntriesByKpi($request->kpi_id);
        return $this->responseJson($entriesForOneKpi);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $entry = $this->entryRepo->find($id);
        return EntryResource::make($entry);
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
