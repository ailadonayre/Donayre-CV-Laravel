<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'fullname',
        'title',
        'contact',
        'address',
        'age',
        'profile_summary',
        'public_slug',
        'profile_picture',
        'has_education',
        'has_experience',
        'has_achievements',
        'last_login',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'last_login' => 'datetime',
        'password' => 'hashed',
        'has_education' => 'boolean',
        'has_experience' => 'boolean',
        'has_achievements' => 'boolean',
        'age' => 'integer',
    ];

    // Relationships - Updated with descending order
    public function socialLinks()
    {
        return $this->hasMany(SocialLink::class)->orderBy('display_order');
    }

    public function education()
    {
        return $this->hasMany(Education::class)->orderBy('created_at', 'desc');
    }

    public function experience()
    {
        return $this->hasMany(Experience::class)->orderBy('created_at', 'desc');
    }

    public function experienceTraitsGlobal()
    {
        return $this->hasMany(ExperienceTraitGlobal::class)->orderBy('display_order');
    }

    public function achievements()
    {
        return $this->hasMany(Achievement::class)->orderBy('created_at', 'desc');
    }

    public function techCategories()
    {
        return $this->hasMany(TechCategory::class)->orderBy('display_order');
    }

    public function userTechnologies()
    {
        return $this->hasMany(UserTechnology::class)
            ->orderBy('category', 'asc')
            ->orderBy('display_order', 'asc');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    // Get CV PDF attachment
    public function cvPdf()
    {
        return $this->hasOne(Attachment::class)->where('file_type', 'cv_pdf')->latest();
    }

    // Auto-generate public_slug on creation
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->public_slug)) {
                $user->public_slug = static::generateUniqueSlug($user->username);
            }
        });
    }

    public static function generateUniqueSlug($username)
    {
        $slug = Str::slug($username);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('public_slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    // Helper to check if user has resume data
    public function hasResumeData()
    {
        return !empty($this->fullname) || 
               $this->education()->exists() || 
               $this->experience()->exists() || 
               $this->achievements()->exists() || 
               $this->userTechnologies()->exists();
    }

    // Get profile picture URL or default
    public function getProfilePictureUrlAttribute()
    {
        if ($this->profile_picture && \Storage::disk('public')->exists($this->profile_picture)) {
            return asset('storage/' . $this->profile_picture);
        }
        return null;
    }
}