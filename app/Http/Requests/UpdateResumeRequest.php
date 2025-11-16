<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResumeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization handled by auth middleware
    }

    public function rules(): array
    {
        return [
            'fullname' => 'required|string|max:100',
            'title' => 'nullable|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . auth()->id(),
            'contact' => 'nullable|string|max:50',
            'address' => 'required|string|max:255',
            'age' => 'required|integer|min:0|max:150',
            
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
        ];
    }

    public function messages(): array
    {
        return [
            'fullname.required' => 'Full name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already in use.',
            'address.required' => 'Address is required.',
            'age.required' => 'Age is required.',
            'age.integer' => 'Age must be a number.',
            'age.min' => 'Age must be at least 0.',
            'age.max' => 'Age cannot be more than 150.',
        ];
    }
}