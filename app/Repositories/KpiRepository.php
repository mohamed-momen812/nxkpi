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
}
