<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SignUpAudit extends Model
{
    public $table = 'sign_up_audits';

    public $fillable = [
        'user_id',
        'ip_address',
        'device_info',
        'created_at',
        'updated_at',
    ];

// The attributes that should be casted to native types.
    protected $casts = [
        'user_id' => 'string',
        'ip_address' => 'string',
        'device_info' => 'string',
    ];

    // Validation rules
    public static $rules = [
        'user_id' => 'nullable|string|max:255',
        'ip_address' => 'nullable|string|max:45',
        'device_info' => 'nullable|string|max:255',
    ];
}
