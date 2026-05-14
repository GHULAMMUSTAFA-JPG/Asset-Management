<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceAlert;

class MaintenanceAlertController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $alerts = MaintenanceAlert::with('schedule')
            ->where('company_id', $user->company_id)
            ->latest()
            ->get();

        return response()->success($alerts);
    }

    public function markRead($id)
    {
        $alert = MaintenanceAlert::find($id);

        if (!$alert) {
            return response()->notFound('Alert not found');
        }

        $alert->update(['is_read' => true]);

        return response()->success($alert);
    }
}