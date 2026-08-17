<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('images/trucking-logo.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ភ្លេចពាក្យសម្ងាត់ | LS Trucking Service</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Kantumruy+Pro:wght@400;500;600&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/forgot-password.css') }}">
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
                        <i class="fas fa-check-circle"></i>
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
