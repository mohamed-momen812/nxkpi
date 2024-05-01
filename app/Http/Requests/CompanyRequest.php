<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
            'name'          => 'string',
            'logo'          => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'support_email' => 'required|email',
            'country'       => 'string',
            'import_emails' => 'array',
            'import_emails.*' => 'email',
            'invoices_email' => 'array',
            'invoices_email.*' => 'email',
            'invoice_address' => 'string',
            'site_url'      => 'string',
            'default_frequency_id' => 'exists:frequencies,id',
            'start_finantial_year' => 'in:jan,feb,mar,apr,may,jun,jul,aug,sep,oct,nov,dec',
            'start_of_week' => 'in:sun,mon,tue,wed,thu,fri,sat',
        ];
    }

    public function onUpdate(){
        return [
            'name'          => 'string',
            'logo'          => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'support_email' => 'required|email',
            'country'       => 'string',
            'import_emails' => 'array',
            'import_emails.*' => 'email',
            'invoices_email' => 'array',
            'invoices_email.*' => 'email',
            'invoice_address' => 'string',
            'site_url'      => 'string',
            'default_frequency_id' => 'exists:frequencies,id',
            'start_finantial_year' => 'in:jan,feb,mar,apr,may,jun,jul,aug,sep,oct,nov,dec',
            'start_of_week' => 'in:sun,mon,tue,wed,thu,fri,sat',
        ];
    }

    public function messages()
    {
        return [
            
        ];
    }
}
