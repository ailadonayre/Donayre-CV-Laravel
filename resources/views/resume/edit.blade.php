@extends('layouts.app')

@section('title', 'Edit Resume')

@section('content')
<header class="header">
    <div class="container">
        <div class="header-content">
            <div class="header-left">
                <h1 class="name">Edit Resume</h1>
                <h2 class="title">Update Your Information</h2>
                <p class="user-welcome">
                    Editing as <strong>{{ $user->username }}</strong>
                    <a href="{{ route('resume.public', $user->public_slug) }}" class="logout-link">Public View</a>
                    <a href="{{ route('home') }}" class="logout-link">Home</a>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="logout-link">Log Out</a>
                </p>
            </div>
        </div>
    </div>
</header>

<!-- Hidden logout form -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<main class="main-content">
    <div class="container">
        <div class="resume-editor">
            @if ($errors->any())
                <div class="alert alert-error">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <ul style="margin: 10px 0 0 20px; text-align: left;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('resume.update') }}" class="resume-form" id="resumeForm" enctype="multipart/form-data">
                @csrf
                
                <!-- Personal Information -->
                <div class="form-section">
                    <h3 class="section-title"><i class="fa-solid fa-user"></i> Personal Information</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Full Name *</label>
                            <input 
                                type="text" 
                                name="fullname" 
                                class="form-input" 
                                value="{{ old('fullname', $user->fullname) }}" 
                                maxlength="100"
                                required
                                placeholder="Enter your full name"
                            >
                        </div>
                        <div class="form-group">
                            <label class="form-label">Title/Position</label>
                            <input 
                                type="text" 
                                name="title" 
                                class="form-input" 
                                value="{{ old('title', $user->title) }}"
                                maxlength="100"
                                placeholder="e.g., Full Stack Developer"
                            >
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Email *</label>
                            <input 
                                type="email" 
                                name="email" 
                                class="form-input" 
                                value="{{ old('email', $user->email) }}"
                                maxlength="100"
                                required
                                placeholder="your.email@example.com"
                            >
                        </div>
                        <div class="form-group">
                            <label class="form-label">Contact Number</label>
                            <input 
                                type="tel" 
                                name="contact" 
                                class="form-input" 
                                value="{{ old('contact', $user->contact) }}"
                                maxlength="50"
                                placeholder="+1 (555) 123-4567"
                            >
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Age *</label>
                            <input 
                                type="number" 
                                name="age" 
                                class="form-input" 
                                value="{{ old('age', $user->age) }}"
                                min="0"
                                max="150"
                                required
                                placeholder="Enter your age"
                            >
                        </div>
                        <div class="form-group">
                            <label class="form-label">Address *</label>
                            <input 
                                type="text" 
                                name="address" 
                                class="form-input" 
                                value="{{ old('address', $user->address) }}"
                                maxlength="255"
                                required
                                placeholder="City, State/Province, Country"
                            >
                        </div>
                    </div>
                </div>

                <!-- Profile Picture Section -->
                <div class="form-section">
                    <h3 class="section-title"><i class="fa-solid fa-image"></i> Profile Picture</h3>
                    
                    @if($user->profile_picture_url)
                        <div class="current-profile-picture">
                            <img src="{{ $user->profile_picture_url }}" alt="Current profile picture" class="preview-image-circle">
                            <form method="POST" action="{{ route('profile.picture.delete') }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-remove-file" onclick="return confirm('Are you sure you want to delete your profile picture?')">
                                    <i class="fa-solid fa-trash"></i> Remove Picture
                                </button>
                            </form>
                        </div>
                    @endif
                    
                    <div class="form-group">
                        <label class="form-label">Upload Profile Picture</label>
                        <input 
                            type="file" 
                            name="profile_picture" 
                            class="form-input-file" 
                            accept="image/jpeg,image/jpg,image/png"
                            onchange="previewImage(this)"
                        >
                        <small class="form-note">Accepted formats: JPG, JPEG, PNG. Max size: 2MB</small>
                        <div id="image-preview" class="image-preview-container" style="display: none;">
                            <img id="preview-img" class="preview-image-circle" alt="Preview">
                        </div>
                    </div>
                </div>

                <!-- CV PDF Section -->
                <div class="form-section">
                    <h3 class="section-title"><i class="fa-solid fa-file-pdf"></i> CV PDF Upload</h3>
                    
                    @if($user->cvPdf && $user->cvPdf->exists())
                        <div class="current-cv-pdf">
                            <div class="pdf-display">
                                <i class="fa-solid fa-file-pdf" style="font-size: 2rem; color: var(--accent-color);"></i>
                                <span>{{ $user->cvPdf->original_name }}</span>
                                <a href="{{ $user->cvPdf->url }}" download class="btn-download-mini">
                                    <i class="fa-solid fa-download"></i> Download
                                </a>
                            </div>
                            <form method="POST" action="{{ route('cv.pdf.delete') }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-remove-file" onclick="return confirm('Are you sure you want to delete your CV PDF?')">
                                    <i class="fa-solid fa-trash"></i> Remove PDF
                                </button>
                            </form>
                        </div>
                    @endif
                    
                    <div class="form-group">
                        <label class="form-label">Upload CV PDF (Optional)</label>
                        <input 
                            type="file" 
                            name="cv_pdf" 
                            class="form-input-file" 
                            accept=".pdf,application/pdf"
                            onchange="validatePdfFile(this)"
                            id="cv_pdf_input"
                        >
                        <small class="form-note">
                            <i class="fa-solid fa-info-circle"></i> Upload your CV in PDF format. Optional. Max size: 5MB
                        </small>
                        @error('cv_pdf')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                        <div id="pdf-error" class="error-message" style="display: none;"></div>
                    </div>
                </div>

                <!-- Social Links -->
                <div class="form-section">
                    <h3 class="section-title"><i class="fa-solid fa-link"></i> Social Links</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">LinkedIn</label>
                            <input 
                                type="url" 
                                name="linkedin" 
                                class="form-input" 
                                value="{{ old('linkedin', $user->socialLinks->where('platform', 'LinkedIn')->first()->url ?? '') }}" 
                                maxlength="255"
                                placeholder="https://linkedin.com/in/yourprofile"
                            >
                        </div>
                        <div class="form-group">
                            <label class="form-label">GitHub</label>
                            <input 
                                type="url" 
                                name="github" 
                                class="form-input" 
                                value="{{ old('github', $user->socialLinks->where('platform', 'GitHub')->first()->url ?? '') }}"
                                maxlength="255"
                                placeholder="https://github.com/yourusername"
                            >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Custom Link</label>
                        <input 
                            type="url" 
                            name="custom_link" 
                            class="form-input" 
                            value="{{ old('custom_link', $user->socialLinks->where('platform', 'Custom')->first()->url ?? '') }}"
                            maxlength="255"
                            placeholder="https://yourwebsite.com"
                        >
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn-primary"><i class="fa-solid fa-save"></i> Update Resume</button>
                    <a href="{{ route('home') }}" class="btn-secondary"><i class="fa-solid fa-arrow-left"></i> Cancel</a>
                </div>

                <!-- Education Section -->
                <div class="form-section">
                    <h3 class="section-title"><i class="fa-solid fa-graduation-cap"></i> Education</h3>
                    
                    <div class="section-toggle">
                        <label for="has_education">Do you have any Education?</label>
                        <select name="has_education" id="has_education" onchange="toggleSection('education', this.value)">
                            <option value="no" {{ old('has_education', $user->has_education ? 'yes' : 'no') === 'no' ? 'selected' : '' }}>No</option>
                            <option value="yes" {{ old('has_education', $user->has_education ? 'yes' : 'no') === 'yes' ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>
                    
                    <div id="education-section-content" class="section-content {{ $user->has_education ? 'active' : '' }}">
                        <div id="education-container">
                            @forelse($user->education as $edu)
                                <div class="repeater-item">
                                    <div class="form-group">
                                        <label class="form-label">Degree Program *</label>
                                        <input type="text" name="education_degree[]" class="form-input" value="{{ old('education_degree.' . $loop->index, $edu->degree) }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Institution *</label>
                                        <input type="text" name="education_institution[]" class="form-input" value="{{ old('education_institution.' . $loop->index, $edu->institution) }}" required>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label class="form-label">Start Date</label>
                                            <input type="text" name="education_start[]" class="form-input" value="{{ old('education_start.' . $loop->index, $edu->start_date) }}" placeholder="2023">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">End Date</label>
                                            <input type="text" name="education_end[]" class="form-input" value="{{ old('education_end.' . $loop->index, $edu->end_date) }}" placeholder="Present">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Description</label>
                                        <textarea name="education_description[]" class="form-textarea" rows="3">{{ old('education_description.' . $loop->index, $edu->description) }}</textarea>
                                    </div>
                                    <button type="button" class="btn-remove" onclick="removeItem(this)">Remove</button>
                                </div>
                            @empty
                                <div class="repeater-item">
                                    <div class="form-group">
                                        <label class="form-label">Degree Program *</label>
                                        <input type="text" name="education_degree[]" class="form-input" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Institution *</label>
                                        <input type="text" name="education_institution[]" class="form-input" required>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label class="form-label">Start Date</label>
                                            <input type="text" name="education_start[]" class="form-input" placeholder="2023">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">End Date</label>
                                            <input type="text" name="education_end[]" class="form-input" placeholder="Present">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Description</label>
                                        <textarea name="education_description[]" class="form-textarea" rows="3"></textarea>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                        <button type="button" class="btn-add" onclick="addEducation()"><i class="fa-solid fa-plus"></i> Add Education</button>
                    </div>
                    
                    <div id="education-no-data" class="no-data-message" style="display: {{ !$user->has_education ? 'block' : 'none' }};">
                        No education entries will be displayed on your resume.
                    </div>
                </div>

                <!-- Global Experience Traits -->
                <div class="form-section">
                    <h3 class="section-title"><i class="fa-solid fa-star"></i> Experience Traits</h3>
                    <p style="color: var(--text-secondary); margin-bottom: 15px; font-size: 0.95rem;">
                        <i class="fa-solid fa-info-circle"></i> Select traits that describe your overall experience. These will be displayed once at the top of the Experience section.
                    </p>

                    <div id="experience-traits-global" class="trait-selector">
                        @php
                            $globalTraitOptions = [
                                'fa-code|Technical Expertise',
                                'fa-users|Team Leadership',
                                'fa-lightbulb|Problem Solving',
                                'fa-rotate-right|Adaptability',
                                'fa-rocket|Innovation',
                                'fa-chart-line|Growth',
                                'fa-handshake|Collaboration'
                            ];
                            
                            $existingTraits = $user->experienceTraitsGlobal->map(function($t) {
                                return $t->trait_icon . '|' . $t->trait_label;
                            })->toArray();
                        @endphp

                        @foreach($globalTraitOptions as $opt)
                            @php
                                [$icon, $label] = explode('|', $opt);
                                $isSelected = in_array($opt, $existingTraits) ? 'selected' : '';
                            @endphp
                            <div class="trait-option {{ $isSelected }}" data-value="{{ $opt }}" onclick="toggleGlobalTrait(this)">
                                <i class="fa-solid {{ $icon }}"></i>
                                <span>{{ $label }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div id="experience-traits-global-inputs">
                        @foreach($user->experienceTraitsGlobal as $trait)
                            <input type="hidden" name="experience_traits_global[]" value="{{ $trait->trait_icon }}|{{ $trait->trait_label }}">
                        @endforeach
                    </div>
                </div>

                <!-- Experience Section -->
                <div class="form-section">
                    <h3 class="section-title"><i class="fa-solid fa-briefcase"></i> Experience</h3>
                    
                    <div class="section-toggle">
                        <label for="has_experience">Do you have any Experience?</label>
                        <select name="has_experience" id="has_experience" onchange="toggleSection('experience', this.value)">
                            <option value="no" {{ old('has_experience', $user->has_experience ? 'yes' : 'no') === 'no' ? 'selected' : '' }}>No</option>
                            <option value="yes" {{ old('has_experience', $user->has_experience ? 'yes' : 'no') === 'yes' ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>
                    
                    <div id="experience-section-content" class="section-content {{ $user->has_experience ? 'active' : '' }}">
                        <div id="experience-container">
                            @forelse($user->experience as $exp)
                                <div class="repeater-item">
                                    <div class="form-group">
                                        <label class="form-label">Job Title/Position</label>
                                        <input type="text" name="experience_title[]" class="form-input" value="{{ old('experience_title.' . $loop->index, $exp->job_title) }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Company/Project</label>
                                        <input type="text" name="experience_company[]" class="form-input" value="{{ old('experience_company.' . $loop->index, $exp->company) }}">
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label class="form-label">Start Date</label>
                                            <input type="text" name="experience_start[]" class="form-input" value="{{ old('experience_start.' . $loop->index, $exp->start_date) }}" placeholder="Jan 2023">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">End Date</label>
                                            <input type="text" name="experience_end[]" class="form-input" value="{{ old('experience_end.' . $loop->index, $exp->end_date) }}" placeholder="Present">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Description</label>
                                        <textarea name="experience_description[]" class="form-textarea" rows="3">{{ old('experience_description.' . $loop->index, $exp->description) }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Keywords (comma-separated)</label>
                                        <input type="text" name="experience_keywords[]" class="form-input" value="{{ old('experience_keywords.' . $loop->index, $exp->keywords->pluck('keyword')->implode(', ')) }}" placeholder="e.g., Machine Learning, Data Visualization, Analytics">
                                        <small class="form-note">Enter keywords separated by commas. They will display as tags under the description.</small>
                                    </div>
                                    <button type="button" class="btn-remove" onclick="removeItem(this)">Remove</button>
                                </div>
                            @empty
                                <div class="repeater-item">
                                    <div class="form-group">
                                        <label class="form-label">Job Title/Position</label>
                                        <input type="text" name="experience_title[]" class="form-input">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Company/Project</label>
                                        <input type="text" name="experience_company[]" class="form-input">
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label class="form-label">Start Date</label>
                                            <input type="text" name="experience_start[]" class="form-input" placeholder="Jan 2023">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">End Date</label>
                                            <input type="text" name="experience_end[]" class="form-input" placeholder="Present">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Description</label>
                                        <textarea name="experience_description[]" class="form-textarea" rows="3"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Keywords (comma-separated)</label>
                                        <input type="text" name="experience_keywords[]" class="form-input" placeholder="e.g., Machine Learning, Data Visualization, Analytics">
                                        <small class="form-note">Enter keywords separated by commas. They will display as tags under the description.</small>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                        <button type="button" class="btn-add" onclick="addExperience()"><i class="fa-solid fa-plus"></i> Add Experience</button>
                    </div>
                    
                    <div id="experience-no-data" class="no-data-message" style="display: {{ !$user->has_experience ? 'block' : 'none' }};">
                        No experience entries will be displayed on your resume.
                    </div>
                </div>

                <!-- Achievements Section -->
                <div class="form-section">
                    <h3 class="section-title"><i class="fa-solid fa-trophy"></i> Achievements</h3>
                    
                    <div class="section-toggle">
                        <label for="has_achievements">Do you have any Achievements?</label>
                        <select name="has_achievements" id="has_achievements" onchange="toggleSection('achievements', this.value)">
                            <option value="no" {{ old('has_achievements', $user->has_achievements ? 'yes' : 'no') === 'no' ? 'selected' : '' }}>No</option>
                            <option value="yes" {{ old('has_achievements', $user->has_achievements ? 'yes' : 'no') === 'yes' ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>
                    
                    <div id="achievements-section-content" class="section-content {{ $user->has_achievements ? 'active' : '' }}">
                        <div id="achievement-container">
                            @forelse($user->achievements as $ach)
                                <div class="repeater-item">
                                    <div class="form-group">
                                        <label class="form-label">Achievement Title</label>
                                        <input type="text" name="achievement_title[]" class="form-input" value="{{ old('achievement_title.' . $loop->index, $ach->title) }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Date</label>
                                        <input type="text" name="achievement_date[]" class="form-input" value="{{ old('achievement_date.' . $loop->index, $ach->achievement_date) }}" placeholder="2024">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Description</label>
                                        <textarea name="achievement_description[]" class="form-textarea" rows="3">{{ old('achievement_description.' . $loop->index, $ach->description) }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Select Icon</label>
                                        <div class="icon-selector">
                                            @php
                                                $icons = ['fa-trophy', 'fa-medal', 'fa-certificate', 'fa-award', 'fa-star', 'fa-code'];
                                            @endphp
                                            @foreach($icons as $icon)
                                                <div class="icon-option {{ $ach->icon === $icon ? 'selected' : '' }}" data-icon="{{ $icon }}" onclick="selectIcon(this)">
                                                    <i class="fa-solid {{ $icon }}"></i>
                                                </div>
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="achievement_icon[]" value="{{ old('achievement_icon.' . $loop->index, $ach->icon) }}" class="icon-input">
                                    </div>
                                    <button type="button" class="btn-remove" onclick="removeItem(this)">Remove</button>
                                </div>
                            @empty
                                <div class="repeater-item">
                                    <div class="form-group">
                                        <label class="form-label">Achievement Title</label>
                                        <input type="text" name="achievement_title[]" class="form-input">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Date</label>
                                        <input type="text" name="achievement_date[]" class="form-input" placeholder="2024">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Description</label>
                                        <textarea name="achievement_description[]" class="form-textarea" rows="3"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Select Icon</label>
                                        <div class="icon-selector">
                                            @php
                                                $icons = ['fa-trophy', 'fa-medal', 'fa-certificate', 'fa-award', 'fa-star', 'fa-code'];
                                            @endphp
                                            @foreach($icons as $icon)
                                                <div class="icon-option {{ $loop->first ? 'selected' : '' }}" data-icon="{{ $icon }}" onclick="selectIcon(this)">
                                                    <i class="fa-solid {{ $icon }}"></i>
                                                </div>
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="achievement_icon[]" value="fa-trophy" class="icon-input">
                                    </div>
                                </div>
                            @endforelse
                        </div>
                        <button type="button" class="btn-add" onclick="addAchievement()"><i class="fa-solid fa-plus"></i> Add Achievement</button>
                    </div>
                    
                    <div id="achievements-no-data" class="no-data-message" style="display: {{ !$user->has_achievements ? 'block' : 'none' }};">
                        No achievement entries will be displayed on your resume.
                    </div>
                </div>

                <!-- Technologies Section -->
                <div class="form-section">
                    <h3 class="section-title"><i class="fa-solid fa-laptop-code"></i> Technologies</h3>
                    <p style="color: var(--text-secondary); margin-bottom: 15px; font-size: 0.9rem;">
                        <i class="fa-solid fa-info-circle"></i> Select technologies from predefined categories or add custom ones.
                    </p>
                    
                    @php
                        $categories = ['Frontend', 'Backend', 'Databases', 'DevOps', 'Multimedia', 'Mobile', 'Testing'];
                    @endphp
                    
                    @foreach($categories as $category)
                        @php
                            $fieldName = 'tech_' . strtolower(str_replace(' ', '_', $category));
                            $userSelectedInCategory = $userTechnologies->get($category, collect());
                            
                            // Find custom "Other" value
                            $hasCustomOther = false;
                            $customOtherValue = '';
                            foreach ($userSelectedInCategory as $selected) {
                                if ($selected->is_custom) {
                                    $hasCustomOther = true;
                                    $customOtherValue = $selected->technology_name;
                                    break;
                                }
                            }
                        @endphp
                        
                        <div class="tech-category-section">
                            <h4 class="tech-category-title">{{ $category }}</h4>
                            <div class="tech-checkboxes">
                                @foreach($techOptions->get($category, collect()) as $option)
                                    @php
                                        $isChecked = $userSelectedInCategory->contains('technology_name', $option->name) || ($option->name === 'Other' && $hasCustomOther);
                                        $inputId = 'tech_' . md5($category . $option->name);
                                        $isOther = ($option->name === 'Other');
                                    @endphp
                                    <div class="tech-checkbox-item">
                                        <input 
                                            type="checkbox" 
                                            id="{{ $inputId }}" 
                                            name="{{ $fieldName }}[]" 
                                            value="{{ $option->name }}"
                                            {{ $isChecked ? 'checked' : '' }}
                                            @if($isOther) onchange="toggleCustomInput(this, '{{ $category }}')" @endif
                                        >
                                        <label for="{{ $inputId }}">{{ $option->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Custom input field for "Other" -->
                            <div class="custom-tech-input {{ $hasCustomOther ? 'active' : '' }}" id="custom-{{ strtolower($category) }}">
                                <label class="form-label">Custom {{ $category }} Technology</label>
                                <input 
                                    type="text" 
                                    name="{{ $fieldName }}[]" 
                                    class="form-input" 
                                    placeholder="Enter custom technology name"
                                    value="{{ $hasCustomOther ? 'custom:' . $customOtherValue : '' }}"
                                >
                                <small class="form-note">This will only be saved if "Other" is checked above.</small>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn-primary"><i class="fa-solid fa-save"></i> Save Resume</button>
                    <a href="{{ route('home') }}" class="btn-secondary"><i class="fa-solid fa-arrow-left"></i> Cancel</a>
                </div>
            </form>
        </div>
    </div>
</main>

@push('styles')
<style>
    /* Profile Picture + PDF Section */
    .current-profile-picture, .current-cv-pdf {
        margin-bottom: 20px;
        padding: 15px;
        background: var(--bg-secondary);
        border-radius: 8px;
        border: 1px solid var(--border-color);
    }

    .preview-image-circle {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--accent-color);
        margin-bottom: 10px;
    }

    .pdf-display {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 10px;
    }

    .btn-download-mini {
        background: var(--accent-color);
        color: white;
        padding: 6px 12px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-download-mini:hover {
        background: var(--accent-hover);
    }

    .btn-remove-file {
        background: #e74c3c;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-remove-file:hover {
        background: #c0392b;
    }

    .form-input-file {
        width: 100%;
        padding: 12px;
        border: 2px dashed var(--border-color);
        border-radius: 8px;
        background: var(--bg-secondary);
        cursor: pointer;
        transition: all 0.3s;
    }

    .form-input-file:hover {
        border-color: var(--accent-color);
    }

    .image-preview-container {
        margin-top: 15px;
        text-align: center;
    }

    .error-message {
        color: #e74c3c;
        font-size: 0.9rem;
        margin-top: 5px;
        font-weight: 600;
    }

    /* Repeater Items */
    .repeater-item {
        background: var(--bg-secondary);
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 15px;
        border: 1px solid var(--border-color);
    }

    .btn-remove {
        background: #5db8b1;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-remove:hover {
        background: #4d9b94;
    }

    .btn-add {
        background: var(--accent-color);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        margin-top: 10px;
        transition: all 0.3s;
    }

    .btn-add:hover {
        background: var(--accent-hover);
    }

    /* Trait Selector */
    .trait-selector {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
    }

    .trait-option {
        padding: 8px 16px;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
        background: var(--bg-primary);
    }

    .trait-option:hover {
        border-color: var(--accent-color);
        background: var(--bg-secondary);
        transform: translateY(-2px);
    }

    .trait-option.selected {
        background: var(--accent-color);
        color: white;
        border-color: var(--accent-color);
    }

    .trait-option.selected i {
        color: white;
    }

    .trait-option i {
        color: var(--accent-color);
        transition: color 0.3s;
    }

    /* Icon Selector */
    .icon-selector {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
    }

    .icon-option {
        width: 50px;
        height: 50px;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1.5rem;
        transition: all 0.3s;
        background: var(--bg-primary);
    }

    .icon-option:hover {
        border-color: var(--accent-color);
        transform: scale(1.1);
    }

    .icon-option.selected {
        background: var(--accent-color);
        color: white;
        border-color: var(--accent-color);
    }

    .form-note {
        display: block;
        margin-top: 5px;
        font-size: 0.85rem;
        color: var(--text-muted);
        font-style: italic;
    }

    .section-toggle {
        margin-bottom: 20px;
        padding: 15px;
        background: var(--bg-secondary);
        border-radius: 8px;
        border: 2px solid var(--border-color);
    }

    .section-toggle label {
        font-weight: 700;
        color: var(--text-primary);
        margin-right: 10px;
    }

    .section-toggle select {
        padding: 8px 12px;
        border-radius: 6px;
        border: 2px solid var(--border-color);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-weight: 600;
        cursor: pointer;
    }

    .section-content {
        display: none;
    }

    .section-content.active {
        display: block;
    }

    .no-data-message {
        padding: 15px;
        background: rgba(239, 35, 60, 0.1);
        border: 2px solid rgba(239, 35, 60, 0.3);
        border-radius: 8px;
        color: var(--text-primary);
        font-weight: 600;
        text-align: center;
    }

    /* Tech Category */
    .tech-category-section {
        margin-bottom: 25px;
        padding: 20px;
        background: var(--bg-secondary);
        border-radius: 12px;
        border: 1px solid var(--border-color);
    }

    .tech-category-title {
        font-weight: 700;
        color: var(--accent-color);
        margin-bottom: 15px;
        font-size: 1.1rem;
    }

    .tech-checkboxes {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 10px;
    }

    .tech-checkbox-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px;
        background: var(--bg-primary);
        border-radius: 6px;
        border: 1px solid var(--border-color);
        transition: all 0.3s;
    }

    .tech-checkbox-item:hover {
        background: var(--bg-secondary);
        border-color: var(--accent-color);
    }

    .tech-checkbox-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .tech-checkbox-item label {
        cursor: pointer;
        font-weight: 500;
        color: var(--text-primary);
        flex: 1;
    }

    .custom-tech-input {
        margin-top: 10px;
        display: none;
    }

    .custom-tech-input.active {
        display: block;
    }

    .custom-tech-input input {
        width: 100%;
        padding: 10px;
        border-radius: 6px;
        border: 2px solid var(--border-color);
        background: var(--bg-primary);
        color: var(--text-primary);
    }
</style>
@endpush

@push('scripts')
<script>
    // Section toggle logic
    function toggleSection(sectionName, value) {
        const content = document.getElementById(sectionName + '-section-content');
        const noData = document.getElementById(sectionName + '-no-data');
        
        if (value === 'yes') {
            content.classList.add('active');
            if (noData) noData.style.display = 'none';
        } else {
            content.classList.remove('active');
            if (noData) noData.style.display = 'block';
        }
    }
    
    // Custom tech input toggle
    function toggleCustomInput(checkbox, category) {
        const customInput = document.getElementById('custom-' + category.toLowerCase());
        if (checkbox.checked) {
            customInput.classList.add('active');
        } else {
            customInput.classList.remove('active');
            const input = customInput.querySelector('input');
            if (input) input.value = '';
        }
    }
    
    function removeItem(btn) {
        btn.closest('.repeater-item').remove();
    }
    
    function addEducation() {
        const container = document.getElementById('education-container');
        const html = `
            <div class="repeater-item">
                <div class="form-group">
                    <label class="form-label">Degree Program *</label>
                    <input type="text" name="education_degree[]" class="form-input" maxlength="200" required placeholder="e.g., Bachelor of Science in Computer Science">
                </div>
                <div class="form-group">
                    <label class="form-label">Institution *</label>
                    <input type="text" name="education_institution[]" class="form-input" maxlength="200" required placeholder="e.g., University of Technology">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Start Date</label>
                        <input type="text" name="education_start[]" class="form-input" maxlength="50" placeholder="2020">
                    </div>
                    <div class="form-group">
                        <label class="form-label">End Date</label>
                        <input type="text" name="education_end[]" class="form-input" maxlength="50" placeholder="Present">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="education_description[]" class="form-textarea" rows="3" maxlength="2000" placeholder="Describe your education, achievements, or relevant coursework..."></textarea>
                </div>
                <button type="button" class="btn-remove" onclick="removeItem(this)">Remove</button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }

    function addExperience() {
        const container = document.getElementById('experience-container');
        const html = `
            <div class="repeater-item">
                <div class="form-group">
                    <label class="form-label">Job Title/Position *</label>
                    <input type="text" name="experience_title[]" class="form-input" maxlength="200" required placeholder="e.g., Senior Software Engineer">
                </div>
                <div class="form-group">
                    <label class="form-label">Company/Project</label>
                    <input type="text" name="experience_company[]" class="form-input" maxlength="200" placeholder="e.g., Tech Corp Inc.">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Start Date</label>
                        <input type="text" name="experience_start[]" class="form-input" maxlength="50" placeholder="Jan 2023">
                    </div>
                    <div class="form-group">
                        <label class="form-label">End Date</label>
                        <input type="text" name="experience_end[]" class="form-input" maxlength="50" placeholder="Present">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="experience_description[]" class="form-textarea" rows="3" maxlength="2000" placeholder="Describe your responsibilities, achievements, and key contributions..."></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Keywords (comma-separated)</label>
                    <input type="text" name="experience_keywords[]" class="form-input" maxlength="500" placeholder="e.g., Machine Learning, Data Visualization, Analytics">
                    <small class="form-note">Enter keywords separated by commas. They will display as tags under the description.</small>
                </div>
                <button type="button" class="btn-remove" onclick="removeItem(this)">Remove</button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }

    function addAchievement() {
        const container = document.getElementById('achievement-container');
        const html = `
            <div class="repeater-item">
                <div class="form-group">
                    <label class="form-label">Achievement Title *</label>
                    <input type="text" name="achievement_title[]" class="form-input" maxlength="200" required placeholder="e.g., Best Innovation Award 2024">
                </div>
                <div class="form-group">
                    <label class="form-label">Date</label>
                    <input type="text" name="achievement_date[]" class="form-input" maxlength="50" placeholder="2024">
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="achievement_description[]" class="form-textarea" rows="3" maxlength="2000" placeholder="Describe the achievement and its impact..."></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Select Icon</label>
                    <div class="icon-selector">
                        <div class="icon-option selected" data-icon="fa-trophy" onclick="selectIcon(this)"><i class="fa-solid fa-trophy"></i></div>
                        <div class="icon-option" data-icon="fa-medal" onclick="selectIcon(this)"><i class="fa-solid fa-medal"></i></div>
                        <div class="icon-option" data-icon="fa-certificate" onclick="selectIcon(this)"><i class="fa-solid fa-certificate"></i></div>
                        <div class="icon-option" data-icon="fa-award" onclick="selectIcon(this)"><i class="fa-solid fa-award"></i></div>
                        <div class="icon-option" data-icon="fa-star" onclick="selectIcon(this)"><i class="fa-solid fa-star"></i></div>
                        <div class="icon-option" data-icon="fa-code" onclick="selectIcon(this)"><i class="fa-solid fa-code"></i></div>
                    </div>
                    <input type="hidden" name="achievement_icon[]" value="fa-trophy" class="icon-input">
                </div>
                <button type="button" class="btn-remove" onclick="removeItem(this)">Remove</button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }
    
    function selectIcon(element) {
        const container = element.closest('.icon-selector');
        container.querySelectorAll('.icon-option').forEach(opt => opt.classList.remove('selected'));
        element.classList.add('selected');
        
        const input = container.nextElementSibling;
        input.value = element.dataset.icon;
    }
    
    function toggleGlobalTrait(el) {
        if (!el) return;
        el.classList.toggle('selected');
        updateGlobalTraitInputs();
    }

    function updateGlobalTraitInputs() {
        const container = document.getElementById('experience-traits-global-inputs');
        if (!container) return;
        container.innerHTML = '';
        document.querySelectorAll('#experience-traits-global .trait-option.selected').forEach(opt => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'experience_traits_global[]';
            input.value = opt.dataset.value;
            container.appendChild(input);
        });
    }

    function previewImage(input) {
        const preview = document.getElementById('image-preview');
        const img = document.getElementById('preview-img');
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            // Validate file type
            if (!file.type.match('image/(jpeg|jpg|png)')) {
                alert('Please select a valid image file (JPG, JPEG, or PNG)');
                input.value = '';
                preview.style.display = 'none';
                return;
            }
            
            // Validate file size (2MB)
            if (file.size > 2048 * 1024) {
                alert('Image size must be less than 2MB');
                input.value = '';
                preview.style.display = 'none';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    }
    
    function validatePdfFile(input) {
        const errorDiv = document.getElementById('pdf-error');
        errorDiv.style.display = 'none';
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            // Validate file type
            if (file.type !== 'application/pdf') {
                errorDiv.textContent = 'Please select a valid PDF file';
                errorDiv.style.display = 'block';
                input.value = '';
                return false;
            }
            
            // Validate file size (5MB)
            if (file.size > 5120 * 1024) {
                errorDiv.textContent = 'PDF size must be less than 5MB';
                errorDiv.style.display = 'block';
                input.value = '';
                return false;
            }
        }
        return true;
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateGlobalTraitInputs();
        
        // Initialize custom tech inputs
        const categories = ['Frontend', 'Backend', 'Databases', 'DevOps', 'Multimedia', 'Mobile', 'Testing'];
        categories.forEach(category => {
            const otherCheckbox = document.querySelector('input[value="Other"][name*="' + category.toLowerCase() + '"]');
            if (otherCheckbox && otherCheckbox.checked) {
                const customInput = document.getElementById('custom-' + category.toLowerCase());
                if (customInput) {
                    customInput.classList.add('active');
                }
            }
        });
    });
</script>
@endpush
@endsection