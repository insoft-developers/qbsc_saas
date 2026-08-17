<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatroliArchive extends Model
{
    protected $fillable = [
        'patroli_id',
        'company_id',
        'tanggal',
        'original_path',
        'google_drive_file_id',
        'google_drive_folder_id',
        'file_size',
        'status',
        'error_message',
        'uploaded_at',
        'deleted_at',
        'local_deleted_at'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'uploaded_at' => 'datetime',
        'deleted_at' => 'datetime',
         'local_deleted_at' => 'datetime',
    ];

    public function patroli()
    {
        return $this->belongsTo(Patroli::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}