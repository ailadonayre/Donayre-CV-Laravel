<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technology extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'tech_name',
        'display_order',
    ];

    protected $casts = [
        'category_id' => 'integer',
        'display_order' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(TechCategory::class, 'category_id');
    }
}