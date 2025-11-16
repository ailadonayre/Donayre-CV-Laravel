<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExperienceKeyword extends Model
{
    use HasFactory;

    protected $fillable = [
        'experience_id',
        'keyword',
        'display_order',
    ];

    protected $casts = [
        'experience_id' => 'integer',
        'display_order' => 'integer',
    ];

    public function experience()
    {
        return $this->belongsTo(Experience::class);
    }
}