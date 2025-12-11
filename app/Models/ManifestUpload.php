<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManifestUpload extends Model
{
    protected $fillable = [
        'pub_date',
        'pub_code',
        'original_filename',
        'stored_path',
        'user_id',
        'total_rows',
        'imported_rows',
        'skipped_rows',
        'status',
        'error_message',
        'combined_file_path',
        'combined_at',
    ];

    protected $casts = [
        'pub_date' => 'date',
        'combined_at' => 'datetime',
        'total_rows' => 'integer',
        'imported_rows' => 'integer',
        'skipped_rows' => 'integer',
    ];

    public function rows()
    {
        return $this->hasMany(ManifestRow::class, 'upload_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
