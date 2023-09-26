<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'role' => $this->role,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'phone_verified' => $this->phone_verified,
            'uploaded_doc' => count($this->documents) > 0,
            'verified' => $this->verified,
        ];
    }
}
