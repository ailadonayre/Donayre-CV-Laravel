<div class="content-grid">
    <!-- Left Column -->
    <div class="left-column">
        <!-- Profile Card -->
        <div class="card profile-card">
            <div class="card-header">
                <span class="header-label">Profile</span>
            </div>
            <div class="profile-section">
                <div class="profile-info">
                    <h3 class="profile-name-title">
                        <span class="name-highlight">{{ strtoupper($user->fullname ?? 'User') }}</span>
                    </h3>
                    @if($user->title)
                    <p class="profile-subtitle">{{ $user->title }}</p>
                    @endif
                    
                    <div class="profile-details">
                        @if($user->age)
                        <div class="detail-item">
                            <span class="detail-label">Age</span>
                            <span class="detail-value">{{ $user->age }}</span>
                        </div>
                        @endif
                        
                        @if($user->address)
                        <div class="detail-item">
                            <span class="detail-label">Address</span>
                            <span class="detail-value">{{ $user->address }}</span>
                        </div>
                        @endif
                        
                        @if($user->email)
                        <div class="detail-item">
                            <span class="detail-label">Email</span>
                            <span class="detail-value">{{ $user->email }}</span>
                        </div>
                        @endif
                        
                        @if($user->contact)
                        <div class="detail-item">
                            <span class="detail-label">Contact</span>
                            <span class="detail-value">{{ $user->contact }}</span>
                        </div>
                        @endif
                    </div>
                    
                    @if($user->socialLinks->count() > 0)
                        <div class="profile-social">
                            @foreach($user->socialLinks as $link)
                                <a href="{{ $link->url }}" target="_blank" class="social-icon" rel="noopener noreferrer" title="{{ $link->platform }}">
                                    <i class="{{ $link->icon }}"></i>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Experience Card -->
        <div class="card experience-card">
            <h3 class="card-title">EXPERIENCE</h3>
            
            @if(!$user->has_experience)
                <div class="experience-content">
                    <p style="text-align: center; color: var(--text-muted); padding: 20px; font-style: italic;">
                        No available data.
                    </p>
                </div>
            @elseif($user->experience->isEmpty())
                <div class="experience-content">
                    <p style="text-align: center; color: var(--text-muted); padding: 20px; font-style: italic;">
                        No experience entries added yet.
                    </p>
                </div>
            @else
                @if($user->experienceTraitsGlobal->count() > 0)
                <div class="experience-traits-footer">
                    <div class="trait-boxes">
                        @foreach($user->experienceTraitsGlobal as $trait)
                            <div class="trait-box">
                                <i class="fa-solid {{ $trait->trait_icon }}"></i>
                                <span class="trait-label">{{ $trait->trait_label }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <div class="experience-content">
                    <div class="experience-section">
                        @foreach($user->experience as $exp)
                        <div class="experience-item">
                            <div class="experience-header">
                                <h4 class="experience-title">{{ $exp->job_title }}</h4>
                                @if($exp->start_date || $exp->end_date)
                                <span class="experience-date">
                                    {{ $exp->start_date }}
                                    @if($exp->start_date && $exp->end_date) - @endif
                                    {{ $exp->end_date }}
                                </span>
                                @endif
                            </div>
                            
                            @if($exp->company)
                            <p class="experience-company">{{ $exp->company }}</p>
                            @endif
                            
                            @if($exp->description)
                            <p class="experience-description">{!! nl2br(e($exp->description)) !!}</p>
                            @endif
                            
                            @if($exp->keywords->count() > 0)
                            <div class="experience-skills">
                                @foreach($exp->keywords as $keyword)
                                    <span class="skill-tag">{{ $keyword->keyword }}</span>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Right Column -->
    <div class="right-column">
        <!-- Education Card -->
        <div class="card education-card">
            <h3 class="card-title">EDUCATION</h3>
            <div class="timeline">
                @if(!$user->has_education)
                    <p style="text-align: center; color: var(--text-muted); padding: 20px 0; font-style: italic;">
                        No available data.
                    </p>
                @elseif($user->education->isEmpty())
                    <p style="text-align: center; color: var(--text-muted); padding: 20px 0; font-style: italic;">
                        No education entries added yet.
                    </p>
                @else
                    @foreach($user->education as $edu)
                    <div class="timeline-item">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h4 class="timeline-title">{{ $edu->degree }}</h4>
                            @if($edu->institution)
                            <p class="timeline-subtitle">{{ $edu->institution }}</p>
                            @endif
                            @if($edu->start_date || $edu->end_date)
                            <span class="timeline-date">
                                {{ $edu->start_date }}
                                @if($edu->start_date && $edu->end_date) - @endif
                                {{ $edu->end_date }}
                            </span>
                            @endif
                            @if($edu->description)
                            <p class="timeline-description">{!! nl2br(e($edu->description)) !!}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Achievements Card -->
        <div class="card achievements-card">
            <h3 class="card-title">ACHIEVEMENTS</h3>
            <div class="achievements-list">
                @if(!$user->has_achievements)
                    <p style="text-align: center; color: var(--text-muted); padding: 20px 0; font-style: italic;">
                        No available data.
                    </p>
                @elseif($user->achievements->isEmpty())
                    <p style="text-align: center; color: var(--text-muted); padding: 20px 0; font-style: italic;">
                        No achievement entries added yet.
                    </p>
                @else
                    @foreach($user->achievements as $ach)
                    <div class="achievement-item">
                        <div class="achievement-icon">
                            <i class="fa-solid {{ $ach->icon }}"></i>
                        </div>
                        <div class="achievement-content">
                            <h4 class="achievement-title">{{ $ach->title }}</h4>
                            @if($ach->description)
                            <p class="achievement-description">{!! nl2br(e($ach->description)) !!}</p>
                            @endif
                            @if($ach->achievement_date)
                            <span class="achievement-date">{{ $ach->achievement_date }}</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- Technologies Card (Full Width) -->
    @if($userTechnologies->count() > 0)
    <div class="card tech-card">
        <h3 class="card-title">TECHNOLOGIES</h3>
        <div class="tech-content">
            <div class="tech-grid">
                @foreach($userTechnologies as $category => $technologies)
                    @if($technologies->count() > 0)
                    <div class="tech-category">
                        <h4>{{ $category }}</h4>
                        <div class="tech-tags">
                            @foreach($technologies->unique('technology_name') as $tech)
                                <span class="tech-tag">{{ $tech->technology_name }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>