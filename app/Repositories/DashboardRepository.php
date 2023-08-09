<?php

namespace App\Repositories;

use App\Models\Dashboard;
use Illuminate\Database\Eloquent\Model;

class DashboardRepository extends BaseRepository
{

    public function __construct(Dashboard $model)
    {
        parent::__construct($model);
    }


}
