<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteRequest extends Model
{
    protected $fillable = [
        'type',
        'name',
        'phone',
        'email',
        'service',
        'message',
        'status',
        'admin_notes',
        'ip_address',
    ];
}
