<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EntryRequest extends FormRequest
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
        return request()->isMethod('PUT') || request()->isMethod('PATCH') ? $this->onUpdate() : $this->onCreate();
    }

    public function onCreate(){
        return [
            'entries' => 'required|array',
            // 'entries.*.date' => 'date|before_or_equal:now',
            'entries.*.date' => 'date',
            'entries.*.notes' => 'string',
            'entries.*.actual' => 'numeric|max:9999999.99|min:00.01',
            'kpi_id' => 'required|numeric',
            // 'notes' => 'string'
        ];
    }

    public function onUpdate(){
        return [
            'entries' => 'required|array',
            "entries.*.id" => 'required|exists:entries,id',
            'entries.*.date' => 'date|before_or_equal:now',
            'entries.*.notes' => 'string',
            'entries.*.actual' => 'numeric|max:9999999.99|min:00.01',
            'entries.*.user_target' => 'numeric','max:9999999.99','min:0.01' , 'nullable',
            'kpi_id' => 'required|exists:kpis,id',
            // 'notes' => 'string'
        ];
    }

    public function messages()
    {
        return [
            'entries.required' => 'entries array is required',
            'entries.array' => 'entries must be an array' ,
            'entries.*.id.required' => 'id of entry is required',
            'entries.*.id.numeric' => 'id of entry must be number',
            'entries.*.date.date' => 'date of entries must be date format d-m-y',
            'entries.*.actual.numeric' => 'actual value must be numeric',
            'entries.*.actual.max' => 'actual value must be less than 9999999.99' ,
            'entries.*.actual.min' => 'actual value must be greater than 00.01',
            'kpi_id.required' => 'kpi id is required',
            'kpi_id' => 'kpi id must be integer',
            'notes.string' => 'notes must be string',
        ];
    }
}
