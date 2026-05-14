<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceSchedule extends Model
{
    protected $fillable = [
        'asset_id',
        'company_id',
        'scheduled_by',
        'type',
        'priority',
        'scheduled_date',
        'assigned_to',
        'estimated_cost',
        'description',
        'status',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function scheduledBy()
    {
        return $this->belongsTo(User::class, 'scheduled_by');
    }

    public function logs()
    {
        return $this->hasMany(MaintenanceLog::class);
    }

    public function alerts()
    {
        return $this->hasMany(MaintenanceAlert::class);
    }
}