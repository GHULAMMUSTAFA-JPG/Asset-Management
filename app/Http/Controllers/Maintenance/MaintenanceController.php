<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Maintenance\CreateMaintenanceRequest;
use App\Http\Requests\Maintenance\UpdateMaintenanceRequest;
use App\Http\Requests\Maintenance\UpdateStatusRequest;
use App\Models\MaintenanceSchedule;

class MaintenanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $query = MaintenanceSchedule::with(['scheduledBy', 'logs', 'alerts']);

        if ($user->role === 'super_admin') {
            // sees all
        } elseif ($user->role === 'company_admin' || $user->role === 'manager') {
            $query->where('company_id', $user->company_id);
        } else {
            // staff sees only assigned to them
            $query->where('company_id', $user->company_id)
                  ->where('assigned_to', $user->name);
        }

        return response()->success($query->latest()->get());
    }

    public function store(CreateMaintenanceRequest $request)
    {
        $user = auth()->user();

        $schedule = MaintenanceSchedule::create([
            ...$request->validated(),
            'company_id'   => $user->company_id,
            'scheduled_by' => $user->id,
            'status'       => 'pending',
        ]);

        return response()->created($schedule);
    }

    public function show($id)
    {
        $schedule = MaintenanceSchedule::with(['scheduledBy', 'logs', 'alerts'])
            ->find($id);

        if (!$schedule) {
            return response()->notFound('Maintenance schedule not found');
        }

        return response()->success($schedule);
    }

    public function update(UpdateMaintenanceRequest $request, $id)
    {
        $schedule = MaintenanceSchedule::find($id);

        if (!$schedule) {
            return response()->notFound('Maintenance schedule not found');
        }

        $schedule->update($request->validated());

        return response()->success($schedule);
    }

    public function destroy($id)
    {
        $schedule = MaintenanceSchedule::find($id);

        if (!$schedule) {
            return response()->notFound('Maintenance schedule not found');
        }

        $schedule->delete();

        return response()->noContent();
    }

    public function updateStatus(UpdateStatusRequest $request, $id)
    {
        $schedule = MaintenanceSchedule::find($id);

        if (!$schedule) {
            return response()->notFound('Maintenance schedule not found');
        }

        $schedule->update(['status' => $request->status]);

        return response()->success($schedule);
    }
}