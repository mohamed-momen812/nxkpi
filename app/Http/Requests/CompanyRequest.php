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
//            'user_id'       => 'exists:users.id',
            'support_email' => 'required|email',
            'country'       => 'string',
            'import_emails' => 'array',
            'export_emails' => 'array',
            'site_url'      => 'string'
        ];
    }

    public function onUpdate(){
        return [
            'name'          => 'string',
            'logo'          => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
//            'user_id'       => 'exists:users.id',
            'support_email' => 'email',
            'country'       => 'string',
            'import_emails' => 'array',
            'export_emails' => 'array',
            'site_url'      => 'string'
        ];
    }

    public function messages()
    {
        return [
            'name.string'   => 'name is required',
        ];
    }
}
