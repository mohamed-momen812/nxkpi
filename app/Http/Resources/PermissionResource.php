<?php

namespace App\Http\Resources;

use App\Models\Module;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $modules = Module::all();

        return [
            'id'            => $this->id,
            'name'          => $this->name,
            // 'created_at'    => $this->created_at->format('d-m-y'),
            // 'updated_at'    => $this->updated_at->format('d-m-y'),
//            'roles'         => RoleResource::collection($this->roles),
        ];
    }
}
