<?php

namespace App\Http\Controllers;

use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LangController extends Controller
{
    use ApiTrait;
    public function change($lang)
    {
        Session::put('lang' , $lang);
        return $this->responseJson();
    }
}
