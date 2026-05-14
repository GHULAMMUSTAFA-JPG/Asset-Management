<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaintenanceAlertResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                      => $this->id,
            'maintenance_schedule_id' => $this->maintenance_schedule_id,
            'company_id'              => $this->company_id,
            'alert_type'              => $this->alert_type,
            'is_read'                 => $this->is_read,
            'schedule'                => new MaintenanceScheduleResource($this->whenLoaded('schedule')),
            'created_at'              => $this->created_at,
            'updated_at'              => $this->updated_at,
        ];
    }
}