<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Maintenance\AddMaintenanceLogRequest;
use App\Models\MaintenanceLog;
use App\Models\MaintenanceSchedule;

class MaintenanceLogController extends Controller
{
    public function index($id)
    {
        $schedule = MaintenanceSchedule::find($id);

        if (!$schedule) {
            return response()->notFound('Maintenance schedule not found');
        }

        $logs = MaintenanceLog::with('loggedBy')
            ->where('maintenance_schedule_id', $id)
            ->latest()
            ->get();

        return response()->success($logs);
    }

    public function store(AddMaintenanceLogRequest $request, $id)
    {
        $schedule = MaintenanceSchedule::find($id);

        if (!$schedule) {
            return response()->notFound('Maintenance schedule not found');
        }

        $log = MaintenanceLog::create([
            ...$request->validated(),
            'maintenance_schedule_id' => $id,
            'logged_by'               => auth()->id(),
        ]);

        return response()->created($log);
    }
}