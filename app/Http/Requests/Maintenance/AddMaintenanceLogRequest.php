<?php

namespace App\Http\Requests\Maintenance;

use Illuminate\Foundation\Http\FormRequest;

class AddMaintenanceLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'notes'          => 'required|string',
            'cost_actual'    => 'nullable|numeric|min:0',
            'parts_replaced' => 'nullable|string',
            'started_at'     => 'nullable|date',
            'completed_at'   => 'nullable|date|after_or_equal:started_at',
        ];
    }
}