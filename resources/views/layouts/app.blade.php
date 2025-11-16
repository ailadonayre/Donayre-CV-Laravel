<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Donayre CV')</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/resume.css') }}?v={{ time() }}">
    
    @stack('styles')
</head>
<body>
    <!-- Dark Mode Toggle -->
    <button class="dark-mode-toggle" id="darkModeToggle" aria-label="Toggle dark mode">
        <i class="fa-solid fa-moon"></i>
    </button>

    <!-- Success Notification -->
    @if(session('success'))
    <div class="success-notification" id="successNotification">
        <div class="notification-content">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
            <button class="notification-close" onclick="closeNotification()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </div>
    @endif

    @yield('content')

    <!-- Scripts -->
    <script src="{{ asset('js/script.js') }}?v={{ time() }}"></script>
    
    @if(session('success'))
    <script>
        function closeNotification() {
            const notification = document.getElementById('successNotification');
            if (notification) {
                notification.style.animation = 'slideOut 0.3s ease forwards';
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }
        }

        setTimeout(() => {
            closeNotification();
        }, 5000);
    </script>
    @endif
    
    @stack('scripts')
</body>
</html>