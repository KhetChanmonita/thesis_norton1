@once
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/service.css') }}">
    @endpush
@endonce

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
                <img src="images/homepage5356.png" alt="Domestic & Cross-Border Transport">
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
    const images = document.querySelectorAll('.image-section img');
    images.forEach(img => {
        img.onerror = function() {
            this.style.display = 'none';
            const parent = this.parentElement;
            const colors = {'01':'#3498db','02':'#e74c3c','03':'#2ecc71','04':'#f39c12'};
            const icons  = {'01':'fa-shipping-fast','02':'fa-weight-hanging','03':'fa-globe-americas','04':'fa-truck-moving'};
            const num = parent.closest('.service-box').querySelector('.title-number').textContent;
            parent.style.cssText = 'background:'+colors[num]+';display:flex;align-items:center;justify-content:center;';
            const icon = document.createElement('i');
            icon.className = 'fas '+icons[num];
            icon.style.cssText = 'font-size:60px;color:white;opacity:0.8;';
            parent.appendChild(icon);
        };
    });
});
</script>