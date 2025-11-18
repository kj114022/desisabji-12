<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    public $table = 'registrations';

    public $fillable = [
        'name',        // Represents a username
        'email',
        'mobile',
        'password_hash', // Stores the hashed password
        'activation_hash',
        'mobile_verify',
        'email_verify',
        'email_hash',
        'reset_expires_at',
        'otp_expires_at',
        'mobile_otp',
        'ip_address',
        'status'

    ];

    // The attributes that should be casted to native types.
    protected $casts = [
        'name' => 'string',
        'email' => 'string',
        'mobile' => 'string',
        'password_hash' => 'string',
        'activation_hash' => 'string',
        'mobile_verify' => 'boolean',
        'email_verify' => 'boolean',
        'email_hash' => 'string',
        'reset_expires_at' => 'datetime',
        'otp_expires_at' => 'datetime',
        'mobile_otp' => 'string',
        'ip_address' => 'string',
        'status' => 'boolean'
    ];
    // Validation rules
    public static $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:registrations',
        'mobile' => 'required|string|max:15|unique:registrations',
        'password_hash' => 'required|string|min:6',
        'activation_hash' => 'nullable|string|max:255',
        'mobile_verify' => 'boolean',
        'email_verify' => 'boolean',
        'email_hash' => 'nullable|string|max:255',
        'reset_expires_at' => 'nullable|date',
        'otp_expires_at' => 'nullable|date',
        'mobile_otp' => 'nullable|string|max:6',
        'ip_address' => 'nullable|string|max:45',
        'status' => 'boolean'
    ];

}
