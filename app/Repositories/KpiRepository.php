<?php

namespace App\Repositories;

use App\Models\Kpi;
use App\Interfaces\KpiRepositoryInterface;
use Illuminate\Http\Request;

class KpiRepository extends BaseRepository implements KpiRepositoryInterface
{
    public function __construct(Kpi $model)
    {
        parent::__construct($model);
    }

    public function search(Request $request)
    {
        return $this->model->search( $request )->get();
    }

    public function enableOrDisable(Kpi $kpi)
    {
        if(request()->enable == 1){
            return$kpi->update([
                'enable' => 1
            ]);
        }elseif(request()->enable == 0){
            return $kpi->update([
                'enable' => 0
            ]);
        }else{
            return false;
        }
    }
}
