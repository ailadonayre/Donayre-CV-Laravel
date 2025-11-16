@extends('layouts.app')

@section('title', 'Login - Donayre CV')

@section('content')
<div class="auth-container">
    <form class="auth-form" method="POST" action="{{ route('login') }}">
        @csrf
        
        <div class="auth-header">
            <h1 class="auth-title">Welcome!</h1>
        </div>

        @if ($errors->any())
            <div class="alert alert-error">
                <i class="fa-solid fa-triangle-exclamation"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <div class="form-group">
            <label for="username" class="form-label">Username</label>
            <input 
                type="text" 
                id="username" 
                name="username" 
                class="form-input"
                placeholder="Enter your username"
                value="{{ old('username') }}"
                required
                autofocus
            >
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <div class="password-wrapper">
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-input form-input-with-toggle"
                    placeholder="Enter your password"
                    required
                >
                <button 
                    type="button" 
                    class="password-toggle" 
                    onclick="togglePassword('password', this)"
                    aria-label="Toggle password visibility"
                >
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-auth">
            <i class="fa-solid fa-right-to-bracket"></i>
            Log in
        </button>

        <div class="auth-link">
            <a href="{{ route('register') }}">
                <i class="fa-solid fa-user-plus"></i>
                Create new account
            </a>
        </div>
    </form>
</div>

@push('styles')
<style>
    .password-wrapper {
        position: relative;
    }
    
    .password-toggle {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--text-secondary);
        cursor: pointer;
        padding: 5px;
        font-size: 1.1rem;
        transition: color 0.3s ease;
        z-index: 10;
    }
    
    .password-toggle:hover {
        color: var(--accent-color);
    }
    
    .password-toggle:focus {
        outline: 2px solid var(--accent-color);
        outline-offset: 2px;
        border-radius: 4px;
    }
    
    .form-input-with-toggle {
        padding-right: 45px;
    }
</style>
@endpush

@push('scripts')
<script>
    function togglePassword(inputId, button) {
        const input = document.getElementById(inputId);
        const icon = button.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
            button.setAttribute('aria-label', 'Hide password');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
            button.setAttribute('aria-label', 'Show password');
        }
    }
</script>
@endpush
@endsection