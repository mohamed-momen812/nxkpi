<?php

namespace App\Repositories;

use App\Models\Kpi;
use App\Interfaces\KpiRepositoryInterface;

class KpiRepository extends BaseRepository implements KpiRepositoryInterface
{
    public function __construct(Kpi $model)
    {
        parent::__construct($model);
    }
}
