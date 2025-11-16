<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PublicResumeController extends Controller
{
    /**
     * Display a user's public resume by slug
     */
    public function show($slug)
    {
        // Find user by public_slug or username
        $user = User::where('public_slug', $slug)
            ->orWhere('username', $slug)
            ->firstOrFail();

        // Load all relationships
        $user->load([
            'socialLinks',
            'education',
            'experience.keywords',
            'experienceTraitsGlobal',
            'achievements',
            'userTechnologies'
        ]);

        // Group technologies by category
        $userTechnologies = $user->userTechnologies->groupBy('category');

        $hasResumeData = $user->hasResumeData();

        // Use the same view as authenticated resume, but mark as public
        return view('resume.public', compact('user', 'userTechnologies', 'hasResumeData'));
    }
}