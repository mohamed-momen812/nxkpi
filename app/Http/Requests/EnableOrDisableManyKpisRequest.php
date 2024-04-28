<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnableOrDisableManyKpisRequest extends FormRequest
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
            'kpi_ids' => 'required|array',
            'kpi_ids.*' => 'exists:kpis,id',
            'enable' => 'required|in:0,1'
        ];
    }

    
}
