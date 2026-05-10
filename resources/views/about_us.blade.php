<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>អំពីពួកយើង | Trucking System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Kantumruy+Pro:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/about_us.css') }}">
</head>
<body>
   @include('partials.header')
   
<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-overlay">
        <div class="hero-content">
            <h1>ក្រុមគម្រោងដឹកជញ្ជូន</h1>
            <p>យើងជាដៃគូដឹកជញ្ជូនដែលទុកចិត្តបាន និងមានបទពិសោធន៍ជាង 3ឆ្នាំ</p>
            <a href="#story" class="hero-btn">ស្វែងយល់បន្ថែម <i class="fas fa-arrow-down"></i></a>
        </div>
    </div>
</section>

<!-- Main Content -->
<main class="about-container">
    <!-- Our Story -->
    <section class="story-section" id="story">
        <div class="section-header">
            <h2><span class="highlight">រឿងរបស់</span> យើង</h2>
            <p>ដំណើរជីវិតនិងការអភិវឌ្ឍន៍</p>
        </div>
        
        <div class="story-content">
            <div class="story-image">
                <img src="{{ asset('images/export.png') }}" alt="Our Story">
                <!-- <div class="story-year">
                    <span>២០១៥</span>
                    <p>ឆ្នាំបង្កើត</p>
                </div> -->
            </div>
            <div class="story-text">
                <h3>ការចាប់ផ្តើមដំបូង</h3>
                <p>ក្រុមហ៊ុនយើងត្រូវបានបង្កើតឡើងនៅឆ្នាំ ២០១៥ ដោយមានគោលបំណងផ្តល់នូវសេវាកម្មដឹកជញ្ជូនដែលមានគុណភាព និងទុកចិត្តបាននៅក្នុងប្រទេសកម្ពុជា។</p>
                <p>ចាប់ពីការចាប់ផ្តើមដោយមានតែរថយន្តដឹកទំនិញ ៣គ្រឿង រហូតមកដល់ពេលបច្ចុប្បន្ននេះ យើងមានយានជំនិះជាង ៥០គ្រឿង និងបុគ្គលិកជាង ១០០នាក់។</p>
                
                <div class="achievements">
                    <div class="achievement">
                        <i class="fas fa-truck-moving"></i>
                        <div>
                            <h4>៥០+</h4>
                            <p>រថយន្តដឹកទំនិញ</p>
                        </div>
                    </div>
                    <div class="achievement">
                        <i class="fas fa-users"></i>
                        <div>
                            <h4>១០០+</h4>
                            <p>បុគ្គលិក</p>
                        </div>
                    </div>
                    <div class="achievement">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <h4>២៥+</h4>
                            <p>ខេត្ត/ក្រុង</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision -->
    <section class="mission-vision">
        <div class="mv-container">
            <div class="mission-box">
                <div class="mv-icon">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h3>បេសកកម្មរបស់យើង</h3>
                <p>ផ្តល់សេវាកម្មដឹកជញ្ជូនដែលមានគុណភាពខ្ពស់ សុវត្ថិភាព និងទំនួលខុសត្រូវចំពោះអតិថិជនគ្រប់រូប ដោយប្រើបច្ចេកវិទ្យាទំនើបដើម្បីបង្កើនប្រសិទ្ធភាពការងារ។</p>
            </div>
            
            <div class="vision-box">
                <div class="mv-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <h3>ចក្ខុវិស័យរបស់យើង</h3>
                <p>ក្លាយជាក្រុមហ៊ុនដឹកជញ្ជូនឈានមុខគេនៅក្នុងតំបន់អាស៊ីអាគ្នេយ៍ ដោយការពង្រឹងប្រព័ន្ធគ្រប់គ្រង និងពង្រីកសេវាកម្មទៅកាន់ទីផ្សារអន្តរជាតិ។</p>
            </div>
        </div>
    </section>

    <!-- Our Values -->
    <section class="values-section">
        <div class="section-header">
            <h2>តម្លៃ <span class="highlight">របស់យើង</span></h2>
            <p>គុណតម្លៃដែលយើងរស់នៅដោយ</p>
        </div>
        
        <div class="values-grid">
            <div class="value-card">
                <div class="value-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h4>សុវត្ថិភាព</h4>
                <p>សុវត្ថិភាពជាអាទិភាពលំដាប់ទីមួយក្នុងគ្រប់ប្រតិបត្តិការរបស់យើង។</p>
            </div>
            
            <div class="value-card">
                <div class="value-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <h4>ទំនុកចិត្ត</h4>
                <p>យើងសាងសង់ទំនាក់ទំនងដោយស្មោះត្រង់ និងទុកចិត្តជាមួយអតិថិជន។</p>
            </div>
            
            <div class="value-card">
                <div class="value-icon">
                    <i class="fas fa-rocket"></i>
                </div>
                <h4>ការច្នៃប្រឌិត</h4>
                <p>យើងតែងតែរកមធ្យោបាយថ្មីៗដើម្បីបង្កើនប្រសិទ្ធភាពការងារ។</p>
            </div>
            
            <div class="value-card">
                <div class="value-icon">
                    <i class="fas fa-users-cog"></i>
                </div>
                <h4>ការសហការ</h4>
                <p>ការងារក្រុមគឺជាគន្លឹះឆ្ពោះទៅរកភាពជោគជ័យនៃអង្គការ។</p>
            </div>
        </div>
    </section>

    <!-- Our Team -->
    <section class="team-section">
        <div class="section-header">
            <h2><span class="highlight">ក្រុម</span> ដឹកនាំ</h2>
            <p>អ្នកដែលនាំមកនូវទស្សនវិស័យថ្មី</p>
        </div>
        
        <div class="team-grid">
            <div class="team-card">
                <div class="team-img">
                    <img src="{{ asset('images/choose-us.jpg') }}" alt="CEO">
                </div>
                <div class="team-info">
                    <h4>កញ្ញា ខេត្ត ចាន់មូនីតា</h4>
                    <p>ប្រធានប្រតិបត្តិ</p>
                    <div class="team-social">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="team-card">
                <div class="team-img">
                    <img src="{{ asset('images/import-pp.png') }}" alt="Director">
                </div>
                <div class="team-info">
                    <h4>លោក សុខកាយ សុបញ្ញា</h4>
                    <p>អគ្គនាយកប្រតិបត្តិ</p>
                    <div class="team-social">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="team-card">
                <div class="team-img">
                    <img src="{{ asset('images/import-shv.png') }}" alt="Manager">
                </div>
                <div class="team-info">
                    <h4>កញ្ញា ខេត្ត ចាន់មូនីតា</h4>
                    <p>អគ្គនាយកប្រតិបត្តិផ្នែកប្រតិបត្តិការ</p>
                    <div class="team-social">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact CTA -->
    <section class="contact-cta">
        <div class="cta-content">
            <h2>ត្រៀមខ្លួនជាមួយដៃគូដឹកជញ្ជូនរបស់អ្នក</h2>
            <p>ទាក់ទងមកយើងថ្ងៃនេះដើម្បីស្វែងយល់បន្ថែមអំពីសេវាកម្មរបស់យើង</p>
            <div class="cta-buttons">
                <a href="/contact" class="cta-btn primary">
                    <i class="fas fa-phone-alt"></i> ទំនាក់ទំនងយើង
                </a>
                <a href="/services" class="cta-btn secondary">
                    <i class="fas fa-truck-loading"></i> សេវាកម្មរបស់យើង
                </a>
            </div>
        </div>
    </section>
</main>

@include('partials.footer')
<script>
    // Mobile menu toggle
    document.querySelector('.menu-toggle').addEventListener('click', function() {
        document.querySelector('.nav-menu').classList.toggle('show');
    });
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if(targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if(targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Add scroll effect to navbar
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if(window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
</script>

</body>
</html>