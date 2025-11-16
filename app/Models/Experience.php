<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    protected $table = 'experience';

    protected $fillable = [
        'user_id',
        'job_title',
        'company',
        'start_date',
        'end_date',
        'description',
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

    public function keywords()
    {
        return $this->hasMany(ExperienceKeyword::class)->orderBy('display_order');
    }

    // Helper method to sync keywords
    public function syncKeywords(array $keywords)
    {
        // Delete existing keywords
        $this->keywords()->delete();

        // Insert new keywords
        foreach ($keywords as $index => $keyword) {
            if (!empty(trim($keyword))) {
                $this->keywords()->create([
                    'keyword' => trim($keyword),
                    'display_order' => $index,
                ]);
            }
        }
    }
}