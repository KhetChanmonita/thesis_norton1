<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>តម្លៃសេវា | Trucking System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Kantumruy+Pro:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/price.css') }}">
</head>
<body>
@include('partials.header')

<!-- Hero Section -->
<section class="pricing-hero">
    <div class="hero-container">
        <h1>តម្លៃសេវាដឹកជញ្ជូន</h1>
        <p>តម្លៃសមរម្យ និងមានការប្រៀបធៀបគ្នានៅលើទីផ្សារ</p>
        
        <div class="pricing-toggle">
            <span class="toggle-label">ការគណនាតម្លៃ</span>
            <div class="toggle-switch">
                <button class="toggle-btn active" data-type="distance">តាមចម្ងាយ</button>
                <button class="toggle-btn" data-type="weight">តាមទំងន់</button>
                <button class="toggle-btn" data-type="truck">តាមប្រភេទរថយន្ត</button>
            </div>
        </div>
    </div>
</section>

<!-- Price Calculator -->
<section class="calculator-section">
    <div class="container">
        <div class="calculator-card">
            <h2><i class="fas fa-calculator"></i> គណនាតម្លៃដឹកជញ្ជូន</h2>
            <p>បំពេញព័ត៌មានខាងក្រោមដើម្បីទទួលបានតម្លៃប៉ាន់ស្មាន</p>
            
            <div class="calculator-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="from-location"><i class="fas fa-map-marker-alt"></i> ទីតាំងចាប់ផ្តើម</label>
                        <input type="text" id="from-location" placeholder="ឧ. ភ្នំពេញ">
                    </div>
                    
                    <div class="form-group">
                        <label for="to-location"><i class="fas fa-map-marker"></i> ទីតាំងគោលដៅ</label>
                        <input type="text" id="to-location" placeholder="ឧ. ក្រុងសៀមរាប">
                    </div>
                    
                    <div class="form-group">
                        <label for="truck-type"><i class="fas fa-truck"></i> ប្រភេទរថយន្ត</label>
                        <select id="truck-type">
                            <option value="small">រថយន្តតូច (១-៣តោន)</option>
                            <option value="medium" selected>រថយន្តមធ្យម (៣-១០តោន)</option>
                            <option value="large">រថយន្តធំ (១០-២០តោន)</option>
                            <option value="container">រថយន្តផ្ទុកគម្រប (២០តោន+)</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="weight"><i class="fas fa-weight-hanging"></i> ទំងន់ទំនិញ (តោន)</label>
                        <input type="range" id="weight" min="1" max="20" value="5">
                        <div class="range-value">
                            <span id="weight-value">5 តោន</span>
                            <span class="range-min">1ត</span>
                            <span class="range-max">20ត</span>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-calendar-alt"></i> បន្ថែមសេវាកម្ម</label>
                    <div class="services-checkbox">
                        <label class="checkbox-label">
                            <input type="checkbox" id="loading" checked>
                            <span class="checkmark"></span>
                            សេវាផ្ទុកទំនិញ
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" id="unloading" checked>
                            <span class="checkmark"></span>
                            សេវាទំលាក់ទំនិញ
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" id="insurance">
                            <span class="checkmark"></span>
                            ធានារ៉ាប់រងទំនិញ
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" id="storage">
                            <span class="checkmark"></span>
                            សេវាក្រោដ៍ទុកបណ្តោះអាសន្ន
                        </label>
                    </div>
                </div>
                
                <button class="calculate-btn" id="calculate-price">
                    <i class="fas fa-calculator"></i> គណនាតម្លៃ
                </button>
            </div>
            
            <div class="result-card" id="price-result">
                <div class="result-header">
                    <h3><i class="fas fa-receipt"></i> តម្លៃប៉ាន់ស្មាន</h3>
                    <div class="result-total">
                        <span class="amount">$0.00</span>
                        <span class="currency">ដុល្លារ</span>
                    </div>
                </div>
                
                <div class="result-details">
                    <div class="detail-item">
                        <span>ថ្លៃដឹកជញ្ជូនមូលដ្ឋាន</span>
                        <span>$0.00</span>
                    </div>
                    <div class="detail-item">
                        <span>សេវាបន្ថែម</span>
                        <span>$0.00</span>
                    </div>
                    <div class="detail-item">
                        <span>ពន្ធនិងថ្លៃសេវា</span>
                        <span>$0.00</span>
                    </div>
                    <div class="detail-item discount">
                        <span>ការបញ្ចុះតម្លៃ (១០%)</span>
                        <span>-$0.00</span>
                    </div>
                    <div class="detail-item total">
                        <span>តម្លៃសរុប</span>
                        <span>$0.00</span>
                    </div>
                </div>
                
                <div class="result-actions">
                    <a href="{{ route('login') }}" class="btn-book">
                        <i class="fas fa-calendar-check"></i> ចូលគណនីដើម្បីកក់
                    </a>
                    <a href="/contact" class="btn-contact">
                        <i class="fas fa-phone-alt"></i> ទំនាក់ទំនងយើង
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pricing Plans -->
<section class="pricing-plans">
    <div class="container">
        <div class="section-header">
            <h2>កញ្ចប់<span class="highlight">តម្លៃសេវា</span></h2>
            <p>ជ្រើសរើសកញ្ចប់សេវាដែលសមនឹងតម្រូវការរបស់អ្នក</p>
        </div>
        
        <div class="plans-grid">
            <!-- Basic Plan -->
            <div class="plan-card">
                <div class="plan-header">
                    <div class="plan-icon">
                        <i class="fas fa-truck-pickup"></i>
                    </div>
                    <h3>កញ្ចប់មូលដ្ឋាន</h3>
                    <div class="plan-price">
                        <span class="amount">$150</span>
                        <span class="period">/ ខែ</span>
                    </div>
                    <p>សម្រាប់ការដឹកជញ្ជូនតាមតំបន់</p>
                </div>
                
                <div class="plan-features">
                    <h4>អ្វីដែលរួមបញ្ចូល:</h4>
                    <ul>
                        <li><i class="fas fa-check"></i> ដឹកជញ្ជូនក្នុងទីក្រុង</li>
                        <li><i class="fas fa-check"></i> រថយន្ត ៣តោន</li>
                        <li><i class="fas fa-check"></i> ចម្ងាយរហូតដល់ ៥០គីឡូម៉ែត្រ</li>
                        <li><i class="fas fa-check"></i> សេវាផ្ទុក/ទំលាក់</li>
                        <li><i class="fas fa-check"></i> ថ្លៃប្រេងឥន្ធនៈរួម</li>
                    </ul>
                </div>
                
                <div class="plan-limitations">
                    <h4>កំហិត:</h4>
                    <ul>
                        <li><i class="fas fa-times"></i> មិនរួមធានារ៉ាប់រង</li>
                        <li><i class="fas fa-times"></i> មិនរួមសេវាសន្តិសុខ</li>
                        <li><i class="fas fa-times"></i> មិនរួមក្រោដ៍ទុក</li>
                    </ul>
                </div>
                
                <button class="plan-select-btn">
                    <i class="fas fa-shopping-cart"></i> ជ្រើសរើសកញ្ចប់
                </button>
            </div>
            
            <!-- Standard Plan (Featured) -->
            <div class="plan-card featured">
                <div class="plan-badge">ពេញនិយម</div>
                <div class="plan-header">
                    <div class="plan-icon">
                        <i class="fas fa-truck-moving"></i>
                    </div>
                    <h3>កញ្ចប់ស្តង់ដារ</h3>
                    <div class="plan-price">
                        <span class="amount">$450</span>
                        <span class="period">/ ខែ</span>
                    </div>
                    <p>សម្រាប់ការដឹកជញ្ជូនតាមផ្លូវជាតិ</p>
                </div>
                
                <div class="plan-features">
                    <h4>អ្វីដែលរួមបញ្ចូល:</h4>
                    <ul>
                        <li><i class="fas fa-check"></i> ដឹកជញ្ជូនពាសពេញប្រទេស</li>
                        <li><i class="fas fa-check"></i> រថយន្ត ១០តោន</li>
                        <li><i class="fas fa-check"></i> ចម្ងាយគ្មានកំណត់ក្នុងប្រទេស</li>
                        <li><i class="fas fa-check"></i> សេវាផ្ទុក/ទំលាក់</li>
                        <li><i class="fas fa-check"></i> ធានារ៉ាប់រងទំនិញ ៨០%</li>
                        <li><i class="fas fa-check"></i> ថ្លៃប្រេងឥន្ធនៈរួម</li>
                        <li><i class="fas fa-check"></i> សេវាតាមដានទំនិញ</li>
                    </ul>
                </div>
                
                <div class="plan-limitations">
                    <h4>កំហិត:</h4>
                    <ul>
                        <li><i class="fas fa-times"></i> មិនរួមដឹកជញ្ជូនអន្តរជាតិ</li>
                        <li><i class="fas fa-times"></i> កំហិតទំងន់ ១០តោន</li>
                    </ul>
                </div>
                
                <button class="plan-select-btn featured-btn">
                    <i class="fas fa-star"></i> ជ្រើសរើសកញ្ចប់
                </button>
            </div>
            
            <!-- Premium Plan -->
            <div class="plan-card">
                <div class="plan-header">
                    <div class="plan-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h3>កញ្ចប់ពិសេស</h3>
                    <div class="plan-price">
                        <span class="amount">$900</span>
                        <span class="period">/ ខែ</span>
                    </div>
                    <p>សម្រាប់ការដឹកជញ្ជូនពេញលេញ</p>
                </div>
                
                <div class="plan-features">
                    <h4>អ្វីដែលរួមបញ្ចូល:</h4>
                    <ul>
                        <li><i class="fas fa-check"></i> ដឹកជញ្ជូនពាសពេញប្រទេស</li>
                        <li><i class="fas fa-check"></i> រថយន្ត ២០តោន</li>
                        <li><i class="fas fa-check"></i> ដឹកជញ្ជូនអន្តរជាតិ (តែកម្ពុជា-ថៃ-វៀតណាម)</li>
                        <li><i class="fas fa-check"></i> សេវាផ្ទុក/ទំលាក់</li>
                        <li><i class="fas fa-check"></i> ធានារ៉ាប់រងទំនិញ ១០០%</li>
                        <li><i class="fas fa-check"></i> ថ្លៃប្រេងឥន្ធនៈរួម</li>
                        <li><i class="fas fa-check"></i> សេវាតាមដានទំនិញតាមពេលពិត</li>
                        <li><i class="fas fa-check"></i> ក្រោដ៍ទុកបណ្តោះអាសន្ន ៣០ថ្ងៃ</li>
                    </ul>
                </div>
                
                <div class="plan-limitations">
                    <h4>កំហិត:</h4>
                    <ul>
                        <li><i class="fas fa-times"></i> មិនរួមដឹកជញ្ជូនទៅចិន</li>
                    </ul>
                </div>
                
                <button class="plan-select-btn">
                    <i class="fas fa-crown"></i> ជ្រើសរើសកញ្ចប់
                </button>
            </div>
        </div>
        
        <div class="custom-plan">
            <div class="custom-content">
                <h3><i class="fas fa-cogs"></i> មិនឃើញកញ្ចប់ដែលត្រូវនឹងតម្រូវការរបស់អ្នក?</h3>
                <p>ទំនាក់ទំនងយើងឥឡូវនេះដើម្បីទទួលបានកញ្ចប់តម្លៃតាមតម្រូវការផ្ទាល់ខ្លួនរបស់អ្នក។</p>
                <a href="/contact" class="custom-btn">
                    <i class="fas fa-phone-alt"></i> ស្នើសុំកញ្ចប់តាមតម្រូវការ
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Price Comparison Table -->
<section class="comparison-section">
    <div class="container">
        <div class="section-header">
            <h2>តារាង<span class="highlight">ប្រៀបធៀបតម្លៃ</span></h2>
            <p>ព័ត៌មានលម្អិតអំពីតម្លៃដឹកជញ្ជូនតាមចម្ងាយ</p>
        </div>
        
        <div class="comparison-table">
            <table>
                <thead>
                    <tr>
                        <th>ចម្ងាយ (គីឡូម៉ែត្រ)</th>
                        <th>រថយន្តតូច<br><small>(១-៣តោន)</small></th>
                        <th>រថយន្តមធ្យម<br><small>(៣-១០តោន)</small></th>
                        <th>រថយន្តធំ<br><small>(១០-២០តោន)</small></th>
                        <th>រថយន្តផ្ទុកគម្រប<br><small>(២០តោន+)</small></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>០-៥០គីឡូម៉ែត្រ</td>
                        <td>$៥០ - $៨០</td>
                        <td>$៨០ - $១២០</td>
                        <td>$១២០ - $២០០</td>
                        <td>$២០០ - $៣០០</td>
                    </tr>
                    <tr>
                        <td>៥១-១០០គីឡូម៉ែត្រ</td>
                        <td>$៨០ - $១២០</td>
                        <td>$១២០ - $១៨០</td>
                        <td>$១៨០ - $២៨០</td>
                        <td>$២៨០ - $៤២០</td>
                    </tr>
                    <tr>
                        <td>១០១-២០០គីឡូម៉ែត្រ</td>
                        <td>$១២០ - $១៨០</td>
                        <td>$១៨០ - $២៦០</td>
                        <td>$២៦០ - $៤០០</td>
                        <td>$៤០០ - $៦០០</td>
                    </tr>
                    <tr>
                        <td>២០១-៥០០គីឡូម៉ែត្រ</td>
                        <td>$១៨០ - $៣០០</td>
                        <td>$៣០០ - $៥០០</td>
                        <td>$៥០០ - $៨០០</td>
                        <td>$៨០០ - $១,២០០</td>
                    </tr>
                    <tr>
                        <td>៥០១គីឡូម៉ែត្រ+</td>
                        <td>$០.៤/គ.ម</td>
                        <td>$០.៦/គ.ម</td>
                        <td>$១.០/គ.ម</td>
                        <td>$១.៥/គ.ម</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="table-notes">
            <div class="note">
                <i class="fas fa-info-circle"></i>
                <p>តម្លៃខាងលើគឺសម្រាប់តែដឹកជញ្ជូនប៉ុណ្ណោះ។ ថ្លៃបន្ថែមសម្រាប់សេវាផ្ទុក/ទំលាក់ និងធានារ៉ាប់រង។</p>
            </div>
            <div class="note">
                <i class="fas fa-percentage"></i>
                <p>ការបញ្ចុះតម្លៃ ១០% សម្រាប់ការកក់ជាមុន ៣ថ្ងៃ។</p>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq-section">
    <div class="container">
        <div class="section-header">
            <h2>សំណួរ<span class="highlight">ដែលគេសួរញឹកញាប់</span></h2>
            <p>ស្វែងរកចម្លើយសម្រាប់សំណួររបស់អ្នក</p>
        </div>
        
        <div class="faq-grid">
            <div class="faq-item">
                <div class="faq-question">
                    <h3>តើតម្លៃដឹកជញ្ជូនត្រូវបានគណនាយ៉ាងដូចម្តេច?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>តម្លៃដឹកជញ្ជូនត្រូវបានគណនាដោយផ្អែកលើចម្ងាយដឹកជញ្ជូន ប្រភេទរថយន្ត ទំងន់ទំនិញ និងសេវាកម្មបន្ថែមដូចជាការផ្ទុក ទំលាក់ និងធានារ៉ាប់រង។</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <h3>តើមានការបញ្ចុះតម្លៃសម្រាប់ការដឹកជញ្ជូនច្រើនដងទេ?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>បាទ/ចាស យើងមានការបញ្ចុះតម្លៃពិសេសសម្រាប់អតិថិជនដែលប្រើប្រាស់សេវាកម្មរបស់យើងជាប្រចាំ។ ការបញ្ចុះតម្លៃអាចឡើងដល់ ២០% សម្រាប់កិច្ចសន្យាប្រចាំ១ឆ្នាំ។</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <h3>តើអាចទូទាត់តាមរបៀបណាខ្លះ?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>យើងទទួលការទូទាត់តាមរយៈ សាច់ប្រាក់ ប័ណ្ណធនាគារ ប្រាក់អន្តរការណ៍ និងទូទាត់អនឡាញតាមរយៈ ABA Pay និង Pi Pay។</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <h3>តើត្រូវការកក់សេវាជាមុនយ៉ាងដូចម្តេច?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>ការកក់សេវា ៣០% ត្រូវបានទាមទារជាមុនដើម្បីបញ្ជាក់ការកក់ និង ៧០% ទៀតនៅពេលបញ្ចប់សេវា។</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>រីករាយក្នុងការផ្តល់សេវាដល់អតិថិជនរាប់ម៉ឺននាក់</h2>
            <p>ចូលរួមជាមួយក្រុមអតិថិជនដែលទុកចិត្តយើង</p>
            <div class="cta-stats">
                <div class="stat">
                    <h3>១០០០+</h3>
                    <p>អតិថិជនជាប្រចាំ</p>
                </div>
                <div class="stat">
                    <h3>៩៨%</h3>
                    <p>ការពេញចិត្តអតិថិជន</p>
                </div>
                <div class="stat">
                    <h3>២៤/៧</h3>
                    <p>សេវាគាំទ្រ</p>
                </div>
            </div>
            <a href="/contact" class="cta-button">
                <i class="fas fa-headset"></i> ទំនាក់ទំនងយើងឥឡូវនេះ
            </a>
        </div>
    </div>
</section>

@include('partials.footer')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile menu toggle
        const menuToggle = document.querySelector('.menu-toggle');
        if (menuToggle) {
            menuToggle.addEventListener('click', function() {
                const navMenu = document.querySelector('.nav-menu');
                if (navMenu) {
                    navMenu.classList.toggle('show');
                }
            });
        }
        
        // Weight slider
        const weightSlider = document.getElementById('weight');
        const weightValue = document.getElementById('weight-value');
        
        if (weightSlider && weightValue) {
            weightSlider.addEventListener('input', function() {
                weightValue.textContent = this.value + ' តោន';
            });
        }
        
        // Toggle buttons
        const toggleBtns = document.querySelectorAll('.toggle-btn');
        toggleBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                toggleBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });
        
        // Calculate price button
        const calculateBtn = document.getElementById('calculate-price');
        if (calculateBtn) {
            calculateBtn.addEventListener('click', function() {
                calculatePrice();
            });
        }
        
        // FAQ accordion
        const faqQuestions = document.querySelectorAll('.faq-question');
        faqQuestions.forEach(question => {
            question.addEventListener('click', function() {
                const answer = this.nextElementSibling;
                const icon = this.querySelector('i');
                
                // Toggle current answer
                if (answer) {
                    answer.classList.toggle('active');
                }
                if (icon) {
                    icon.classList.toggle('fa-chevron-down');
                    icon.classList.toggle('fa-chevron-up');
                }
                
                // Close other answers
                faqQuestions.forEach(q => {
                    if (q !== this) {
                        const otherAnswer = q.nextElementSibling;
                        const otherIcon = q.querySelector('i');
                        if (otherAnswer) {
                            otherAnswer.classList.remove('active');
                        }
                        if (otherIcon) {
                            otherIcon.classList.remove('fa-chevron-up');
                            otherIcon.classList.add('fa-chevron-down');
                        }
                    }
                });
            });
        });
        
        // Initial calculation
        calculatePrice();
    });
    
    function calculatePrice() {
        // Get values from form
        const truckType = document.getElementById('truck-type');
        const weight = document.getElementById('weight');
        
        if (!truckType || !weight) return;
        
        const truckTypeValue = truckType.value;
        const weightValue = parseInt(weight.value) || 5;
        
        // Base prices per truck type
        const basePrices = {
            'small': 50,
            'medium': 80,
            'large': 120,
            'container': 200
        };
        
        // Calculate base price
        let basePrice = (basePrices[truckTypeValue] || 80) * weightValue;
        
        // Additional services
        let additionalPrice = 0;
        const loading = document.getElementById('loading');
        const unloading = document.getElementById('unloading');
        const insurance = document.getElementById('insurance');
        const storage = document.getElementById('storage');
        
        if (loading && loading.checked) additionalPrice += 20;
        if (unloading && unloading.checked) additionalPrice += 20;
        if (insurance && insurance.checked) additionalPrice += basePrice * 0.1;
        if (storage && storage.checked) additionalPrice += 50;
        
        // Taxes and fees (10%)
        const taxes = (basePrice + additionalPrice) * 0.1;
        
        // Discount (10% if all services selected)
        let discount = 0;
        if (loading && loading.checked && unloading && unloading.checked && insurance && insurance.checked) {
            discount = (basePrice + additionalPrice) * 0.1;
        }
        
        // Total price
        const totalPrice = basePrice + additionalPrice + taxes - discount;
        
        // Format currency
        const formatter = new Intl.NumberFormat('en-US', {
            style: 'decimal',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        
        // Update results
        const resultTotal = document.querySelector('.result-total .amount');
        const detailItems = document.querySelectorAll('.detail-item');
        
        if (resultTotal) {
            resultTotal.textContent = '$' + formatter.format(totalPrice);
        }
        
        if (detailItems.length >= 5) {
            const basePriceElement = detailItems[0]?.querySelector('span:last-child');
            const additionalElement = detailItems[1]?.querySelector('span:last-child');
            const taxesElement = detailItems[2]?.querySelector('span:last-child');
            const discountElement = detailItems[3]?.querySelector('span:last-child');
            const totalElement = detailItems[4]?.querySelector('span:last-child');
            
            if (basePriceElement) basePriceElement.textContent = '$' + formatter.format(basePrice);
            if (additionalElement) additionalElement.textContent = '$' + formatter.format(additionalPrice);
            if (taxesElement) taxesElement.textContent = '$' + formatter.format(taxes);
            if (discountElement) discountElement.textContent = '-$' + formatter.format(discount);
            if (totalElement) totalElement.textContent = '$' + formatter.format(totalPrice);
        }
        
        // Show result card
        const resultCard = document.getElementById('price-result');
        if (resultCard) {
            resultCard.style.display = 'block';
            setTimeout(() => {
                resultCard.style.opacity = '1';
                resultCard.style.transform = 'translateY(0)';
            }, 100);
        }
    }
</script>

</body>
</html>