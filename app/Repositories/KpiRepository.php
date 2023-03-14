<?php

namespace App\Repositories;

use App\Interfaces\KpiRepositoryInterface;
use App\Models\Kpi;

class KpiRepository extends BaseRepository implements KpiRepositoryInterface
{
    public function __construct(Kpi $model)
    {
        parent::__construct($model);
    }
}
