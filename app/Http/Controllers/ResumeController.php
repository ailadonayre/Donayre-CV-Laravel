<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Experience;
use App\Models\TechnologyOption;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ResumeController extends Controller
{
    /**
     * Display the authenticated user's resume (homepage after login)
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Load all relationships with newest first
        $user->load([
            'socialLinks',
            'education',
            'experience.keywords',
            'experienceTraitsGlobal',
            'achievements',
            'userTechnologies',
            'cvPdf'
        ]);

        // Group technologies by category
        $userTechnologies = $user->userTechnologies->groupBy('category');

        $hasResumeData = $user->hasResumeData();

        return view('resume.index', compact('user', 'userTechnologies', 'hasResumeData'));
    }

    /**
     * Show the resume edit form
     */
    public function edit()
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Load all relationships
        $user->load([
            'socialLinks',
            'education',
            'experience.keywords',
            'experienceTraitsGlobal',
            'achievements',
            'userTechnologies',
            'cvPdf'
        ]);

        // Get technology options grouped by category
        $techOptions = TechnologyOption::getAllGrouped();
        
        // Get user's selected technologies grouped by category
        $userTechnologies = $user->userTechnologies->groupBy('category');

        return view('resume.edit', compact('user', 'techOptions', 'userTechnologies'));
    }

    /**
     * Update the user's resume
     */
    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // Validate basic personal information with character limits
        $validated = $request->validate([
            'fullname' => 'required|string|max:100',
            'title' => 'nullable|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . $user->id,
            'contact' => 'nullable|string|max:50',
            'address' => 'required|string|max:255',
            'age' => 'required|integer|min:0|max:150',
            
            // Profile picture
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            
            // CV PDF
            'cv_pdf' => 'nullable|file|mimes:pdf|max:10240',
            
            // Social links
            'linkedin' => 'nullable|url|max:255',
            'github' => 'nullable|url|max:255',
            'custom_link' => 'nullable|url|max:255',
            
            // Section flags
            'has_education' => 'nullable|string|in:yes,no',
            'has_experience' => 'nullable|string|in:yes,no',
            'has_achievements' => 'nullable|string|in:yes,no',
            
            // Education (arrays)
            'education_degree.*' => 'nullable|string|max:200',
            'education_institution.*' => 'nullable|string|max:200',
            'education_start.*' => 'nullable|string|max:50',
            'education_end.*' => 'nullable|string|max:50',
            'education_description.*' => 'nullable|string|max:2000',
            
            // Experience (arrays)
            'experience_title.*' => 'nullable|string|max:200',
            'experience_company.*' => 'nullable|string|max:200',
            'experience_start.*' => 'nullable|string|max:50',
            'experience_end.*' => 'nullable|string|max:50',
            'experience_description.*' => 'nullable|string|max:2000',
            'experience_keywords.*' => 'nullable|string|max:500',
            
            // Achievements (arrays)
            'achievement_title.*' => 'nullable|string|max:200',
            'achievement_date.*' => 'nullable|string|max:50',
            'achievement_description.*' => 'nullable|string|max:2000',
            'achievement_icon.*' => 'nullable|string|max:50',
            
            // Global experience traits
            'experience_traits_global.*' => 'nullable|string|max:250',
            
            // Technologies (arrays per category)
            'tech_frontend.*' => 'nullable|string|max:100',
            'tech_backend.*' => 'nullable|string|max:100',
            'tech_databases.*' => 'nullable|string|max:100',
            'tech_devops.*' => 'nullable|string|max:100',
            'tech_multimedia.*' => 'nullable|string|max:100',
            'tech_mobile.*' => 'nullable|string|max:100',
            'tech_testing.*' => 'nullable|string|max:100',
        ]);

        DB::beginTransaction();

        try {
            // Handle profile picture upload
            if ($request->hasFile('profile_picture')) {
                // Delete old profile picture if exists
                if ($user->profile_picture) {
                    Storage::disk('public')->delete($user->profile_picture);
                }
                
                $file = $request->file('profile_picture');
                $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('profile_pictures', $filename, 'public');
                $validated['profile_picture'] = $path;
            }

            // Update basic user information
            $user->update([
                'fullname' => $validated['fullname'],
                'title' => $validated['title'],
                'email' => $validated['email'],
                'contact' => $validated['contact'],
                'address' => $validated['address'],
                'age' => $validated['age'],
                'profile_picture' => $validated['profile_picture'] ?? $user->profile_picture,
                'has_education' => ($request->input('has_education') === 'yes'),
                'has_experience' => ($request->input('has_experience') === 'yes'),
                'has_achievements' => ($request->input('has_achievements') === 'yes'),
            ]);

            // Handle CV PDF upload
            if ($request->hasFile('cv_pdf')) {
                // Delete old CV PDF if exists
                $oldPdf = $user->cvPdf;
                if ($oldPdf) {
                    Storage::disk('public')->delete($oldPdf->file_path);
                    $oldPdf->delete();
                }
                
                $file = $request->file('cv_pdf');
                $filename = 'cv_' . $user->id . '_' . time() . '.pdf';
                $path = $file->storeAs('attachments', $filename, 'public');
                
                Attachment::create([
                    'user_id' => $user->id,
                    'file_path' => $path,
                    'file_type' => 'cv_pdf',
                    'original_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                ]);
            }

            // Update social links
            $this->updateSocialLinks($user, $request);

            // Update education
            $this->updateEducation($user, $request);

            // Update experience
            $this->updateExperience($user, $request);

            // Update global experience traits
            $this->updateExperienceTraits($user, $request);

            // Update achievements
            $this->updateAchievements($user, $request);

            // Update technologies
            $this->updateTechnologies($user, $request);

            DB::commit();

            return redirect()->route('home')
                ->with('success', 'Resume updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to save: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete profile picture
     */
    public function deleteProfilePicture()
    {
        /** @var User $user */
        $user = Auth::user();
        
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
            $user->update(['profile_picture' => null]);
        }
        
        return back()->with('success', 'Profile picture deleted successfully!');
    }

    /**
     * Delete CV PDF
     */
    public function deleteCvPdf()
    {
        /** @var User $user */
        $user = Auth::user();
        
        $pdf = $user->cvPdf;
        if ($pdf) {
            Storage::disk('public')->delete($pdf->file_path);
            $pdf->delete();
        }
        
        return back()->with('success', 'CV PDF deleted successfully!');
    }

    /**
     * Update social links
     */
    private function updateSocialLinks(User $user, Request $request)
    {
        // Delete existing links
        $user->socialLinks()->delete();

        $socialLinks = [
            ['platform' => 'LinkedIn', 'url' => $request->input('linkedin'), 'icon' => 'fa-brands fa-linkedin'],
            ['platform' => 'GitHub', 'url' => $request->input('github'), 'icon' => 'fa-brands fa-github'],
            ['platform' => 'Custom', 'url' => $request->input('custom_link'), 'icon' => 'fa-solid fa-link'],
        ];

        $order = 0;
        foreach ($socialLinks as $link) {
            if (!empty($link['url'])) {
                $user->socialLinks()->create([
                    'platform' => $link['platform'],
                    'url' => $link['url'],
                    'icon' => $link['icon'],
                    'display_order' => $order++,
                ]);
            }
        }
    }

    /**
     * Update education records
     */
    private function updateEducation(User $user, Request $request)
    {
        // Delete existing education
        $user->education()->delete();

        // Only add if user selected "yes"
        if ($request->input('has_education') !== 'yes') {
            return;
        }

        $degrees = $request->input('education_degree', []);
        $institutions = $request->input('education_institution', []);
        $starts = $request->input('education_start', []);
        $ends = $request->input('education_end', []);
        $descriptions = $request->input('education_description', []);

        foreach ($degrees as $index => $degree) {
            if (!empty(trim($degree))) {
                $user->education()->create([
                    'degree' => trim($degree),
                    'institution' => trim($institutions[$index] ?? ''),
                    'start_date' => trim($starts[$index] ?? ''),
                    'end_date' => trim($ends[$index] ?? ''),
                    'description' => trim($descriptions[$index] ?? ''),
                    'display_order' => $index,
                ]);
            }
        }
    }

    /**
     * Update experience records
     */
    private function updateExperience(User $user, Request $request)
    {
        // Delete existing experience
        $user->experience()->delete();

        // Only add if user selected "yes"
        if ($request->input('has_experience') !== 'yes') {
            return;
        }

        $titles = $request->input('experience_title', []);
        $companies = $request->input('experience_company', []);
        $starts = $request->input('experience_start', []);
        $ends = $request->input('experience_end', []);
        $descriptions = $request->input('experience_description', []);
        $keywords = $request->input('experience_keywords', []);

        foreach ($titles as $index => $title) {
            if (!empty(trim($title))) {
                /** @var Experience $experience */
                $experience = $user->experience()->create([
                    'job_title' => trim($title),
                    'company' => trim($companies[$index] ?? ''),
                    'start_date' => trim($starts[$index] ?? ''),
                    'end_date' => trim($ends[$index] ?? ''),
                    'description' => trim($descriptions[$index] ?? ''),
                    'display_order' => $index,
                ]);

                // Add keywords
                if (!empty($keywords[$index])) {
                    $keywordArray = array_map('trim', explode(',', $keywords[$index]));
                    $experience->syncKeywords($keywordArray);
                }
            }
        }
    }

    /**
     * Update global experience traits
     */
    private function updateExperienceTraits(User $user, Request $request)
    {
        // Delete existing traits
        $user->experienceTraitsGlobal()->delete();

        $traits = $request->input('experience_traits_global', []);

        foreach ($traits as $index => $trait) {
            if (!empty(trim($trait))) {
                $parts = explode('|', $trait, 2);
                if (count($parts) === 2) {
                    $user->experienceTraitsGlobal()->create([
                        'trait_icon' => trim($parts[0]),
                        'trait_label' => trim($parts[1]),
                        'display_order' => $index,
                    ]);
                }
            }
        }
    }

    /**
     * Update achievements
     */
    private function updateAchievements(User $user, Request $request)
    {
        // Delete existing achievements
        $user->achievements()->delete();

        // Only add if user selected "yes"
        if ($request->input('has_achievements') !== 'yes') {
            return;
        }

        $titles = $request->input('achievement_title', []);
        $dates = $request->input('achievement_date', []);
        $descriptions = $request->input('achievement_description', []);
        $icons = $request->input('achievement_icon', []);

        foreach ($titles as $index => $title) {
            if (!empty(trim($title))) {
                $user->achievements()->create([
                    'title' => trim($title),
                    'achievement_date' => trim($dates[$index] ?? ''),
                    'description' => trim($descriptions[$index] ?? ''),
                    'icon' => trim($icons[$index] ?? 'fa-trophy'),
                    'display_order' => $index,
                ]);
            }
        }
    }

    /**
     * Update user technologies
     */
    private function updateTechnologies(User $user, Request $request)
    {
        // Delete existing technologies
        $user->userTechnologies()->delete();

        $categories = [
            'Frontend' => 'tech_frontend',
            'Backend' => 'tech_backend',
            'Databases' => 'tech_databases',
            'DevOps' => 'tech_devops',
            'Multimedia' => 'tech_multimedia',
            'Mobile' => 'tech_mobile',
            'Testing' => 'tech_testing',
        ];

        foreach ($categories as $category => $fieldName) {
            $technologies = $request->input($fieldName, []);
            
            if (!empty($technologies) && is_array($technologies)) {
                $displayOrder = 0;
                $customValue = '';
                $hasOtherChecked = false;

                // First pass: check for "Other" and custom value
                foreach ($technologies as $tech) {
                    if (trim($tech) === 'Other') {
                        $hasOtherChecked = true;
                    } elseif (strpos($tech, 'custom:') === 0) {
                        $customValue = trim(substr($tech, 7));
                    }
                }

                // Second pass: insert technologies
                foreach ($technologies as $tech) {
                    $tech = trim($tech);
                    if (empty($tech) || strpos($tech, 'custom:') === 0) {
                        continue;
                    }

                    $isCustom = false;

                    // If "Other" is checked and we have custom value, use it
                    if ($tech === 'Other' && !empty($customValue)) {
                        $tech = $customValue;
                        $isCustom = true;
                    } elseif ($tech === 'Other') {
                        continue; // Skip "Other" without custom value
                    }

                    $user->userTechnologies()->create([
                        'category' => $category,
                        'technology_name' => $tech,
                        'is_custom' => $isCustom,
                        'display_order' => $displayOrder++,
                    ]);
                }
            }
        }
    }
}