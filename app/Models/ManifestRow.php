<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManifestRow extends Model
{
    protected $fillable = [
        'upload_id',
        'truck',
        'name',
        'drop_address',
        'route',
        'type',
        'seq',
        'account',
        'group',
        'draw',
        'returns',
        'pub_code',
        'pub_date',
        'truck_descr',
        'drop_instructions',
    ];

    protected $casts = [
        'pub_date' => 'date',
    ];

    public function upload()
    {
        return $this->belongsTo(ManifestUpload::class, 'upload_id');
    }
}
