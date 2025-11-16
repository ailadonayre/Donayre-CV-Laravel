<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_name',
        'display_order',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'display_order' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function technologies()
    {
        return $this->hasMany(Technology::class, 'category_id')->orderBy('display_order');
    }
}