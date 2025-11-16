<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExperienceTraitGlobal extends Model
{
    use HasFactory;

    protected $table = 'experience_traits_global';

    protected $fillable = [
        'user_id',
        'trait_icon',
        'trait_label',
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
}