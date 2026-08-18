<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('images/trucking-logo.png') }}">
    <title>ទំនាក់ទំនង | LS Trucking Service</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;500;600;700&family=Montserrat:wght@600;700;800;900&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/contact.css') }}">
</head>
<body>
@include('partials.header')

{{-- Hero Banner --}}
<section class="contact-hero">
    <div class="contact-hero-overlay"></div>
    <div class="contact-hero-body">
        <div class="contact-hero-badge"><i class="fas fa-comment-dots"></i> យើងស្ថិតនៅទីនេះ ២៤/៧</div>
        <h1>ទំនាក់ <span class="contact-hero-hl">ទំនងយើង</span></h1>
        <p>មានសំណួរអំពីតម្លៃ ការកក់ ឬសេវាកម្ម? ក្រុមការងាររបស់យើងរួចរាល់ជានិច្ច</p>
    </div>
    <div class="contact-hero-features">
        <div class="chf-item"><i class="fas fa-globe"></i><span>ដឹកជញ្ជូនអន្តរជាតិ</span></div>
        <div class="chf-sep"></div>
        <div class="chf-item"><i class="fas fa-truck"></i><span>ដឹកជញ្ជូនក្នុងស្រុក</span></div>
        <div class="chf-sep"></div>
        <div class="chf-item"><i class="fas fa-box"></i><span>ផ្ទុក និងរួម</span></div>
        <div class="chf-sep"></div>
        <div class="chf-item"><i class="fas fa-shield-alt"></i><span>សុវត្ថិភាព និងទំនុកចិត្ត</span></div>
    </div>
</section>

{{-- Main content --}}
<section class="contact-section">
    <div class="contact-container">

        {{-- Left: image card --}}
        <div class="contact-image-card">
            <div class="contact-image-overlay"></div>
            <div class="contact-image-content">
                <h2>ទាក់ទងយើង</h2>
                <p>មានសំណួរអំពីតម្លៃ ការកក់ ឬសេវាកម្ម?<br>ក្រុមការងាររបស់យើងរួចរាល់ជានិច្ច</p>
                <div class="cic-features">
                    <div class="cic-feature">
                        <div class="cic-icon"><i class="fas fa-globe"></i></div>
                        <div class="cic-text">
                            <span class="cic-km">ការដឹកជញ្ជូនអន្តរជាតិ</span>
                            <span class="cic-en">International Freight</span>
                        </div>
                    </div>
                    <div class="cic-feature">
                        <div class="cic-icon"><i class="fas fa-truck"></i></div>
                        <div class="cic-text">
                            <span class="cic-km">ការដឹកជញ្ជូនក្នុងស្រុក</span>
                            <span class="cic-en">Road Transportation</span>
                        </div>
                    </div>
                    <div class="cic-feature">
                        <div class="cic-icon"><i class="fas fa-ship"></i></div>
                        <div class="cic-text">
                            <span class="cic-km">ការដឹកជញ្ជូនសមុទ្រ</span>
                            <span class="cic-en">Ocean Freight</span>
                        </div>
                    </div>
                    <div class="cic-feature">
                        <div class="cic-icon"><i class="fas fa-warehouse"></i></div>
                        <div class="cic-text">
                            <span class="cic-km">ផ្ទុក និងសោហ៊ុយ</span>
                            <span class="cic-en">Warehousing & 3PL</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Middle: form --}}
        <div class="contact-form-card">
            <div class="contact-form-head">
                <i class="fas fa-paper-plane"></i>
                <div>
                    <h2>ផ្ញើសំណួររបស់អ្នក</h2>
                    <p>យើងនឹងឆ្លើយតបក្នុងរយៈពេល ២៤ ម៉ោង</p>
                </div>
            </div>

            @if(session('contact_success'))
                <div class="contact-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('contact_success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="contact-error">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('contact.store') }}" class="contact-form">
                @csrf

                <div class="cf-row">
                    <div class="cf-group">
                        <label><i class="fas fa-user"></i> ឈ្មោះពេញ</label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}"
                               placeholder="ឧ. សុខ សុភា" required>
                    </div>
                    <div class="cf-group">
                        <label><i class="fas fa-phone-alt"></i> លេខទូរស័ព្ទ</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               placeholder="ឧ. 012 345 678" required>
                    </div>
                </div>

                <div class="cf-row">
                    <div class="cf-group">
                        <label><i class="fas fa-envelope"></i> អុីម៉ែល</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               placeholder="example@email.com">
                    </div>
                    <div class="cf-group">
                        <label><i class="fas fa-building"></i> ឈ្មោះក្រុមហ៊ុន</label>
                        <input type="text" name="company_name" value="{{ old('company_name') }}"
                               placeholder="ជាជម្រើស">
                    </div>
                </div>

                <div class="cf-group">
                    <label><i class="fas fa-tag"></i> ប្រភេទសំណួរ</label>
                    <select name="inquiry_type" required>
                        <option value="">-- ជ្រើសរើសប្រភេទ --</option>
                        <option value="price"       {{ old('inquiry_type')=='price'       ? 'selected':'' }}>សំណួរអំពីតម្លៃ</option>
                        <option value="import"      {{ old('inquiry_type')=='import'      ? 'selected':'' }}>ការដឹកទំនិញចូល (Import)</option>
                        <option value="export"      {{ old('inquiry_type')=='export'      ? 'selected':'' }}>ការដឹកទំនិញចេញ (Export)</option>
                        <option value="partnership" {{ old('inquiry_type')=='partnership' ? 'selected':'' }}>ភ្ជាប់កិច្ចសហការ</option>
                        <option value="other"       {{ old('inquiry_type')=='other'       ? 'selected':'' }}>សំណួរផ្សេងៗ</option>
                    </select>
                </div>

                <div class="cf-group">
                    <label><i class="fas fa-comment-alt"></i> សារ</label>
                    <textarea name="message" rows="5"
                              placeholder="សូមសរសេរសំណួរ ឬតម្រូវការរបស់អ្នក..." required>{{ old('message') }}</textarea>
                </div>

                <button type="submit" class="cf-submit">
                    <i class="fas fa-paper-plane"></i> ផ្ញើសំណួរ
                </button>
            </form>
        </div>

        {{-- Right: info --}}
        <div class="contact-info-col">

            <div class="contact-info-card">
                <h3><i class="fas fa-address-card"></i> ព័ត៌មានទំនាក់ទំនង</h3>
                <div class="ci-list">
                    <div class="ci-item">
                        <div class="ci-icon"><i class="fas fa-phone-alt"></i></div>
                        <div>
                            <div class="ci-label">ទូរស័ព្ទ</div>
                            <div class="ci-val">096 267 9042</div>
                        </div>
                    </div>
                    <div class="ci-item">
                        <div class="ci-icon"><i class="fab fa-telegram-plane"></i></div>
                        <div>
                            <div class="ci-label">Telegram</div>
                            <div class="ci-val">@KCmonita11</div>
                        </div>
                    </div>
                    <div class="ci-item">
                        <div class="ci-icon"><i class="fas fa-envelope"></i></div>
                        <div>
                            <div class="ci-label">អុីម៉ែល</div>
                            <div class="ci-val">khetchanmonita3@gmail.com</div>
                        </div>
                    </div>
                    <div class="ci-item">
                        <div class="ci-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div>
                            <div class="ci-label">អាសយដ្ឋាន</div>
                            <div class="ci-val">ផ្លូវបឹងទទឹងថ្ងៃ២ ផ្ទះលេខ ៩៨៣ ខណ្ឌជ្រោយចង្វា រាជធានីភ្នំពេញ</div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="contact-social-card">
                <h3>តាមដានយើង</h3>
                <div class="cs-links">
                    <a href="https://t.me/+855962679042" target="_blank" class="cs-btn telegram"><i class="fab fa-telegram-plane"></i> Telegram</a>
                    <a href="https://www.tiktok.com/@khetchanmonita" target="_blank" class="cs-btn tiktok"><i class="fab fa-tiktok"></i> TikTok</a>
                </div>
            </div>

        </div>
    </div>
</section>

@include('partials.footer')

<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.querySelector('.menu-toggle');
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            const nav = document.querySelector('.nav-menu');
            if (nav) nav.classList.toggle('show');
        });
    }
});
</script>

</body>
</html>