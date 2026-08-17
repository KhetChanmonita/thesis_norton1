<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('images/trucking-logo.png') }}">
    <title>អំពីយើង | LS Trucking Service</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;500;600;700&family=Montserrat:wght@600;700;800;900&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/about_us.css') }}">
</head>
<body>
@include('partials.header')

{{-- ══ HERO ══ --}}
<section class="au-hero">
    <div class="au-hero-left">
        <h1>ជាដៃគូទុកចិត្ត<br><span class="au-orange">ក្នុងការដឹកជញ្ជូនគ្រប់ទីកន្លែង</span></h1>
        <div class="au-hero-line"></div>
        <p>LS Trucking Service ជាក្រុមហ៊ុនដឹកជញ្ជូនដែលមានបទពិសោធន៍ជាង ៣ ឆ្នាំ ក្នុងការដឹកជញ្ជូនទំនិញពីកំពង់ផែ ទៅដល់គ្រប់ ២៥ ខេត្ត-រាជធានីទូទាំងប្រទេស ។ យើងប្រើប្រាស់បច្ចេកវិទ្យាទំនើប ដើម្បីផ្តល់សេវាកម្មដ៏ល្អ ទំនួលខុសត្រូវ និងទំនុកចត្ត ដល់អតិថិជនគ្រប់រូប ។</p>

        <a href="{{ route('contact') }}" class="au-hero-btn">
            <i class="fas fa-arrow-right"></i> ទំនាក់ទំនងយើង
        </a>
    </div>
    <div class="au-hero-right">
        <img src="{{ asset('images/foraboutus.png') }}" alt="LS Trucking Service">
    </div>
</section>

{{-- ══ FEATURE BAR ══ --}}
<div class="au-stats-wrap">
    <div class="au-feature-bar">
        <div class="au-feature-item">
            <div class="au-feature-icon"><i class="fas fa-shield-alt"></i></div>
            <div>
                <h4 class="au-feature-title">សុវត្ថិភាពជាទម្លាប់</h4>
                <p class="au-feature-desc">យើងធានាសុវត្ថិភាពទំនិញរបស់អ្នកគ្រប់ដំណាក់កាល តាមស្តង់ដារខ្ពស់បំផុត ។</p>
            </div>
        </div>
        <div class="au-feature-sep"></div>
        <div class="au-feature-item">
            <div class="au-feature-icon"><i class="fas fa-clock"></i></div>
            <div>
                <h4 class="au-feature-title">ដឹកជញ្ជូនទាន់ពេលវេលា</h4>
                <p class="au-feature-desc">ការដឹកជញ្ជូនរបស់យើងតែងតែទាន់ពេលវេលា ស្របតាមកាលវិភាគដែលបានកំណត់ ។</p>
            </div>
        </div>
        <div class="au-feature-sep"></div>
        <div class="au-feature-item">
            <div class="au-feature-icon"><i class="fas fa-map-marker-alt"></i></div>
            <div>
                <h4 class="au-feature-title">គ្របដណ្តប់ទូទាំងប្រទេស</h4>
                <p class="au-feature-desc">បណ្តាញសេវាកម្មរបស់យើងគ្របដណ្តប់គ្រប់ ២៥ ខេត្ត-រាជធានីទូទាំងប្រទេស ។</p>
            </div>
        </div>
        <div class="au-feature-sep"></div>
        <div class="au-feature-item">
            <div class="au-feature-icon"><i class="fas fa-headset"></i></div>
            <div>
                <h4 class="au-feature-title">សេវាជំនួយទាន់ពេលវេលា 24/7</h4>
                <p class="au-feature-desc">ក្រុមការងាររបស់យើងត្រៀមខ្លួនជួយអ្នកគ្រប់ពេលវេលា និងគ្រប់ស្ថានភាព ។</p>
            </div>
        </div>
    </div>
</div>

{{-- ══ ABOUT COMPANY ══ --}}
<section class="au-company-section">
    <div class="au-container">
        <div class="au-company-grid">
            <div class="au-company-left">
                <div class="au-company-tag">
                    <span class="au-company-dash"></span>
                    <h2>អំពីក្រុមហ៊ុន</h2>
                </div>
                <p>LS Trucking Service ជាក្រុមហ៊ុនដឹកជញ្ជូនទំនិញ ដែលបង្កើតឡើងដោយក្រុមការងារដែលមានបទពិសោធន៍ និងជំនាញវិជ្ជាជីវៈ ។ យើងប្តេជ្ញាផ្តល់សេវាកម្មប្រកបដោយគុណភាព សុវត្ថិភាព និងភាពជឿទុកចិត្ត ដើម្បីបំពេញតម្រូវការអាជីវកម្មរបស់អតិថិជនគ្រប់ប្រភេទ ។</p>
            </div>
            <div class="au-company-stats">
                <div class="au-company-stat">
                    <div class="au-company-stat-icon"><i class="fas fa-user-tie"></i></div>
                    <span class="au-company-stat-num">10+</span>
                    <span class="au-company-stat-label">បុគ្គលិកជំនាញ</span>
                </div>
                <div class="au-company-stat">
                    <div class="au-company-stat-icon"><i class="fas fa-users"></i></div>
                    <span class="au-company-stat-num">៣ ឆ្នាំ</span>
                    <span class="au-company-stat-label">បទពិសោធន៍</span>
                </div>
                <div class="au-company-stat">
                    <div class="au-company-stat-icon"><i class="fas fa-truck"></i></div>
                    <span class="au-company-stat-num">1,000+</span>
                    <span class="au-company-stat-label">ការដឹកជញ្ជូនជោគជ័យ</span>
                </div>
                <div class="au-company-stat">
                    <div class="au-company-stat-icon"><i class="fas fa-award"></i></div>
                    <span class="au-company-stat-num">98%</span>
                    <span class="au-company-stat-label">ការពេញចិត្តរបស់អតិថិជន</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ MISSION / VISION / VALUES ══ --}}
<section class="au-mvv-section">
    <div class="au-container">
        <div class="au-mvv-head">
            <h2>បេសកម្ម និងគុណតម្លៃ<span class="au-orange">របស់យើង</span></h2>
            <div class="au-mvv-line"></div>
        </div>

        <div class="au-mvv-grid">
            <div class="au-mvv-card">
                <div class="au-mvv-icon-wrap"><i class="fas fa-bullseye"></i></div>
                <h3>បេសកម្ម</h3>
                <p>ផ្តល់សេវាកម្មដឹកជញ្ជូនប្រកបដោយគុណភាព ភាពស្មោះត្រង់ និងទំនួលខុសត្រូវ ដើម្បីបំពេញតម្រូវការអតិថិជន និងពង្រឹងសេដ្ឋកិច្ចជាតិ ។</p>
            </div>
            <div class="au-mvv-card">
                <div class="au-mvv-icon-wrap"><i class="fas fa-eye"></i></div>
                <h3>ចក្ខុវិស័យ</h3>
                <p>ក្លាយជាក្រុមហ៊ុនដឹកជញ្ជូនឈានមុខគេ ដែលទទួលបានការទុកចិត្តបំផុតនៅក្នុងប្រទេសកម្ពុជា ដោយការពង្រឹងប្រព័ន្ធ និងពង្រីកសេវាកម្មឆ្ពោះទៅកាន់ទីផ្សារអន្តរជាតិ ។</p>
            </div>
            <div class="au-mvv-card">
                <div class="au-mvv-icon-wrap"><i class="fas fa-gem"></i></div>
                <h3>គុណតម្លៃ</h3>
                <ul class="au-mvv-list">
                    <li><i class="fas fa-check"></i> សុវត្ថិភាពជាមុនគេ</li>
                    <li><i class="fas fa-check"></i> ទំនុកចិត្ត និងការទទួលខុសត្រូវ</li>
                    <li><i class="fas fa-check"></i> គុណភាព និងប្រសិទ្ធភាពការងារ</li>
                    <li><i class="fas fa-check"></i> ការកែទម្រង់ និងការច្នៃប្រឌិតថ្មី</li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- ══ TIMELINE ══ --}}
<section class="au-timeline-section">
    <div class="au-container">
        <div class="au-section-head">
            <h2>ដំណាក់កាល<span class="au-orange">ការអភិវឌ្ឍ</span></h2>
            <p>ចំណុចសំខាន់ៗក្នុងដំណើររបស់ LS Trucking Service</p>
        </div>

        <div class="au-timeline">
            <div class="au-tl-item au-tl-left">
                <div class="au-tl-dot"><i class="fas fa-flag"></i></div>
                <div class="au-tl-card">
                    <span class="au-tl-year">២០២២</span>
                    <h4>ការបង្កើតក្រុមហ៊ុន</h4>
                    <p>LS Trucking Service ចាប់ផ្តើមប្រតិបត្តិការ ជាមួយរថយន្ត ១ គ្រឿង និងបុគ្គលិក ១ នាក់ និងបម្រើការនៅកំពុងផែភ្នំព្រះសីហនុ ។</p>
                </div>
            </div>
            <div class="au-tl-item au-tl-right">
                <div class="au-tl-dot"><i class="fas fa-truck"></i></div>
                <div class="au-tl-card">
                    <span class="au-tl-year">២០២៤</span>
                    <h4>ពង្រីករថយន្ត</h4>
                    <p>ពង្រីករថយន្តដល់ ១០ គ្រឿង និងចាប់ផ្តើមដំណើរការពីកំពង់ផែភ្នំពេញ ។</p>
                </div>
            </div>
            <div class="au-tl-item au-tl-left">
                <div class="au-tl-dot"><i class="fas fa-map-marked-alt"></i></div>
                <div class="au-tl-card">
                    <span class="au-tl-year">២០២៦</span>
                    <h4>គ្របដណ្តប់ ២៥ ខេត្ត</h4>
                    <p>LS Truckingពង្រីករថយន្តដល់ ១៨ គ្រឿង ក្លាយជាក្រុមហ៊ុនដែលអាចដឹកជញ្ជូនទំនិញទៅគ្រប់ ២៥ ខេត្ត-រាជធានី។</p>
                </div>
            </div>
            <div class="au-tl-item au-tl-right">
                <div class="au-tl-dot"><i class="fas fa-laptop"></i></div>
                <div class="au-tl-card">
                    <span class="au-tl-year">២០២៦</span>
                    <h4>ប្រព័ន្ធឌីជីថល</h4>
                    <p>បើកដំណើរការប្រព័ន្ធគ្រប់គ្រងការកក់អនឡាញ ដើម្បីបង្កើនភាពងាយស្រួលដល់អតិថិជន។</p>
                </div>
            </div>
            <div class="au-tl-item au-tl-left">
                <div class="au-tl-dot au-tl-dot-active"><i class="fas fa-star"></i></div>
                <div class="au-tl-card au-tl-card-active">
                    <span class="au-tl-year">នៅឆ្នាំខាងមុខ​ ២០២៧</span>
                    <h4>ឈានមុខគេ</h4>
                    <p>LS Trucking Service អាចឈានដល់ ៣០+ រថយន្ត ៣០+ បុគ្គលិក ជាក្រុមហ៊ុនដឹកជញ្ជូនល្បីបំផុតក្នុងប្រទេស។</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ WHY CHOOSE US ══ --}}
<section class="au-why-section">
    <div class="au-container">
        <div class="au-section-head">
            <span class="au-section-tag"><i class="fas fa-trophy"></i> ហេតុអ្វីជ្រើសយើង</span>
            <h2>អ្វីដែលធ្វើឱ្យ<span class="au-orange">យើងខុសគេ</span></h2>
            <p>គុណភាពសេវាកម្ម និងការប្រកាន់ខ្ជាប់ដែលយើងធ្វើបានគ្រប់ពេល</p>
        </div>
        <div class="au-why-grid">
            <div class="au-why-card">
                <div class="au-why-icon"><i class="fas fa-shield-alt"></i></div>
                <h4>សុវត្ថិភាពខ្ពស់</h4>
                <p>រថយន្តបានត្រួតពិនិត្យ និងថែទាំជាប្រចាំ ដើម្បីធានាសុវត្ថិភាពផ្លូវវាររបស់ទំនិញ</p>
            </div>
            <div class="au-why-card">
                <div class="au-why-icon"><i class="fas fa-clock"></i></div>
                <h4>ផ្តល់សេវា ២៤/៧</h4>
                <p>ក្រុមការងាររបស់យើងសកម្មគ្រប់ម៉ោង ដើម្បីឆ្លើយតបទៅនឹងតម្រូវការរបស់អ្នក</p>
            </div>
            <div class="au-why-card">
                <div class="au-why-icon"><i class="fas fa-map-marked-alt"></i></div>
                <h4>គ្របដណ្តប់ ២៥ ខេត្ត</h4>
                <p>ដឹកជញ្ជូនទំនិញពីច្រក ២ ទៅគ្រប់ ២៥ ខេត្ត-រាជធានីទូទាំងប្រទេស</p>
            </div>
            <div class="au-why-card">
                <div class="au-why-icon"><i class="fas fa-dollar-sign"></i></div>
                <h4>តម្លៃសមរម្យ</h4>
                <p>ប្រព័ន្ធគណនាតម្លៃតម្លាភ ស្មើភាព ដោយមិនមានការលួចបន្ថែមប្រាក់ណាមួយ</p>
            </div>
            <div class="au-why-card">
                <div class="au-why-icon"><i class="fas fa-mobile-alt"></i></div>
                <h4>តាមដានស្ថានភាព</h4>
                <p>អតិថិជនអាចតាមដានស្ថានភាពការដឹករបស់ខ្លួនបានតាមរយៈប្រព័ន្ធអនឡាញ</p>
            </div>
            <div class="au-why-card">
                <div class="au-why-icon"><i class="fas fa-handshake"></i></div>
                <h4>ការទំនុកចិត្ត</h4>
                <p>អតិថិជនជាង ១,០០០ រូបបានផ្តល់ការទុកចិត្ត ដោយមានប្រវត្តិ 3 ឆ្នាំ</p>
            </div>
        </div>
    </div>
</section>

{{-- ══ TEAM ══ --}}
<section class="au-team-section">
    <div class="au-container">
        <div class="au-section-head">
            <h2>ក្រុម<span class="au-orange">ដឹកនាំ</span>របស់យើង</h2>
            <p>អ្នកជំនាញដែលនាំមកនូវបទពិសោធន៍ និងទស្សនវិស័យ</p>
        </div>
        <div class="au-team-grid">
            <div class="au-team-card">
                <div class="au-team-img">
                    <img src="{{ asset('images/boss/leangsry.png') }}" alt="CEO">
                    <div class="au-team-overlay">
                        <div class="au-team-socials">
                            
                        </div>
                    </div>
                </div>
                <div class="au-team-info">
                    <h4>ស្រេង លាងស្រុី</h4>
                    <span class="au-team-role">នាយកប្រតិបត្តិការ</span>
                    <p>ជំនាញពាណិជ្ជកម្មអន្តរជាតិ និងយុទ្ធសាស្ត្រអាជីវកម្ម ជាង ១០ ឆ្នាំ</p>
                </div>
            </div>
            <div class="au-team-card">  
                <div class="au-team-img">
                    <img src="{{ asset('images/boss/socheata.png') }}" alt="COO">
                    <div class="au-team-overlay">
                        <div class="au-team-socials">
                           
                        </div>
                    </div>
                </div>
                <div class="au-team-info">
                    <h4>ស្រេង សុជាតា</h4>
                    <span class="au-team-role">អគ្គនាយករងប្រតិបត្តិការ</span>
                    <p>ជំនាញផ្នែកប្រតិបត្តិការ និងជើងសារគយ​ ជាង ១៥​ ឆ្នាំ</p>
                </div>
            </div>
            <div class="au-team-card">
                <div class="au-team-img">
                    <img src="{{ asset('images/boss/sovann.png') }}" alt="Operations">
                    <div class="au-team-overlay">
                        <div class="au-team-socials">
                           
                        </div>
                    </div>
                </div>
                <div class="au-team-info">
                    <h4>ពេជ្រធុន សុវណ្ណ</h4>
                    <span class="au-team-role">អ្នកគ្រប់គ្រងប្រតិបត្តិការទូទៅ</span>
                    <p>ជំនាញផ្នែកប្រតិបត្តិការ និងគ្រប់គ្រងពាណិជ្ជកម្ម ជាង ៨ ឆ្នាំ</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ STAFF ══ --}}
<section class="au-staff-section">
    <div class="au-container">
        <div class="au-section-head">
            <h2>ក្រុមការងារ<span class="au-orange">របស់យើង</span></h2>
            <p>ក្រុមបុគ្គលិកដែលឧស្សាហ៍ព្យាយាម និងសកម្មក្នុងការផ្តល់សេវាកម្ម</p>
        </div>
        <div class="au-staff-grid">
            <div class="au-staff-card">
                <div class="au-staff-img-wrap">
                    <img src="{{ asset('images/staff/nita.png') }}" alt="Staff 1">
                    <div class="au-staff-icon"><i class="fas fa-user"></i></div>
                </div>
                <h4 class="au-staff-name">ខេត្ត ចាន់មូនីតា</h4>
                <span class="au-staff-role">គណនីករ</span>
            </div>
            <div class="au-staff-card">
                <div class="au-staff-img-wrap">
                    <img src="{{ asset('images/staff/lina.png') }}" alt="Staff 2">
                    <div class="au-staff-icon"><i class="fas fa-user"></i></div>
                </div>
                <h4 class="au-staff-name">ហុន លីណា</h4>
                <span class="au-staff-role">ផ្នែកប្រតិបត្តិការ</span>
            </div>
            <div class="au-staff-card">
                <div class="au-staff-img-wrap">
                    <img src="{{ asset('images/staff/panha.png') }}" alt="Staff 3">
                    <div class="au-staff-icon"><i class="fas fa-user"></i></div>
                </div>
                <h4 class="au-staff-name">ឈុន បញ្ញា</h4>
                <span class="au-staff-role">អ្នកដឹកជញ្ជូនឯកសារ</span>
            </div>
             <div class="au-staff-card">
                <div class="au-staff-img-wrap">
                    <img src="{{ asset('images/staff/lin.jpg') }}" alt="Staff 3">
                    <div class="au-staff-icon"><i class="fas fa-user"></i></div>
                </div>
                <h4 class="au-staff-name">យ៉ាន ដាលីន</h4>
                <span class="au-staff-role">អ្នករៀបចំឯកសារ</span>
            </div>
           
        </div>
    </div>
</section>

{{-- ══ CTA ══ --}}
<section class="au-cta-section">
    <div class="au-cta-shape c1"></div>
    <div class="au-cta-shape c2"></div>
    <div class="au-container au-cta-body">
        <div class="au-cta-left">
            <h2>ត្រៀមខ្លួនជាមួយ<br><span class="au-cta-highlight">ដៃគូដឹកជញ្ជូន</span>របស់អ្នក?</h2>
            <p>ទំនាក់ទំនងមកយើងថ្ងៃនេះ ឬប្រើប្រាស់ប្រព័ន្ធគណនាតម្លៃរបស់យើងដើម្បីចាប់ផ្តើម</p>
        </div>
        <div class="au-cta-right">
            <a href="{{ route('contact') }}" class="au-cta-btn-primary"><i class="fas fa-phone-alt"></i> ទំនាក់ទំនងយើង</a>
            <a href="{{ route('price') }}"   class="au-cta-btn-outline"><i class="fas fa-calculator"></i> គណនាតម្លៃ</a>
        </div>
    </div>
</section>

@include('partials.footer')

<script>
document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.querySelector('.menu-toggle');
    if (menuToggle) menuToggle.addEventListener('click', () => document.querySelector('.nav-menu').classList.toggle('show'));

    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', function (e) {
            const el = document.querySelector(this.getAttribute('href'));
            if (el) { e.preventDefault(); window.scrollTo({ top: el.offsetTop - 80, behavior: 'smooth' }); }
        });
    });

    const observer = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('au-tl-visible'); });
    }, { threshold: 0.15 });
    document.querySelectorAll('.au-tl-item').forEach(el => observer.observe(el));
});
</script>
</body>
</html>
