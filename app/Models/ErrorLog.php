<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    protected $fillable = [
        'message',
        'type',
        'severity',
        'file',
        'line',
        'stack_trace',
        'url',
        'method',
        'request_body',
        'ip_address',
        'user_id',
        'status_code',
    ];
}