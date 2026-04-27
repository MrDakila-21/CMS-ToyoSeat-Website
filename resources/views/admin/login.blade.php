<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toyo Seat Philippines - Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for fallback icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            background-image: url('{{ asset('images/background.svg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
            width: 100%;
            height: 100vh;
        }

        /* Centered Container */
        .login-container {
            position: relative;
            z-index: 2;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* Modal Card - Exact Figma Login Modal Styles */
        .login-card {
            position: relative;
            width: 483px;
            height: 536px;
            background: #FFFFFF;
            border-radius: 15px;
            margin: 0 auto;
            padding: 0;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            animation: slideDown 0.5s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Logo Section */
        .logo-container {
            position: absolute;
            width: 90px;
            height: 90px;
            left: 50%;
            transform: translateX(-50%);
            top: 38px;
            text-align: center;
        }

        .logo {
            width: 90px;
            height: 90px;
            object-fit: contain;
            border-radius: 50%;
        }

        /* TOYO SEAT */
        .company-name {
            position: absolute;
            width: 202px;
            height: 48px;
            left: 50%;
            transform: translateX(-50%);
            top: 128px;
            font-family: 'Poppins', sans-serif;
            font-style: normal;
            font-weight: 700;
            font-size: 32px;
            line-height: 48px;
            color: #015A96;
            text-align: center;
            margin: 0;
            letter-spacing: 1px;
        }

        /* PHILIPPINES CORPORATION */
        .company-sub {
            position: absolute;
            width: 174px;
            height: 17px;
            left: 50%;
            transform: translateX(-50%);
            top: 167px;
            font-family: 'Inria Sans', sans-serif;
            font-style: italic;
            font-weight: 400;
            font-size: 12px;
            line-height: 17px;
            text-align: center;
            color: #015A96;
            margin: 0;
        }

        /* CMS ADMIN LOGIN */
        .login-title {
            position: absolute;
            width: 196px;
            height: 24px;
            left: 50%;
            transform: translateX(-50%);
            top: 196px;
            font-family: 'Poppins', sans-serif;
            font-style: normal;
            font-weight: 600;
            font-size: 20px;
            line-height: 30px;
            color: #8F8686;
            text-align: center;
            margin: 0;
            padding: 0;
            border-bottom: none;
        }

        /* Form Wrapper */
        .form-wrapper {
            position: relative;
            width: 100%;
            margin-top: 259px;
            padding: 0 99px;
        }

        /* Form Group for Username */
        .form-group-username {
            position: relative;
            width: 285px;
            height: 58px;
            margin-bottom: 26px;
        }

        /* Input wrapper for positioning icon */
        .input-wrapper {
            position: relative;
            width: 100%;
            height: 100%;
        }

        /* Username Input Field */
        .form-control-username {
            width: 100%;
            height: 58px;
            background: #FFFFFF;
            border: 1px solid #8F8686;
            border-radius: 10px;
            padding-left: 50px;
            padding-right: 15px;
            font-family: 'Poppins', sans-serif;
            font-style: normal;
            font-weight: 500;
            font-size: 16px;
            line-height: 24px;
            color: #333;
            transition: all 0.3s ease;
        }

        .form-control-username::placeholder {
            color: #8F8686;
            font-weight: 500;
            font-size: 16px;
        }

        .form-control-username:focus {
            outline: none;
            border-color: #015A96;
            box-shadow: 0 0 0 3px rgba(1, 90, 150, 0.1);
        }

        /* Password Input Field */
        .form-control-password {
            width: 100%;
            height: 58px;
            background: #FFFFFF;
            border: 1px solid #8F8686;
            border-radius: 10px;
            padding-left: 50px;
            padding-right: 15px;
            font-family: 'Poppins', sans-serif;
            font-style: normal;
            font-weight: 500;
            font-size: 16px;
            line-height: 24px;
            color: #333;
            transition: all 0.3s ease;
        }

        .form-control-password::placeholder {
            color: #8F8686;
            font-weight: 500;
            font-size: 16px;
        }

        .form-control-password:focus {
            outline: none;
            border-color: #015A96;
            box-shadow: 0 0 0 3px rgba(1, 90, 150, 0.1);
        }

        /* Icon Styles - Using both SVG and Font Awesome fallback */
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            z-index: 1;
        }

        /* SVG Icon styling */
        .input-icon svg {
            width: 24px;
            height: 24px;
        }

        /* Font Awesome fallback */
        .input-icon i {
            font-size: 20px;
            color: #8F8686;
        }

        /* Hide SVG if not loaded, show FA instead */
        .input-icon .svg-icon {
            display: block;
        }
        
        .input-icon .fa-icon {
            display: none;
        }

        /* If SVG fails to load, show FA */
        .input-icon.svg-fallback .svg-icon {
            display: none;
        }
        
        .input-icon.svg-fallback .fa-icon {
            display: block;
        }

        /* Form Group for Password */
        .form-group-password {
            position: relative;
            width: 285px;
            height: 58px;
            margin-bottom: 26px;
        }

        /* Button Group */
        .button-group {
            position: relative;
            width: 285px;
            height: 58px;
            margin-top: 10px;
        }

        /* Login Button */
        .login-btn {
            width: 100%;
            height: 58px;
            background: #015A96;
            border-radius: 10px;
            border: none;
            font-family: 'Poppins', sans-serif;
            font-style: normal;
            font-weight: 600;
            font-size: 20px;
            line-height: 30px;
            color: #FFFFFF;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .login-btn:hover {
            background-color: #004070;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(1, 90, 150, 0.3);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        /* Alert Messages */
        .alert {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 285px;
            padding: 12px;
            border-radius: 10px;
            font-size: 14px;
            text-align: center;
            z-index: 10;
            margin: 0;
        }

        .alert-danger {
            background-color: #fee;
            border: 1px solid #fcc;
            color: #c33;
        }

        /* Hide default labels */
        .form-label {
            display: none;
        }

        /* Responsive Design */
        @media (max-width: 520px) {
            .login-card {
                width: 95%;
                max-width: 483px;
                height: auto;
                min-height: 536px;
            }
            
            .form-wrapper {
                padding: 0 5%;
            }
            
            .form-group-username,
            .form-group-password,
            .button-group,
            .alert {
                width: 100%;
            }
            
            .company-name {
                font-size: 28px;
                width: auto;
                white-space: nowrap;
            }
            
            .company-sub {
                width: auto;
                white-space: nowrap;
            }
            
            .login-title {
                width: auto;
                white-space: nowrap;
            }
        }

        @media (max-width: 480px) {
            .company-name {
                font-size: 24px;
                white-space: normal;
                width: 90%;
            }
            
            .company-sub {
                white-space: normal;
                width: 90%;
            }
            
            .login-title {
                white-space: normal;
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Logo -->
            <div class="logo-container">
                <img src="{{ asset('images/logo.svg') }}" alt="Toyo Seat Logo" class="logo">
            </div>

            <!-- TOYO SEAT -->
            <h1 class="company-name">TOYO SEAT</h1>
            
            <!-- PHILIPPINES CORPORATION -->
            <div class="company-sub">PHILIPPINES CORPORATION</div>

            <!-- CMS ADMIN LOGIN -->
            <div class="login-title">
                CMS ADMIN LOGIN
            </div>

            <!-- Form Wrapper -->
            <div class="form-wrapper">
                <!-- Error Messages -->
                @if($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('admin.login') }}">
                    @csrf
                    
                    <!-- Username Field -->
                    <div class="form-group-username">
                        <div class="input-wrapper">
                            <div class="input-icon" id="username-icon">
                                <!-- Try SVG first -->
                                <img src="{{ asset('images/admin.svg') }}" 
                                     class="svg-icon" 
                                     alt="user icon"
                                     style="width: 24px; height: 24px;"
                                     onerror="this.style.display='none'; document.getElementById('username-icon').classList.add('svg-fallback');">
                                <!-- Font Awesome fallback -->
                                <i class="fas fa-user fa-icon" style="font-size: 20px; color: #8F8686;"></i>
                            </div>
                            <input type="email" 
                                   class="form-control-username" 
                                   id="email" 
                                   name="email" 
                                   placeholder="User Name" 
                                   value="{{ old('email') }}"
                                   required 
                                   autofocus>
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="form-group-password">
                        <div class="input-wrapper">
                            <div class="input-icon" id="password-icon">
                                <!-- Try SVG first -->
                                <img src="{{ asset('images/lock.svg') }}" 
                                     class="svg-icon" 
                                     alt="lock icon"
                                     style="width: 24px; height: 24px;"
                                     onerror="this.style.display='none'; document.getElementById('password-icon').classList.add('svg-fallback');">
                                <!-- Font Awesome fallback -->
                                <i class="fas fa-lock fa-icon" style="font-size: 20px; color: #8F8686;"></i>
                            </div>
                            <input type="password" 
                                   class="form-control-password" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Password"
                                   required>
                        </div>
                    </div>

                    <!-- Login Button -->
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
        // Check if SVG images exist and handle fallback
        document.addEventListener('DOMContentLoaded', function() {
            // Test if admin.svg exists
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
            
            // Test if lock.svg exists
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
        });
    </script>
</body>
</html>