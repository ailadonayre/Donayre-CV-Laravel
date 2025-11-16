@extends('layouts.app')

@section('title', 'Sign Up - Donayre CV')

@section('content')
<div class="auth-container">
    <form class="auth-form" method="POST" action="{{ route('register') }}" id="signupForm">
        @csrf
        
        <div class="auth-header">
            <h1 class="auth-title">Sign Up</h1>
        </div>

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

        <div class="form-group">
            <label for="username" class="form-label">Username</label>
            <input 
                type="text" 
                id="username" 
                name="username" 
                class="form-input"
                placeholder="Choose a username"
                value="{{ old('username') }}"
                minlength="3"
                required
                autofocus
            >
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                class="form-input"
                placeholder="Enter your email address"
                value="{{ old('email') }}"
                required
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
                    placeholder="Create a password"
                    minlength="6"
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
            <div class="password-strength" id="passwordStrength"></div>
        </div>

        <div class="form-group">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <div class="password-wrapper">
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    class="form-input form-input-with-toggle"
                    placeholder="Confirm your password"
                    minlength="6"
                    required
                >
                <button 
                    type="button" 
                    class="password-toggle" 
                    onclick="togglePassword('password_confirmation', this)"
                    aria-label="Toggle password visibility"
                >
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-auth">
            <i class="fa-solid fa-user-plus"></i>
            Create Account
        </button>

        <div class="auth-divider">
            <span>Already have an account?</span>
        </div>

        <div class="auth-link">
            <a href="{{ route('login') }}">
                <i class="fa-solid fa-right-to-bracket"></i>
                Sign in instead
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
    
    .password-strength {
        margin-top: 5px;
        font-size: 0.8rem;
        color: var(--text-muted);
    }
    
    .strength-weak { color: #e74c3c; }
    .strength-medium { color: #f39c12; }
    .strength-strong { color: #27ae60; }

    .auth-divider {
        text-align: center;
        margin: 15px 0;
        color: var(--text-secondary);
        font-size: 0.9rem;
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

    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        const strengthDiv = document.getElementById('passwordStrength');
        
        if (password.length === 0) {
            strengthDiv.textContent = '';
            return;
        }
        
        let strength = 0;
        
        if (password.length >= 6) strength += 1;
        if (/[a-z]/.test(password)) strength += 1;
        if (/[A-Z]/.test(password)) strength += 1;
        if (/[0-9]/.test(password)) strength += 1;
        if (/[^A-Za-z0-9]/.test(password)) strength += 1;
        
        if (strength < 2) {
            strengthDiv.textContent = 'Weak password';
            strengthDiv.className = 'password-strength strength-weak';
        } else if (strength < 4) {
            strengthDiv.textContent = 'Medium password';
            strengthDiv.className = 'password-strength strength-medium';
        } else {
            strengthDiv.textContent = 'Strong password';
            strengthDiv.className = 'password-strength strength-strong';
        }
    });

    document.getElementById('password_confirmation').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmPassword = this.value;
        
        if (confirmPassword.length === 0) {
            this.style.borderColor = 'var(--border-color)';
            return;
        }
        
        if (password === confirmPassword) {
            this.style.borderColor = '#27ae60';
        } else {
            this.style.borderColor = '#e74c3c';
        }
    });
</script>
@endpush
@endsection