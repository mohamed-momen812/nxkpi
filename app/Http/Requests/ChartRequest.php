<?php

namespace App\Http\Requests;

use App\Enums\ChartsEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ChartRequest extends FormRequest
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
            'type' => new Enum(ChartsEnum::class),
            'dashboard_id' => 'required|exists:dashboards,id',
        ];
    }

    public function onUpdate(){
        return [
            'type' => 'required|string',
            'dashboard_id' => 'required|exists:dashboards,id',
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'name is required',
            'dashboard_id.string'   => 'name is required',
        ];
    }
}
