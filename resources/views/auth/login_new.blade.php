<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Login Akun | QBSC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="{{ asset('images/satpam128.png') }}">

    <link href="{{ asset('template/frontend/assets/css/app.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('template/frontend/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">

    <style>
        /* === BACKGROUND & BODY === */
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0b0b0c, #333436, #7dd3fc);
            background-size: 300% 300%;
            animation: gradientShift 8s ease infinite;
            font-family: "Inter", sans-serif;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* === AUTH CARD === */
        .auth-card {
            margin-top: 40px;
            margin-bottom: 40px;
            width: 420px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 20px;
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.1);
            padding: 40px 35px;
            color: #fff;
            text-align: center;
            animation: fadeUp 0.6s ease forwards;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-card img {
            width: 160px;
            background: rgba(255, 255, 255, 0.2);
            padding: 10px 30px;
            border-radius: 12px;
            margin-bottom: 25px;
        }

        h3 {
            font-weight: 700;
            color: #fff;
            margin-bottom: 6px;
        }

        p.text-muted {
            color: rgba(255, 255, 255, 0.7) !important;
            margin-bottom: 25px;
            font-size: 14px;
        }

        /* === FORM === */
        .form-label {
            text-align: left;
            display: block;
            font-weight: 600;
            color: #f0f8ff;
            margin-bottom: 6px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 10px;
            color: #fff;
            padding: 10px 14px;
            margin-bottom: 14px;
            transition: 0.3s;
        }

        .form-control:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.3);
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.3);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        small.text-danger {
            display: block;
            text-align: left;
        }

        .form-check-label {
            color: rgba(255, 255, 255, 0.8);
        }

        /* === BUTTON === */
        .btn-primary {
            background: linear-gradient(90deg, #00b4d8, #007bff);
            border: none;
            border-radius: 10px;
            padding: 12px;
            width: 100%;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            background: linear-gradient(90deg, #0096c7, #0056d2);
        }

        /* === FOOTER === */
        .auth-footer {
            margin-top: 18px;
            font-size: 14px;
        }

        .auth-footer a {
            color: #fff;
            font-weight: 600;
            text-decoration: underline;
        }

        .input-error {
            position: relative;
            top: -15px !important;
            left: -20px;
            background: red;
            padding: 2px;
            border-radius: 10px;
        }

        .wa-container {
            position: fixed;
            bottom: 28px;
            right: 28px;
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Bubble chat */
        .wa-bubble {
            background: #ffffff;
            color: #333;
            padding: 10px 14px;
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            font-size: 13px;
            line-height: 1.3;
            animation: bubbleIn 0.6s ease forwards;
        }

        .wa-bubble strong {
            display: block;
            font-weight: 700;
            color: #25D366;
        }

        /* WhatsApp Button */
        .wa-float {
            width: 58px;
            height: 58px;
            background: #25D366;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            position: relative;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.35);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .wa-float:hover {
            transform: scale(1.12);
            background: #1ebe5d;
        }

        /* Online status dot */
        .wa-status {
            position: absolute;
            bottom: 6px;
            right: 6px;
            width: 12px;
            height: 12px;
            background: #00ff6a;
            border: 2px solid #fff;
            border-radius: 50%;
            animation: pulse 1.5s infinite;
        }

        /* Animations */
        @keyframes bubbleIn {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(0, 255, 106, 0.6);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(0, 255, 106, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(0, 255, 106, 0);
            }
        }

        .btn-register-google {
            margin-top: 10px;
            background: white !important;
            color: black !important;
            padding: 0px !important;
        }

        .image-register-google {
            position: relative;
            padding: 0px !important;
            width: 30px !important;
            top: 10px;
            left: -11px;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin-top: 10px;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #ccc;
        }

        .divider span {
            padding: 0 10px;
            font-size: 14px;
            color: white;
        }
    </style>
</head>

<body>
    <div class="auth-card">
        <img src="{{ asset('images/satpam-trans.png') }}" alt="Logo QBSC">

        <h3>Login Akun</h3>

        @if (session('success'))
            <div class="alert alert-success bg-success text-white border-0">{{ session('success') }}</div>
        @endif
        @error('error')
            <div class="alert alert-danger bg-danger text-white border-0">{{ $message }}</div>
            @endif

            <form style="margin-top: 20px;" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                    @error('email')
                        <small class="text-white input-error">{{ $message }}</small>
                    @enderror
                </div>


                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                    @error('password')
                        <small class="text-white input-error">{{ $message }}</small>
                    @enderror
                </div>


                <button style="margin-top: 20px;" type="submit" class="btn btn-primary">Login</button>

            </form>

            <div class="auth-footer mt-3">
                Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
            </div>
            <div class="divider">
                <span>Atau</span>
            </div>


            <a href="{{ route('google.login') }}"><button type="button" class="btn btn-primary btn-register-google"><img class="img-fluid image-register-google"
                    src="{{ asset('images/google_icon.png') }}">Daftar Dengan
                Google</button></a>
        </div>
        <!-- WhatsApp Floating Premium -->
        <div class="wa-container">
            <div class="wa-bubble">
                <strong>Butuh bantuan?</strong>
                <span>Chat Admin QBSC</span>
            </div>

            <a href="https://wa.me/6282165174835?text=Halo%20Admin%20QBSC,%20saya%20butuh%20bantuan" target="_blank"
                class="wa-float" aria-label="Chat WhatsApp">
                <i class="mdi mdi-whatsapp"></i>
                <span class="wa-status"></span>
            </a>
        </div>


        <script src="{{ asset('template/frontend/assets/js/vendor.min.js') }}"></script>
        <script src="{{ asset('template/frontend/assets/js/app.min.js') }}"></script>
    </body>

    </html>
