<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/footer.css') }}">

<!-- Footer -->
<footer class="footer">
    <div class="footer-container">
        <div class="footer-col">
            <div class="footer-logo">
                <img src="{{ asset('images/trucking-logo.png') }}" alt="Trucking Logo">
                <span>Trucking System</span>
            </div>
            <p>ក្រុមហ៊ុនដឹកជញ្ជូនដែលទុកចិត្តបាន និងមានបទពិសោធន៍ជាង ១០ឆ្នាំនៅក្នុងប្រទេសកម្ពុជា។</p>
            <div class="footer-social">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-telegram"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
                <a href="#"><i class="fab fa-linkedin"></i></a>
            </div>
        </div>
        
        <div class="footer-col">
            <h4>តំណរភ្ជាប់</h4>
            <ul>
                <li><a href="{{ route('home') }}"><i class="fas fa-chevron-right"></i> ទំព័រដើម</a></li>
                <li><a href="{{ route('services_header') }}"><i class="fas fa-chevron-right"></i> សេវាកម្ម</a></li>
                <li><a href="{{ route('about_us') }}"><i class="fas fa-chevron-right"></i> អំពីរពួកយើង</a></li>
                <li><a href="{{ route('trucks_section') }}"><i class="fas fa-chevron-right"></i> អំពីរថយន្ត</a></li>
                <li><a href="{{ route('price') }}"><i class="fas fa-chevron-right"></i> តម្លៃ</a></li>
            </ul>
        </div>
        
        <div class="footer-col">
            <h4>សេវាកម្ម</h4>
            <ul>
                <!-- <li><a href="#"><i class="fas fa-chevron-right"></i> ដឹកជញ្ជូនទំនិញ</a></li> -->
                <li><a href="#"><i class="fas fa-chevron-right"></i> ដឹកជញ្ជូនពីកំពង់ផែស្វ័យយ័តព្រះសីហនុ ​និងកំពង់ផែស្វ័យយ័តភ្នំពេញ ​ទៅកាន់រោងចក្រនៅក្នុងស្រុក</a></li>
                <li><a href="#"><i class="fas fa-chevron-right"></i> ដឹកជញ្ជូនពីរោងចក្រក្នុងស្រុកទៅកាន់កំពង់ផែស្វ័យយ័តព្រះសីហនុ និងកំពង់ផែស្វ័យយ័តភ្នំពេញ</a></li>
                <li><a href="#"><i class="fas fa-chevron-right"></i> ការដឹកជញ្ជូនដែលមានសុវត្ថិភាព</a></li>
            </ul>
        </div>
        
        <div class="footer-col">
            <h4>ទំនាក់ទំនង</h4>
            <div class="contact-info">
                <p><i class="fas fa-map-marker-alt"></i> ផ្លូវបឹងទទឹងថ្ងៃ២ ផ្ទះលេខ ៩៨៣​ ខណ្ឌ ជ្រោយចង្វា រាជធានីភ្នំពេញ</p>
                <p><i class="fas fa-phone"></i> +855 12 345 678</p>
                <p><i class="fas fa-envelope"></i> lstrucking@gmail.com</p>
            </div>
        </div>
    </div>
    
    <div class="footer-bottom">
        <p>© 2026 ប្រព័ន្ធគ្រប់គ្រងដឹកជញ្ជូន | រក្សាសិទ្ធិគ្រប់យ៉ាង</p>
    </div>
</footer>
