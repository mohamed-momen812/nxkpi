<?php

namespace App\Http\Requests;

use App\Rules\UniqueEmailExceptCurrentUserRule;
use App\Rules\ValidImageOrPath;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'image' => [new ValidImageOrPath],
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => ['required', 'email', new UniqueEmailExceptCurrentUserRule],
            'preferred_language' => 'string'
        ];
    }
}
