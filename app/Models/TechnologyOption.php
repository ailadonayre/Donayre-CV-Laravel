<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnologyOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'name',
        'is_preset',
        'display_order',
    ];

    protected $casts = [
        'is_preset' => 'boolean',
        'display_order' => 'integer',
    ];

    // Get all technologies grouped by category
    public static function getAllGrouped()
    {
        return static::where('is_preset', true)
            ->orderBy('category')
            ->orderBy('display_order')
            ->get()
            ->groupBy('category');
    }
}