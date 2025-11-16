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
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="logout-link" style="background: none; border: none; cursor: pointer; padding: 4px 8px;">Log Out</button>
                    </form>
                </p>
            </div>
        </div>
    </div>
</header>

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

            <form method="POST" action="{{ route('resume.update') }}" class="resume-form" id="resumeForm">
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

                {{-- Continue in next part... --}}