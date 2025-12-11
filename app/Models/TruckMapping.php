<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TruckMapping extends Model
{
    protected $fillable = [
        'source_code',
        'target_code',
        'description',
        'is_active',
    ];

    /**
     * Scope only active mappings.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
