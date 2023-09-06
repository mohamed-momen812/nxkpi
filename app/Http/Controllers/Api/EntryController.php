<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EntryRequest;
use App\Http\Resources\EntryResource;
use App\Interfaces\EntryRepositoryInterface;
use App\Interfaces\KpiRepositoryInterface;
use App\Models\Dashboard;
use App\Repositories\DashboardRepository;
use App\Traits\ApiTrait;
use App\Traits\Helper;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EntryController extends Controller
{
    use ApiTrait , Helper;

    private $entryRepo;
    private $kpiRepo;

    private $dashboardRepo;

    public function __construct(EntryRepositoryInterface $entryRepoInterface,KpiRepositoryInterface $kpiRepoInterface , DashboardRepository $dashboardRepo)
    {
        $this->entryRepo = $entryRepoInterface ;
        $this->kpiRepo = $kpiRepoInterface ;
        $this->dashboardRepo = $dashboardRepo;
    }
    public function index()
    {
        $entries = $this->entryRepo->allWithPaginate();

        if ( !$entries->isEmpty() )
        {
            return $this->responseJson( EntryResource::collection($entries) , 'entries retrieved successfully');
        }

        return $this->responseJsonFailed("there's no entries yet");
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
        //to get kpi_id and notes directly
        $input = $request->validated();
        $input['user_id'] = auth()->id();
        $kpi = auth()->user()->kpis()->where('id' , $request->kpi_id )->first();

        if($kpi == null) return $this->responseJsonFailed("kpi not found");

        if($kpi->dashboards->isEmpty())
        {
            $dashboard = $this->dashboardRepo->create([
                "name"      => $kpi->name,
                "charts"    => [1,2,3],
                "user_id"   => auth()->id(),
                "kpi_id"    => $kpi->id,
            ]);
        }

        $input['target'] = $kpi->user_target ;
        $entries = $request->entries ;

        foreach($entries as $entry)
        {
            $preparedData = $this->prepareData($entry);
            $input = array_merge($input , $preparedData );

            $entry = $this->entryRepo->create($input);

            if($entry) continue;

            return $this->responseJsonFailed();
        }

        $entriesForOneKpi = $this->entryRepo->getEntriesByKpi($request->kpi_id);
        return $this->responseJson($entriesForOneKpi , 'Entries created successfully');
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

        if ($entry == null) return $this->responseJsonFailed("Entry Not Found" );

        $entry = $this->entryRepo->find($id);
        return $this->responseJson( EntryResource::make($entry) );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EntryRequest $request)
    {
        //to get kpi_id and notes directly
        $input = $request->except('entries');

        $kpi = auth()->user()->kpis()->where('id' , $request->kpi_id )->first();

        if($kpi == null) return $this->responseJsonFailed("kpi not found");

        $input['target'] = $kpi->user_target ;
        $entries = $request->entries ;

        foreach($entries as $entry)
        {
            $preparedData = $this->prepareData($entry);
            $input = array_merge($input , $preparedData );
            $entry = $this->entryRepo->update($input , $entry['id']);

            if($entry) continue;

            return $this->responseJsonFailed("entry not found");

        }

        $entriesForOneKpi = $this->entryRepo->getEntriesByKpi($request->kpi_id);
        return $this->responseJson($entriesForOneKpi , 'Entries updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $entry = $this->entryRepo->find($id);

        if ($entry == null) return $this->responseJsonFailed("Entry Not Found" );

        $entry = $this->entryRepo->destroy($id);

        if ($entry){
            return $this->responseJson('Entry deleted successfully');
        }
        return $this->responseJsonFailed();
    }

    public function prepareData($entry)
    {
        $date = new Carbon($entry['date']);
        $input['entry_date'] = $date;
        $input['actual'] = $entry['actual'];
        $input['day'] = $date->day ;
        $input['weekNo'] = $date->weekOfYear ;
        $input['month'] = $date->month;
        $input['quarter'] = $this->calcYearlyQuarter($date) ;
        $input['year'] = $date->year;

        return $input ;
    }

    public function getEntriesByKpi($kpi_id)
    {
        $kpi = $this->kpiRepo->find($kpi_id);

        if($kpi == null) return "kpi not found";

        $entries = $this->entryRepo->getEntriesByKpi($kpi_id) ;

        if($entries == null || empty($entries) == true) return "kpi hasn't entries";

        return $this->entryRepo->getEntriesByKpi($kpi_id);
    }
}
