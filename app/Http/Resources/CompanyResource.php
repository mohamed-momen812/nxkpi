<?php

namespace App\Http\Resources;

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
        ];
    }
}
