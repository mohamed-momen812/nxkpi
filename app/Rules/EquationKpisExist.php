<?php

namespace App\Rules;

use App\Models\Kpi;
use Illuminate\Contracts\Validation\Rule;

class EquationKpisExist implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    // this is right by laravel documentation version 9
//    public function __invoke($attribute, $value, $fail)
//    {
//        preg_match_all('#\#(.*?)\##', $this->equation, $match);
//        $kpis_id = $match[1];
//        $kpis = Kpi::whereIn('id', $kpis_id)->get();
//
//        if ( count($kpis) != count($kpis_id) ) {
//            // Some KPI IDs do not exist in the `kpis` table
//            $fail("some or all equation's kpis ids dosn't exists");
//        }
//    }
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    // this is right by laravel documentation version 10
    public function passes($attribute, $value)
    {
        preg_match_all('#\#(.*?)\##', $value, $match);
        $kpis_id = $match[1];
        $kpis = Kpi::whereIn('id', $kpis_id)->get();

        return (! count($kpis) != count($kpis_id) ) ;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ("some or all equation's kpis ids dosn't exists");
    }
}
