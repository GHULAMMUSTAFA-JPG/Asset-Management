<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaintenanceLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                      => $this->id,
            'maintenance_schedule_id' => $this->maintenance_schedule_id,
            'logged_by'               => [
                'id'   => $this->loggedBy?->id,
                'name' => $this->loggedBy?->name,
            ],
            'notes'                   => $this->notes,
            'cost_actual'             => $this->cost_actual,
            'parts_replaced'          => $this->parts_replaced,
            'started_at'              => $this->started_at,
            'completed_at'            => $this->completed_at,
            'created_at'              => $this->created_at,
            'updated_at'              => $this->updated_at,
        ];
    }
}