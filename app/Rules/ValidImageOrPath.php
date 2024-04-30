<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ValidImageOrPath implements Rule
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
        // Check if the value is an image file
        if (is_uploaded_file($value)) {
            return Validator::make([$attribute => $value], [
                $attribute => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ])->passes();
        }

        // Check if the value is a valid image path
        
        // if (is_string($value) && File::exists($value)) {
        if (is_string($value)) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be either an image file or a valid image path.';
    }
}
