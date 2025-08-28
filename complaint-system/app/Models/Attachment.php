<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attachment extends Model
{
    protected $fillable = [
        'complaint_id',
        'file_path',
        'original_name',
        'file_type',
        'file_size',
    ];

    /**
     * Get the complaint that owns the attachment.
     */
    public function complaint(): BelongsTo
    {
        return $this->belongsTo(Complaint::class);
    }
}
