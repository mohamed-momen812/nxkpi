<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class EquationValidate implements Rule
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

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Extract KPI IDs from the equation
        preg_match_all('/#(\d+)#/', $value, $matches);
        $kpisIds = $matches[1];

        // Check if all KPI IDs exist in the database (removed this part from syntax check)
        // No need to check KPI existence here since it's checked separately

        // Check if the equation syntax is valid
        try {
            // Replace KPI placeholders with a dummy value to evaluate the syntax
            $dummyValue = 1;
            $equation = preg_replace('/#(\d+)#/', $dummyValue, $value);

            // Try evaluating the equation
            eval('$result = ' . $equation . ';');
      
        } catch (\Throwable $e) {
       
            // If an error occurs during evaluation, return false
            return false;
        }

        // If no error occurred during evaluation, return true
        return true;
    }

    public function message()
    {
        return 'The equation contains invalid syntax.';
    }
    
}
