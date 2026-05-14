<?php

namespace App\Http\Requests\Maintenance;

use Illuminate\Foundation\Http\FormRequest;

class CreateMaintenanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'asset_id'       => 'required|integer',
            'type'           => 'required|in:preventive,corrective,routine',
            'priority'       => 'required|in:low,medium,high,critical',
            'scheduled_date' => 'required|date',
            'assigned_to'    => 'nullable|string|max:255',
            'estimated_cost' => 'nullable|numeric|min:0',
            'description'    => 'nullable|string',
        ];
    }
}