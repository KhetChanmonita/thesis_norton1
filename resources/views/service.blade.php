<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>សេវាកម្មរបស់យើង - Trucking Service</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Kantumruy+Pro:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/service.css') }}">
</head>
<body>
    @include('partials.header')

    <div class="services-container">
        <!-- Main Title -->
        <div class="main-title">
            <h1>សេវាកម្មរបស់យើង</h1>
            <div class="subtitle">អ្វីដែលយើងផ្តល់ជូន</div>
        </div>

        <!-- Services Grid -->
        <div class="services-grid">
            <!-- Service 1 -->
            <div class="service-box">
                <div class="image-section">
                    <img src="images/import-shv.png" alt="Container Transportation">
                </div>
                <div class="content-section">
                    <h3 class="service-title">
                        <span class="title-number">01</span>
                        Import From SHV Port
                    </h3>
                    <p class="service-description">សេវាកម្មដឹកជញ្ជូនកុងតឺន័រដែលអាចទុកចិត្តបានពី​ កំពង់ផែស្វយ័តព្រះសីហនុ ទៅកាន់គោលដៅដោយប្រើប្រាស់ដំណោះស្រាយដឹកជញ្ជូនប្រកបដោយវិជ្ជាជីវៈ។</p>
                    <a href="{{ route('service.detail', 'import-shv') }}" class="service-detail-btn">
                        <i class="fas fa-book-open"></i> មើលព័ត៌មានលម្អិត <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Service 2 -->
            <div class="service-box">
                <div class="image-section">
                    <img src="images/export.png" alt="Import & Export Delivery">
                </div>
                <div class="content-section">
                    <h3 class="service-title">
                        <span class="title-number">02</span>
                        Export To SHV Port
                    </h3>
                    <p class="service-description">សេវាកម្មដឹកជញ្ជូនកុងតឺន័រដែលអាចទុកចិត្តបានពីរោងចក្រក្នុងស្រុកទៅកាន់កំពង់ផែស្វយ័តព្រះសីហនុ ដោយប្រើដំណោះស្រាយដឹកជញ្ជូនប្រកបដោយវិជ្ជាជីវៈ។</p>
                    <a href="{{ route('service.detail', 'export-shv') }}" class="service-detail-btn">
                        <i class="fas fa-book-open"></i> មើលព័ត៌មានលម្អិត <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Service 3 -->
            <div class="service-box">
                <div class="image-section">
                    <img src="images/Import-pp.png" alt="Heavy Cargo Transport">
                </div>
                <div class="content-section">
                    <h3 class="service-title">
                        <span class="title-number">03</span>
                        Import From PP Port
                    </h3>
                    <p class="service-description">សេវាកម្មដឹកជញ្ជូនកុងតឺន័រដែលអាចទុកចិត្តបានពី កំពង់ផែស្វយ័តអន្តរជាតិភ្នំពេញ ទៅកាន់គោលដៅដោយប្រើប្រាស់ដំណោះស្រាយដឹកជញ្ជូនប្រកបដោយវិជ្ជាជីវៈ។</p>
                    <a href="{{ route('service.detail', 'import-pp') }}" class="service-detail-btn">
                        <i class="fas fa-book-open"></i> មើលព័ត៌មានលម្អិត <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Service 4 -->
            <div class="service-box">
                <div class="image-section">
                    <img src="images/pick-con1.png" alt="Domestic & Cross-Border Transport">
                </div>
                <div class="content-section">
                    <h3 class="service-title">
                        <span class="title-number">04</span>
                        Export To PP Port
                    </h3>
                    <p class="service-description">សេវាកម្មដឹកជញ្ជូនកុងតឺន័រដែលអាចទុកចិត្តបានពីរោងចក្រក្នុងស្រុកទៅកាន់ កំពង់ផែស្វយ័តអន្តរជាតិភ្នំពេញ ដោយប្រើដំណោះស្រាយដឹកជញ្ជូនប្រកបដោយវិជ្ជាជីវៈ។</p>
                    <a href="{{ route('service.detail', 'export-pp') }}" class="service-detail-btn">
                        <i class="fas fa-book-open"></i> មើលព័ត៌មានលម្អិត <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add animation to service boxes on scroll
            const serviceBoxes = document.querySelectorAll('.service-box');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });

            serviceBoxes.forEach(box => {
                box.style.opacity = '0';
                box.style.transform = 'translateY(30px)';
                box.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(box);
            });

            // If images don't load, show fallback colors
            const images = document.querySelectorAll('.image-section img');
            images.forEach(img => {
                img.onerror = function() {
                    const parent = this.parentElement;

                    // Remove broken image
                    this.style.display = 'none';

                    // Add colored background based on service number
                    let backgroundColor = '#3498db'; // Default blue

                    // Find service number
                    const serviceBox = parent.closest('.service-box');
                    const titleNumber = serviceBox.querySelector('.title-number').textContent;

                    switch(titleNumber) {
                        case '01':
                            backgroundColor = '#3498db'; // Blue
                            break;
                        case '02':
                            backgroundColor = '#e74c3c'; // Red
                            break;
                        case '03':
                            backgroundColor = '#2ecc71'; // Green
                            break;
                        case '04':
                            backgroundColor = '#f39c12'; // Orange
                            break;
                    }

                    parent.style.backgroundColor = backgroundColor;
                    parent.style.display = 'flex';
                    parent.style.alignItems = 'center';
                    parent.style.justifyContent = 'center';

                    // Add icon based on service
                    const icon = document.createElement('i');
                    icon.style.fontSize = '60px';
                    icon.style.color = 'white';
                    icon.style.opacity = '0.8';

                    switch(titleNumber) {
                        case '01':
                            icon.className = 'fas fa-shipping-fast';
                            break;
                        case '02':
                            icon.className = 'fas fa-weight-hanging';
                            break;
                        case '03':
                            icon.className = 'fas fa-globe-americas';
                            break;
                        case '04':
                            icon.className = 'fas fa-truck-moving';
                            break;
                    }

                    parent.appendChild(icon);
                };
            });
        });
    </script>
</body>
</html>
@include('whychooseus')
