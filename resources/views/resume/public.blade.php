@extends('layouts.app')

@section('title', ($user->fullname ?? 'User') . ' - Resume')

@section('content')
<header class="header">
    <div class="container">
        <div class="header-content">
            <div class="header-left">
                <h1 class="name">{{ $user->fullname ?? 'User' }}</h1>
                <h2 class="title">{{ $user->title ?? 'Professional' }}</h2>
            </div>
        </div>
    </div>
</header>

<main class="main-content">
    <div class="container">
        @if(!$hasResumeData)
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fa-solid fa-file"></i>
                </div>
                <h2 class="empty-state-title">Resume Not Available</h2>
                <p class="empty-state-description">
                    This user hasn't created their resume yet.
                </p>
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
        line-height: 1.6;
    }
</style>
@endpush
@endsection