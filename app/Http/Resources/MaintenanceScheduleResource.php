<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaintenanceScheduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'asset_id'       => $this->asset_id,
            'company_id'     => $this->company_id,
            'scheduled_by'   => [
                'id'   => $this->scheduledBy?->id,
                'name' => $this->scheduledBy?->name,
            ],
            'type'           => $this->type,
            'priority'       => $this->priority,
            'scheduled_date' => $this->scheduled_date,
            'assigned_to'    => $this->assigned_to,
            'estimated_cost' => $this->estimated_cost,
            'description'    => $this->description,
            'status'         => $this->status,
            'logs'           => MaintenanceLogResource::collection($this->whenLoaded('logs')),
            'alerts'         => MaintenanceAlertResource::collection($this->whenLoaded('alerts')),
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
        ];
    }
}