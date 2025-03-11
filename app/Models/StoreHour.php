<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'open_days',
        'opening_time',
        'closing_time',
        'lunch_break_start',
        'lunch_break_end',
        'alternate_saturday',
    ];

    protected $casts = [
        'open_days'          => 'array',
        'alternate_saturday' => 'boolean',
    ];
}
