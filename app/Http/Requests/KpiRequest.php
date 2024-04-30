<?php

namespace App\Http\Requests;

use App\Models\Kpi;
use App\Rules\EquationKpisExist;
use App\Rules\EquationValidate;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KpiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create-kpis') || Gate::allows('edit-kpis') ;
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

    public function onCreate(){
        return [
            'name' => [ 'required','string' ] ,
            'description' => [ 'string' ] ,
            'user_target' => ['numeric','max:9999999.99','min:0.01' , 'nullable' ],
            'sort_order' => ['integer' , 'nullable'] ,
            'format' => Rule::in(['1,234', '1,234.56','12%','12.34%','$1,234.56','£1,234.56','€1,234.56','¥1,234.56','12 secs','12 mins','12 hrs','12 days','12 wks','12 mths','12 qtrs','12 yrs']),
            'direction' => 'in:up,down,none|required',
            'aggregated' => 'in:Sum,Average|required',
            'target_calculated' => 'boolean',
            'thresholds' => 'nullable|array',
            'equation' => ['string', new EquationKpisExist, new EquationValidate],
            'frequency_id' => 'required',
            'category_id' => ['exists:categories,id'],
            'format' => 'required',
            'icon'  => 'string',
//            'direction' => 'nullable',
            'target_calculated' => 'required',
            'enable' => 'boolean',
            'working_weeks' => 'array',
            'working_weeks.*' => 'in:"Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"'
        ];
    }

    public function onUpdate(){
        return [
            'name' => [ 'required','string' ] ,
            'description' => [ 'string' ] ,
            'user_target' => ['nullable','numeric','max:9999999.99','min:0.01'],
            'sort_order' => ['integer' , 'nullable'] ,
            'format' => Rule::in(['1,234', '1,234.56','12%','12.34%','$1,234.56','£1,234.56','€1,234.56','¥1,234.56','12 secs','12 mins','12 hrs','12 days','12 wks','12 mths','12 qtrs','12 yrs']),
            'direction' => 'in:up,down,none|nullable',
            'aggregated' => 'in:Sum Totals,Average',
            'target_calculated' => 'boolean',
            'thresholds' => 'nullable|array',
            'equation' => ['string' , new EquationKpisExist ],
            'frequency_id' => 'required',
            'category_id' => 'required',
            'format' => 'required',
            'icon'  => 'string',
            'aggregated' => 'required',
            'direction' => 'nullable',
            'target_calculated' => 'required',
            'enable' => 'boolean',
            'working_weeks' => 'array',
            'working_weeks.*' => 'in:"Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"'
        ];
    }

    public function messages(){
        return [
            'name.required' => 'name field is required',
            'name.string' => 'name must be string' ,
            'user_target.numeric' => 'user target must be numeric' ,
            'user_target.max' => 'user target must be between 9999999.99 and 0.01',
            'sort_order.integer' => 'sort order must be number' ,
        ];
    }
}
