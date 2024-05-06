<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaveRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            "user_id"       => $this->user_id,
            "leave_type"    => $this->leave_type,
            "start_date"    => $this->start_date,
            "end_date"      => $this->end_date,
            "reason"        => $this->reason,
            "status"        => $this->status,
            "admin_comment" => $this->admin_comment,
            "name"          => $this->user->name,
            "email"         => $this->user->email,
            "profile_image" => $this->user->profile_image,
            "created_at"    => $this->created_at,
            "updated_at"    => $this->updated_at,
        ];
    }
}
