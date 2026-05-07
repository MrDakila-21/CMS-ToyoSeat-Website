<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toyo Seat Philippines - Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin-login.css') }}">
    <!-- Vite CSS - This will compile and serve your CSS -->
@vite('resources/css/admin-login.css')
    
    <style>
        /* Inline style for dynamic background image */
        body {
            background-image: url('{{ asset('images/sample8.gif') }}');
        }
    </style>
</head>
<body>
    @if(session('success'))
        <div id="login-success-toast" class="login-toast success-toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="login-toast-content">
                <i class="fas fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div id="login-error-toast" class="login-toast error-toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="login-toast-content">
                <i class="fas fa-circle-exclamation"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        </div>
    @endif

    <div class="login-container">
        <div class="login-card">
            <!-- Logo -->
            <div class="logo-container">
                <img src="{{ asset('images/logo.svg') }}" alt="Toyo Seat Logo" class="logo">
            </div>

            <h1 class="company-name">TOYO SEAT</h1>
            
            <div class="company-sub">PHILIPPINES CORPORATION</div>

            <div class="login-title">
                CMS ADMIN LOGIN
            </div>

            <div class="form-wrapper">
                <form method="POST" action="{{ route('admin.login') }}">
                    @csrf
                    
                    <div class="form-group-username">
                        <div class="input-wrapper">
                            <div class="input-icon" id="username-icon">
                                <img src="{{ asset('images/admin.svg') }}" 
                                     class="svg-icon" 
                                     alt="user icon"
                                     style="width: 24px; height: 24px;"
                                     onerror="this.style.display='none'; document.getElementById('username-icon').classList.add('svg-fallback');">
                                <i class="fas fa-user fa-icon" style="font-size: 20px; color: #8F8686;"></i>
                            </div>
                            <input type="email" 
                                   class="form-control-username" 
                                   autocomplete="off"
                                   id="email" 
                                   name="email" 
                                   placeholder="Username" 
                                   value="{{ old('email') }}"
                                   required 
                                   autofocus>
                        </div>
                    </div>

                    <div class="form-group-password">
                        <div class="input-wrapper">
                            <div class="input-icon" id="password-icon">
                                <img src="{{ asset('images/lock.svg') }}" 
                                     class="svg-icon" 
                                     alt="lock icon"
                                     style="width: 24px; height: 24px;"
                                     onerror="this.style.display='none'; document.getElementById('password-icon').classList.add('svg-fallback');">
                                <i class="fas fa-lock fa-icon" style="font-size: 20px; color: #8F8686;"></i>
                            </div>
                            <input type="password" 
                                   class="form-control-password" 
                                    autocomplete="new-password"
                                   id="password" 
                                   name="password" 
                                   placeholder="Password"
                                   required>
                            <button type="button" id="password-toggle" class="password-toggle" aria-label="Show password" aria-pressed="false">
                                <i class="fas fa-eye" id="password-toggle-icon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="button-group">
                        <button type="submit" class="login-btn">
                            Login
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var adminSvg = new Image();
            adminSvg.onload = function() {
                console.log('admin.svg loaded successfully');
            };
            adminSvg.onerror = function() {
                console.log('admin.svg not found, using Font Awesome fallback');
                var usernameIcon = document.getElementById('username-icon');
                if (usernameIcon) {
                    usernameIcon.classList.add('svg-fallback');
                }
            };
            adminSvg.src = '{{ asset('images/admin.svg') }}';
            
            var lockSvg = new Image();
            lockSvg.onload = function() {
                console.log('lock.svg loaded successfully');
            };
            lockSvg.onerror = function() {
                console.log('lock.svg not found, using Font Awesome fallback');
                var passwordIcon = document.getElementById('password-icon');
                if (passwordIcon) {
                    passwordIcon.classList.add('svg-fallback');
                }
            };
            lockSvg.src = '{{ asset('images/lock.svg') }}';

            // Handle error toast
            var errorToast = document.getElementById('login-error-toast');
            if (errorToast) {
                setTimeout(function() {
                    errorToast.classList.add('hide');
                }, 5000);
                setTimeout(function() {
                    errorToast.remove();
                }, 5600);
            }
            
            // Handle success toast
            var successToast = document.getElementById('login-success-toast');
            if (successToast) {
                setTimeout(function() {
                    successToast.classList.add('hide');
                }, 5000);
                setTimeout(function() {
                    successToast.remove();
                }, 5600);
            }

            var passwordInput = document.getElementById('password');
            var passwordToggle = document.getElementById('password-toggle');
            var passwordToggleIcon = document.getElementById('password-toggle-icon');

            if (passwordInput && passwordToggle && passwordToggleIcon) {
                passwordToggle.addEventListener('click', function() {
                    var isPassword = passwordInput.type === 'password';
                    passwordInput.type = isPassword ? 'text' : 'password';
                    passwordToggleIcon.classList.toggle('fa-eye', !isPassword);
                    passwordToggleIcon.classList.toggle('fa-eye-slash', isPassword);
                    passwordToggle.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
                    passwordToggle.setAttribute('aria-pressed', isPassword ? 'true' : 'false');
                });
            }
        });
    </script>
</body>
</html>