<?php

namespace App\Http\Resources;

use App\Models\Frequency;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
//        return parent::toArray($request);
        return [
            "id"        => $this->id,
            "name"      => $this->name,
            "logo"      => Storage::url($this->logo),
            "support_email" => $this->support_email,
            "country"   => $this->country,
            "import_emails" => $this->import_emails,
            "export_emails" => $this->export_emails,
            "site_url"  => $this->site_url,
            "invoices_email" => $this->invoices_email,
            "invoice_address" => $this->invoice_address,
            "default_frequency_id" => $this->default_frequency_id ? new FrequencyResource(Frequency::find((int)$this->default_frequency_id)) : null,
            "start_finantial_year" => $this->start_finantial_year,
            "start_of_week" => $this->start_of_week
        ];
    }
}
/*

        'invoices_email',
        'invoice_address',
        'default_frequency_id',
        'start_finantial_year',
        'start_of_week'
        */