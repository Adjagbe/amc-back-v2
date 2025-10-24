<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivityLogs extends Model
{
    use HasFactory;
    protected $fillable = [
        'pseudo',
        'controller',
        'action',
        'evenement',
        'date_activity',
        'ip_address',
        'hostname',
    ];
}
