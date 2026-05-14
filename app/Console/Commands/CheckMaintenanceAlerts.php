<?php

namespace App\Console\Commands;

use App\Services\MaintenanceAlertService;
use Illuminate\Console\Command;

class CheckMaintenanceAlerts extends Command
{
    protected $signature   = 'maintenance:check-alerts';
    protected $description = 'Check maintenance schedules and generate due/overdue alerts';

    public function handle(MaintenanceAlertService $service): void
    {
        $service->checkAndGenerateAlerts();
        $this->info('Maintenance alerts checked successfully.');
    }
}