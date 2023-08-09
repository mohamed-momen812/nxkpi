<?php

namespace App\Repositories;

use App\Models\Chart;

class ChartRepository extends BaseRepository
{
    public function __construct(Chart $model)
    {
        parent::__construct($model);
    }
}
