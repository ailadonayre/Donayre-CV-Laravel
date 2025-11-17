<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Get full storage path
    public function getFullPathAttribute()
    {
        return storage_path('app/public/' . $this->file_path);
    }

    // Get public URL
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

    // Check if file exists
    public function exists()
    {
        return \Storage::disk('public')->exists($this->file_path);
    }
}