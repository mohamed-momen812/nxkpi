<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Frequency;
use App\Traits\ApiTrait;

class FrequencyController extends Controller
{
    use ApiTrait;

    public function index()
    {
        $frequencies = Frequency::all();

        return $this->responseJson($frequencies,'frequencies retrieved successfully');
    }
}
