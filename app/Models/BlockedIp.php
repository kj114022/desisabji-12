<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedIp extends Model
{
    public $table = 'blocked_ips';

    public $fillable = [
        'ip_address',
        'reason',
        'is_active',
        'blocked_by',
        'notes'
    ];

}
