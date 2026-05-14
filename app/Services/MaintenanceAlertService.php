<?php

namespace App\Services;

use App\Models\MaintenanceAlert;
use App\Models\MaintenanceSchedule;
use Carbon\Carbon;

class MaintenanceAlertService
{
    public function checkAndGenerateAlerts(): void
    {
        $today    = Carbon::today();
        $upcoming = Carbon::today()->addDays(3);

        // Get all pending or in_progress schedules
        $schedules = MaintenanceSchedule::whereIn('status', ['pending', 'in_progress'])->get();

        foreach ($schedules as $schedule) {
            $scheduledDate = Carbon::parse($schedule->scheduled_date);

            // Overdue — scheduled date is in the past
            if ($scheduledDate->lt($today)) {
                $this->createAlertIfNotExists($schedule, 'overdue');
            }

            // Upcoming — scheduled date is within next 3 days
            elseif ($scheduledDate->between($today, $upcoming)) {
                $this->createAlertIfNotExists($schedule, 'upcoming');
            }
        }
    }

    private function createAlertIfNotExists(MaintenanceSchedule $schedule, string $type): void
    {
        $exists = MaintenanceAlert::where('maintenance_schedule_id', $schedule->id)
            ->where('alert_type', $type)
            ->exists();

        if (!$exists) {
            MaintenanceAlert::create([
                'maintenance_schedule_id' => $schedule->id,
                'company_id'              => $schedule->company_id,
                'alert_type'              => $type,
                'is_read'                 => false,
            ]);
        }
    }
}