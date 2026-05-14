<?php

namespace App\Http\Requests\Maintenance;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMaintenanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'asset_id'       => 'sometimes|integer',
            'type'           => 'sometimes|in:preventive,corrective,routine',
            'priority'       => 'sometimes|in:low,medium,high,critical',
            'scheduled_date' => 'sometimes|date',
            'assigned_to'    => 'nullable|string|max:255',
            'estimated_cost' => 'nullable|numeric|min:0',
            'description'    => 'nullable|string',
        ];
    }
}