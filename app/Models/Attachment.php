<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'file_path',
        'file_type',
        'original_name',
        'file_size',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'file_size' => 'integer',
    ];

    /**
     * Relationship: attachment belongs to user
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get full storage path
     * 
     * @return string
     */
    public function getFullPathAttribute(): string
    {
        return storage_path('app/public/' . $this->file_path);
    }

    /**
     * Get public URL for the attachment
     * 
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    /**
     * Check if file exists on disk
     * 
     * @return bool
     */
    public function exists(): bool
    {
        return Storage::disk('public')->exists($this->file_path);
    }
}