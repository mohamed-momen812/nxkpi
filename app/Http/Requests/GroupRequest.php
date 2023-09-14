<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupRequest extends FormRequest
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
        return request()->isMethod('PUT') || request()->isMethod('PATCH') ? $this->onUpdate() : $this->onCreate() ;
    }

    public function onCreate()
    {
        return [
            'name'          => 'required|string|unique:groups,name',
            'sort_order'    => 'numeric',
        ];
    }

    public function onUpdate()
    {
        return [
            'name'          => 'required|string',
            'sort_order'    => 'numeric',
        ];
    }
}