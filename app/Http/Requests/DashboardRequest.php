<?php

namespace App\Http\Requests;

use App\Enums\ChartsEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class DashboardRequest extends FormRequest
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
        return request()->isMethod('PUT') || request()->isMethod('PATCH') ? $this->onUpdate() : $this->onCreate();
    }

    public function onCreate(){
        return [
            'name'      => 'required|string',
            'kpi_id'    => 'required|exists:kpis,id',
            'charts'    => [ 'array', Rule::in(ChartsEnum::class)],

        ];
    }

    public function onUpdate(){
        return [
            'name'      => 'required|string',
            'kpi_id'    => 'required|exists:kpis,id',
            'charts'    => [ 'array', Rule::in(ChartsEnum::class)],

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'name is required',
            'name.string'   => 'name is required',
        ];
    }
}
