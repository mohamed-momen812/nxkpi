<?php

namespace App\Http\Controllers\Api;

use App\Exports\EntriesExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\EntryRequest;
use App\Http\Resources\EntryResource;
use App\Http\Resources\KpiResource;
use App\Imports\EntriesImport;
use App\Interfaces\EntryRepositoryInterface;
use App\Interfaces\KpiRepositoryInterface;
use App\Models\Dashboard;
use App\Models\User;
use App\Repositories\DashboardRepository;
use App\Traits\ApiTrait;
use App\Traits\Helper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

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
        $kpis = auth()->user()->kpis ;

        if (request()->has('frequencyId') ) {
            $kpis = $kpis->filter(function ($kpi) {
                return $kpi->frequency->id == request()->frequencyId;
            });
        }

        if ( !$kpis->isEmpty() ){
            return $this->responseJson(KpiResource::collection($kpis),'kpis retrieved successfully');
        }

        return $this->responseJsonFailed("there's no kpis yet");
    }

    public function exportExcel()
    {
        $entries = $this->getEntries();
        
        $directory = 'app/'.auth()->id().'/entries/';
        Storage::makeDirectory($directory);
        $fileName = 'entries.xlsx';
        $storeFile = Excel::store(new EntriesExport($entries), $directory.$fileName, 'local');
        $url = url(Storage::url($directory.$fileName));

        return $this->responseJson($url);
    }

    private function getEntries()
    {
        $kpis = auth()->user()->kpis;
        $entries = [] ;
        $kpis->each(function ($kpi) use (&$entries){
            $entries[] = $kpi->entries;
        });
        $mergedCollection = Collection::empty();
        foreach ($entries as $collection) {
            $mergedCollection = $mergedCollection->concat($collection);
        }
        return $mergedCollection;
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
        $kpi = auth()->user()->kpis->where('id' , $request->kpi_id )->first();

        if($kpi == null) return $this->responseJsonFailed("kpi not found");

        if($kpi->dashboards->isEmpty())
        {
            $dashboard = $this->dashboardRepo->create([
                "name"      => $kpi->name,
                "user_id"   => auth()->id(),
                "kpi_id"    => $kpi->id,
            ]);
            $dashboard->charts()->attach(1);
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

        $kpi = auth()->user()->kpis ->where('id' , $request->kpi_id )->first();

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

    public function exportExcelExample()
    {
        $publicUrl = url('storage/entries/Entries-Import-Data-Example.xlsx');
        return $this->responseJson($publicUrl);
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');
        $rows = Excel::toArray(new EntriesImport, $file);

        $entries = [];
        $rows = $rows[0];
        for($i=1; $i < count($rows); $i++){
            $entryData = $this->prepareImporedEntry($rows[$i]);
            $entries[] = $this->entryRepo->create($entryData);
        }

        return $this->responseJson(EntryResource::collection($entries) , 'Entries imported successfully');
    }

    private function prepareImporedEntry($row)
    {
        $entryData = [];
        $entryData['user_id'] = User::where('email' , $row[0])->first()->id;
        $entryData['kpi_id'] = $row[1];
        $entryData['target'] = $row[5];
        $entryData['notes'] = $row[6];
        $toPrepareDateData['date'] = $row[3];
        $toPrepareDateData['actual'] = $row[4];
        $dateData = $this->prepareData($toPrepareDateData);

        $entryData = array_merge($entryData , $dateData );

        return $entryData ;
    }
}
