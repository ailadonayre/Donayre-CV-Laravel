<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTechnology extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category',
        'technology_name',
        'is_custom',
        'display_order',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'is_custom' => 'boolean',
        'display_order' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Get technologies grouped by category
    public static function getGroupedByUser($userId)
    {
        return static::where('user_id', $userId)
            ->orderBy('category')
            ->orderBy('display_order')
            ->get()
            ->groupBy('category');
    }
}