<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ភ្លេចពាក្យសម្ងាត់ | LS Trucking Service</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Kantumruy+Pro:wght@400;500;600&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            background: linear-gradient(135deg, #fff5e6 0%, #ffe0b2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Kantumruy Pro', 'Poppins', sans-serif;
            color: #1E293B;
        }
        .page-main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            margin-top: 80px;
        }
        .card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 60px rgba(255,107,0,0.15), 0 10px 30px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 480px;
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(135deg, #FF6B00, #FF8A3D);
            padding: 36px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .card-header::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 140px; height: 140px;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
        }
        .card-header::after {
            content: '';
            position: absolute;
            bottom: -50px; left: -30px;
            width: 160px; height: 160px;
            background: rgba(255,255,255,0.06);
            border-radius: 50%;
        }
        .header-icon {
            width: 64px; height: 64px;
            background: rgba(255,255,255,0.2);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
            margin-bottom: 16px;
            position: relative;
            z-index: 1;
        }
        .card-header h1 {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 6px;
            position: relative;
            z-index: 1;
        }
        .card-header p {
            color: rgba(255,255,255,0.85);
            font-size: 0.88rem;
            position: relative;
            z-index: 1;
        }
        .card-body { padding: 36px 40px; }

        .alert-success {
            background: #f0fdf4;
            border: 1.5px solid #86efac;
            color: #15803d;
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 0.9rem;
            line-height: 1.6;
        }
        .alert-error {
            background: #fff0f0;
            border: 1.5px solid #fca5a5;
            color: #dc2626;
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
        }

        .input-group { margin-bottom: 24px; }
        .input-group label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            font-size: 14px;
            color: #1E293B;
            margin-bottom: 8px;
        }
        .input-group label i { color: #FF6B00; }
        .input-wrapper { position: relative; }
        .input-wrapper .icon {
            position: absolute;
            left: 15px; top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 15px;
            pointer-events: none;
        }
        .input-wrapper input {
            width: 100%;
            padding: 13px 16px 13px 44px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Kantumruy Pro', 'Poppins', sans-serif;
            background: #f8fafc;
            color: #1E293B;
            transition: all 0.3s ease;
            outline: none;
        }
        .input-wrapper input:focus {
            border-color: #FF6B00;
            background: white;
            box-shadow: 0 0 0 4px rgba(255,107,0,0.08);
        }
        .input-wrapper input:focus + .icon { color: #FF6B00; }

        .submit-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #FF6B00, #FF8A3D);
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Kantumruy Pro', 'Poppins', sans-serif;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255,107,0,0.35);
        }
        .back-link {
            text-align: center;
            font-size: 14px;
            color: #64748B;
        }
        .back-link a {
            color: #FF6B00;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: color 0.3s;
        }
        .back-link a:hover { color: #E55A00; text-decoration: underline; }
    </style>
</head>
<body>
    @include('partials.header')

    <main class="page-main">
        <div class="card">
            <div class="card-header">
                <div class="header-icon">
                    <i class="fas fa-unlock-alt"></i>
                </div>
                <h1>ភ្លេចពាក្យសម្ងាត់?</h1>
                <p>បញ្ចូលអ៊ីមែលរបស់អ្នក យើងនឹងផ្ញើតំណភ្ជាប់កំណត់ពាក្យសម្ងាត់ជូន</p>
            </div>

            <div class="card-body">

                @if (session('status'))
                    <div class="alert-success">
                        <i class="fas fa-check-circle" style="flex-shrink:0;margin-top:2px"></i>
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                @if (!session('status'))
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="input-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i> អ៊ីមែល
                        </label>
                        <div class="input-wrapper">
                            <input type="email" id="email" name="email"
                                   value="{{ old('email') }}"
                                   placeholder="បញ្ចូលអ៊ីមែលរបស់អ្នក"
                                   required autofocus>
                            <span class="icon"><i class="fas fa-envelope"></i></span>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn">
                        <i class="fas fa-paper-plane"></i>
                        ផ្ញើតំណភ្ជាប់កំណត់ពាក្យសម្ងាត់
                    </button>
                </form>
                @endif

                <div class="back-link">
                    <a href="{{ route('login') }}">
                        <i class="fas fa-arrow-left"></i> ត្រឡប់ទៅចូលគណនី
                    </a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
