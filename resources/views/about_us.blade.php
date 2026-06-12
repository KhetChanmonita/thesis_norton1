<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
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
        <span class="au-hero-tag">អំពីយើង</span>
        <h1>បទពិសោធន៍</h1>
        <div class="au-hero-line"></div>
        <p>LS Trucking Service ជាក្រុមហ៊ុនដឹកជញ្ជូនដែលមានបទពិសោធន៍ជាង ៩ ឆ្នាំ ក្នុងការដឹកជញ្ជូនទំនិញពីកំពង់ផែ ទៅដល់គ្រប់ ២៥ ខេត្ត-រាជធានីទូទាំងប្រទេស ។ យើងប្រើប្រាស់បច្ចេកវិទ្យាទំនើប ដើម្បីផ្តល់សេវាកម្មដ៏ល្អ ទំនួលខុសត្រូវ និងទំនុកចត្ត ដល់អតិថិជនគ្រប់រូប ។</p>

        <a href="{{ route('contact') }}" class="au-hero-btn">
            <i class="fas fa-arrow-right"></i> ទំនាក់ទំនងយើង
        </a>
    </div>
    <div class="au-hero-right">
        <img src="{{ asset('images/foraboutus.png') }}" alt="LS Trucking Service">
    </div>
</section>

{{-- ══ STATS BAR ══ --}}
<div class="au-stats-wrap">
    <div class="au-stats-bar">
        <div class="au-stat-item">
            <div class="au-stat-icon"><i class="fas fa-truck"></i></div>
            <div class="au-stat-body">
                <span class="au-stat-num">10+</span>
                <span class="au-stat-title">ឡានរថយន្តធំ</span>
                <span class="au-stat-sub">បទពិសោធន៍ដឹកជញ្ជូន</span>
            </div>
        </div>
        <div class="au-stat-sep"></div>
        <div class="au-stat-item">
            <div class="au-stat-icon"><i class="fas fa-users"></i></div>
            <div class="au-stat-body">
                <span class="au-stat-num">900+</span>
                <span class="au-stat-title">អតិថិជន</span>
                <span class="au-stat-sub">ភ្ញៀវចាត់ចែងបំណឹងសេវាកម្មមើលយើង</span>
            </div>
        </div>
        <div class="au-stat-sep"></div>
        <div class="au-stat-item">
            <div class="au-stat-icon"><i class="fas fa-map-marker-alt"></i></div>
            <div class="au-stat-body">
                <span class="au-stat-num">25+</span>
                <span class="au-stat-title">តំបន់គ្រប់ដណ្តប់</span>
                <span class="au-stat-sub">ខេត្ត និងក្រោយប្រទេស</span>
            </div>
        </div>
        <div class="au-stat-sep"></div>
        <div class="au-stat-item">
            <div class="au-stat-icon"><i class="fas fa-shield-alt"></i></div>
            <div class="au-stat-body">
                <span class="au-stat-num">100%</span>
                <span class="au-stat-title">សុវត្ថិភាព</span>
                <span class="au-stat-sub">ទំនុកបម្រុងសុវត្ថិភាព 100%</span>
            </div>
        </div>
    </div>
</div>

{{-- ══ MISSION / VISION / VALUES ══ --}}
<section class="au-mvv-section">
    <div class="au-container">
        <div class="au-mvv-head">
            <h2>មេសកកម្ម និងគុណតម្លៃ<span class="au-orange">មេស់យើង</span></h2>
            <div class="au-mvv-line"></div>
        </div>

        <div class="au-mvv-grid">
            <div class="au-mvv-card">
                <div class="au-mvv-icon-wrap"><i class="fas fa-bullseye"></i></div>
                <h3>មេសកកម្ម</h3>
                <p>ផ្តល់សេវាកម្មដឹកជញ្ជូនប្រកបដោយគុណភាព ភាពស្មោះត្រង់ និងទំនួលខុសត្រូវ ដើម្បីបំណឹងតម្រូវការអតិថិជន និងពង្រឹងសេដ្ឋកិច្ចជាតិ ។</p>
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
            <span class="au-section-tag"><i class="fas fa-history"></i> ប្រវត្តិក្រុមហ៊ុន</span>
            <h2>ដំណាក់កាល<span class="au-orange">ការអភិវឌ្ឍ</span></h2>
            <p>ចំណុចសំខាន់ៗក្នុងដំណើររបស់ LS Trucking Service</p>
        </div>

        <div class="au-timeline">
            <div class="au-tl-item au-tl-left">
                <div class="au-tl-dot"><i class="fas fa-flag"></i></div>
                <div class="au-tl-card">
                    <span class="au-tl-year">២០១៥</span>
                    <h4>ការបង្កើតក្រុមហ៊ុន</h4>
                    <p>LS Trucking Service ចាប់ផ្តើមប្រតិបត្តិការ ជាមួយរថយន្ត ៣ គ្រឿង និងបុគ្គលិក ១០ នាក់ ពីកំពង់ផែព្រះសីហនុ។</p>
                </div>
            </div>
            <div class="au-tl-item au-tl-right">
                <div class="au-tl-dot"><i class="fas fa-truck"></i></div>
                <div class="au-tl-card">
                    <span class="au-tl-year">២០១៧</span>
                    <h4>ពង្រីករថយន្ត</h4>
                    <p>ពង្រីករថយន្តដល់ ២០ គ្រឿង និងចាប់ផ្តើមដំណើរការពីកំពង់ផែភ្នំពេញ ។</p>
                </div>
            </div>
            <div class="au-tl-item au-tl-left">
                <div class="au-tl-dot"><i class="fas fa-map-marked-alt"></i></div>
                <div class="au-tl-card">
                    <span class="au-tl-year">២០១៩</span>
                    <h4>គ្របដណ្តប់ ២៥ ខេត្ត</h4>
                    <p>LS Trucking ក្លាយជាក្រុមហ៊ុនដំបូងគេដែលអាចដឹកជញ្ជូនទំនិញទៅគ្រប់ ២៥ ខេត្ត-រាជធានី។</p>
                </div>
            </div>
            <div class="au-tl-item au-tl-right">
                <div class="au-tl-dot"><i class="fas fa-laptop"></i></div>
                <div class="au-tl-card">
                    <span class="au-tl-year">២០២២</span>
                    <h4>ប្រព័ន្ធឌីជីថល</h4>
                    <p>បើកដំណើរការប្រព័ន្ធគ្រប់គ្រងការកក់អនឡាញ ដើម្បីបង្កើនភាពងាយស្រួលដល់អតិថិជន។</p>
                </div>
            </div>
            <div class="au-tl-item au-tl-left">
                <div class="au-tl-dot au-tl-dot-active"><i class="fas fa-star"></i></div>
                <div class="au-tl-card au-tl-card-active">
                    <span class="au-tl-year">២០២៥</span>
                    <h4>ឈានមុខគេ</h4>
                    <p>LS Trucking Service ឈានដល់ ៥០+ រថយន្ត ១០០+ បុគ្គលិក ជាក្រុមហ៊ុនដឹកជញ្ជូនល្បីបំផុតក្នុងប្រទេស។</p>
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
                <p>អតិថិជនជាង ១,០០០ រូបបានផ្តល់ការទុកចិត្ត ដោយមានប្រវត្តិ ៩ ឆ្នាំ</p>
            </div>
        </div>
    </div>
</section>

{{-- ══ TEAM ══ --}}
<section class="au-team-section">
    <div class="au-container">
        <div class="au-section-head">
            <span class="au-section-tag"><i class="fas fa-users"></i> ក្រុមការងារ</span>
            <h2>ក្រុម<span class="au-orange">ដឹកនាំ</span>របស់យើង</h2>
            <p>អ្នកជំនាញដែលនាំមកនូវបទពិសោធន៍ និងទស្សនវិស័យ</p>
        </div>
        <div class="au-team-grid">
            <div class="au-team-card">
                <div class="au-team-img">
                    <img src="{{ asset('images/choose-us.jpg') }}" alt="CEO">
                    <div class="au-team-overlay">
                        <div class="au-team-socials">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-telegram-plane"></i></a>
                        </div>
                    </div>
                </div>
                <div class="au-team-info">
                    <h4>កញ្ញា ខេត្ត ចាន់មូនីតា</h4>
                    <span class="au-team-role">នាយកប្រតិបត្តិ (CEO)</span>
                    <p>ជំនាញផ្នែកគ្រប់គ្រង និងយុទ្ធសាស្ត្រអាជីវកម្ម ជាង ១០ ឆ្នាំ</p>
                </div>
            </div>
            <div class="au-team-card">
                <div class="au-team-img">
                    <img src="{{ asset('images/import-pp.png') }}" alt="COO">
                    <div class="au-team-overlay">
                        <div class="au-team-socials">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-telegram-plane"></i></a>
                        </div>
                    </div>
                </div>
                <div class="au-team-info">
                    <h4>លោក សុខកាយ សុបញ្ញា</h4>
                    <span class="au-team-role">អគ្គនាយកប្រតិបត្តិ (COO)</span>
                    <p>ជំនាញផ្នែកប្រតិបត្តិការ និងការគ្រប់គ្រងខ្សែច្រវ៉ាក់ផ្គត់ផ្គង់</p>
                </div>
            </div>
            <div class="au-team-card">
                <div class="au-team-img">
                    <img src="{{ asset('images/import-shv.png') }}" alt="Operations">
                    <div class="au-team-overlay">
                        <div class="au-team-socials">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-telegram-plane"></i></a>
                        </div>
                    </div>
                </div>
                <div class="au-team-info">
                    <h4>កញ្ញា ហុន លីណា</h4>
                    <span class="au-team-role">នាយកប្រតិបត្តិការ</span>
                    <p>ជំនាញផ្នែកប្រតិបត្តិការ និងការសម្របសម្រួលក្រុមមន្ត្រី</p>
                </div>
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
            <h2>ត្រៀមខ្លួនជាមួយ<br><span style="color:#ffb347;">ដៃគូដឹកជញ្ជូន</span>របស់អ្នក?</h2>
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
