@extends('layouts.app')

@section('title', ($user->fullname ?? 'User') . ' - Resume')

@section('content')
<header class="header">
    <div class="container">
        <div class="header-content">
            <div class="header-left">
                <h1 class="name">{{ $user->fullname ?? 'User' }}</h1>
                <h2 class="title">{{ $user->title ?? 'Professional' }}</h2>
                <p class="user-welcome">
                    Welcome, <strong>{{ $user->username }}</strong>! 
                    <a href="{{ route('resume.edit') }}" class="logout-link">
                        {{ $hasResumeData ? 'Edit Resume' : 'Create Resume' }}
                    </a>
                    <a href="{{ route('resume.public', $user->public_slug) }}" class="logout-link">Public View</a>
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
        @if(!$hasResumeData)
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fa-solid fa-file-circle-plus"></i>
                </div>
                <h2 class="empty-state-title">No Resume Yet</h2>
                <p class="empty-state-description">
                    You haven't created your resume yet. Get started by adding your education, 
                    experience, skills, and achievements to showcase your professional profile.
                </p>
                <a href="{{ route('resume.edit') }}" class="btn-create-resume">
                    <i class="fa-solid fa-plus"></i>
                    Create Your Resume
                </a>
            </div>
        @else
            @include('partials.resume-content')
        @endif
    </div>
</main>

@push('styles')
<style>
    .empty-state {
        background: var(--bg-primary);
        border-radius: 16px;
        padding: 60px 40px;
        text-align: center;
        box-shadow: 0 4px 20px var(--shadow-light);
        border: 2px dashed var(--border-color);
        margin: 40px auto;
        max-width: 600px;
    }
    
    .empty-state-icon {
        font-size: 4rem;
        color: var(--accent-color);
        margin-bottom: 20px;
    }
    
    .empty-state-title {
        color: var(--text-primary);
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 15px;
    }
    
    .empty-state-description {
        color: var(--text-secondary);
        font-size: 1.1rem;
        margin-bottom: 30px;
        line-height: 1.6;
    }
    
    .btn-create-resume {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: var(--accent-color);
        color: var(--white);
        padding: 15px 30px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }
    
    .btn-create-resume:hover {
        background: var(--accent-hover);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(93, 184, 177, 0.2);
    }
</style>
@endpush
@endsection