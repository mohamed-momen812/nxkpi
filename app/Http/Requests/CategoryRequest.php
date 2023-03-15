<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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

    public function rules()
    {
        return request()->isMethod('PUT') || request()->isMethod('PATCH') ? $this->onUpdate() : $this->onCreate() ;
    }

    public function onCreate()
    {
        return [
            'name' => ['required' ,'string'],
            'sort_order' => ['integer'],
        ];
    }

    public function onUpdate()
    {
        return[
            'name' => ['required' ,'string'],
            'sort_order' => ['integer'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'name field is required',
            'name.string' => 'name must be string',
            'sort_order.integer' => 'sort order must be integer',
        ];
    }
}
