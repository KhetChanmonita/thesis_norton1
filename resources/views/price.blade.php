<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>តម្លៃសេវា | Trucking System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;500;600;700&family=Montserrat:wght@600;700;800;900&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/price.css') }}">
</head>
<body>
@include('partials.header')

<!-- Hero Section -->
<section class="pricing-hero">
    {{-- Decorative background shapes --}}
    <div class="hero-shape hero-shape-1"></div>
    <div class="hero-shape hero-shape-2"></div>
    <div class="hero-shape hero-shape-3"></div>
    <div class="hero-shape hero-shape-4"></div>

    {{-- Floating icons --}}
    <i class="fas fa-truck      hero-float-icon" style="top:18%;left:6%;font-size:5rem;transform:rotate(-15deg);"></i>
    <i class="fas fa-box-open   hero-float-icon" style="top:55%;right:5%;font-size:3.5rem;transform:rotate(12deg);"></i>
    <i class="fas fa-shipping-fast hero-float-icon" style="bottom:15%;left:18%;font-size:2.8rem;transform:rotate(-8deg);"></i>
    <i class="fas fa-map-marker-alt hero-float-icon" style="top:20%;right:18%;font-size:2.2rem;"></i>

    <div class="hero-container">
        <div class="hero-badge">
            <i class="fas fa-tag"></i> តម្លៃល្អបំផុតនៅលើទីផ្សារ
        </div>
        <h1>តម្លៃសេវា<span class="hero-text-highlight">ដឹកជញ្ជូន</span></h1>
        <p>ប្រព័ន្ធគណនាតម្លៃដឹកជញ្ជូនពីច្រកផ្លូវ ២ ទៅគ្រប់ខេត្ត-រាជធានី ២៥ ក្នុងព្រះរាជាណាចក្រកម្ពុជា</p>

        <div class="hero-stats-bar">
            <div class="hero-stat">
                <span class="hero-stat-num">25</span>
                <span class="hero-stat-lbl">ខេត្ត / រាជធានី</span>
            </div>
            <div class="hero-stat-sep"></div>
            <div class="hero-stat">
                <span class="hero-stat-num">2</span>
                <span class="hero-stat-lbl">ច្រកផ្លូវចេញ</span>
            </div>
        </div>
    </div>
</section>

<!-- Price Calculator -->
<section class="calculator-section">
    <div class="container">
        <div class="calculator-card">
            <h2><i class="fas fa-calculator"></i> គណនាតម្លៃដឹកជញ្ជូន</h2>
            <p>ជ្រើសរើសប្រភេទសេវា កំពង់ផែ និងខេត្តគោលដៅ ដើម្បីមើលតម្លៃ</p>

            <div class="calculator-form">

                {{-- Step 1: Import / Export --}}
                <div class="type-selector-wrap">
                    <div class="type-selector">
                        <button type="button" id="btn-import" class="type-btn active" onclick="selectServiceType('import')">
                            <i class="fas fa-ship"></i>
                            <span class="type-btn-title">Import</span>
                            <span class="type-btn-sub">ទំនិញចូល</span>
                        </button>
                        <button type="button" id="btn-export" class="type-btn" onclick="selectServiceType('export')">
                            <i class="fas fa-truck-loading"></i>
                            <span class="type-btn-title">Export</span>
                            <span class="type-btn-sub">ទំនិញចេញ</span>
                        </button>
                    </div>
                </div>

                <div class="form-grid">

                    {{-- Step 2: Port --}}
                    <div class="form-group">
                        <label id="port-label">
                            <i class="fas fa-anchor"></i>
                            <span id="port-label-text">មាត់ច្រកកំពង់ផែ</span>
                            <span style="color:#ef4444;">*</span>
                        </label>
                        <select id="origin-port">
                            <option value="">-- ជ្រើសរើសកំពង់ផែ --</option>
                            <option value="sihanoukville">មាត់ច្រកកំពង់ផែព្រះសីហនុ</option>
                            <option value="phnom_penh">មាត់ច្រកកំពង់ផែភ្នំពេញ</option>
                        </select>
                    </div>

                    {{-- Step 3: Province --}}
                    <div class="form-group">
                        <label>
                            <i class="fas fa-map-marker-alt"></i>
                            ខេត្ត/រាជធានីគោលដៅ
                            <span style="color:#ef4444;">*</span>
                        </label>
                        <select id="destination-province">
                            <option value="">-- ជ្រើសរើសខេត្ត --</option>
                            @foreach($provinces as $p)
                            <option value="{{ $p['en'] }}">{{ $p['km'] }} ({{ $p['en'] }})</option>
                            @endforeach
                        </select>
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
                        <span id="lbl-type-badge">Import</span>
                        <span id="lbl-route">—</span>
                    </div>
                    <div class="detail-item">
                        <span>ថ្លៃដឹកជញ្ជូន</span>
                        <span id="val-base">$0.00</span>
                    </div>
                    <div class="detail-item total">
                        <span>តម្លៃសរុប</span>
                        <span id="val-total">$0.00</span>
                    </div>
                </div>
                <div class="result-actions">
                    <a href="{{ route('login') }}" class="btn-book">
                        <i class="fas fa-calendar-check"></i> ចូលគណនីដើម្បីកក់
                    </a>
                    <a href="{{ route('contact') }}" class="btn-contact">
                        <i class="fas fa-phone-alt"></i> ទំនាក់ទំនងយើង
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How Pricing Works -->
<section class="how-pricing-section">
    <div class="container">
        <div class="section-header">
            <h2>របៀប<span class="highlight">គណនាតម្លៃ</span></h2>
            <p>គ្រាន់តែ ៣ជំហាន ដើម្បីទទួលបានតម្លៃដឹកជញ្ជូនភ្លាមៗ</p>
        </div>

        <div class="steps-grid">
            <div class="step-card">
                <div class="step-num">01</div>
                <div class="step-icon-wrap"><i class="fas fa-ship"></i></div>
                <h3>ជ្រើសរើសច្រកផ្លូវ</h3>
                <p>ជ្រើសរើសច្រកផ្លូវចេញ — កំពង់ផែព្រះសីហនុ ឬ កំពង់ផែភ្នំពេញ</p>
            </div>

            <div class="step-arrow"><i class="fas fa-chevron-right"></i></div>

            <div class="step-card">
                <div class="step-num">02</div>
                <div class="step-icon-wrap"><i class="fas fa-map-marker-alt"></i></div>
                <h3>ជ្រើសរើសខេត្ត</h3>
                <p>ជ្រើសរើសខេត្ត ឬ រាជធានីគោលដៅ ក្នុងចំណោម ២៥ ខេត្ត-រាជធានី</p>
            </div>

            <div class="step-arrow"><i class="fas fa-chevron-right"></i></div>

            <div class="step-card">
                <div class="step-num">03</div>
                <div class="step-icon-wrap"><i class="fas fa-dollar-sign"></i></div>
                <h3>មើលតម្លៃ</h3>
                <p>ចុចគណនា ដើម្បីទទួលបានតម្លៃដឹកជញ្ជូនសម្រាប់ខេត្ត-រាជធានីដែលអ្នកជ្រើសរើស</p>
            </div>
        </div>

        {{-- Included & Surcharges --}}
        <div class="price-info-grid">

            <div class="price-info-card">
                <div class="price-info-header">
                    <i class="fas fa-check-circle"></i>
                    <h3>អ្វីដែលរួមបញ្ចូលក្នុងតម្លៃ</h3>
                </div>
                <ul class="price-check-list">
                    <li><i class="fas fa-check"></i> ថ្លៃដឹកជញ្ជូនមូលដ្ឋានតាមខេត្ត</li>
                    <li><i class="fas fa-check"></i> ថ្លៃប្រេងឥន្ធនៈ</li>
                    <li><i class="fas fa-check"></i> ប្រាក់ខែអ្នកបើកបរ</li>
                    <li><i class="fas fa-check"></i> ការត្រួតពិនិត្យទំនិញ</li>
                    <li><i class="fas fa-check"></i> សេវាតាមដានស្ថានភាព</li>
                    <li><i class="fas fa-check"></i> ការទំនាក់ទំនងជាមួយអ្នកបើក</li>
                </ul>
            </div>

            <div class="price-info-card surcharge-card">
                <div class="price-info-header">
                    <i class="fas fa-exclamation-circle"></i>
                    <h3>ថ្លៃបន្ថែម</h3>
                </div>
                <div class="surcharge-item">
                    <div class="surcharge-icon"><i class="fas fa-exclamation-triangle"></i></div>
                    <div>
                        <div class="surcharge-title">ទំនិញមានគ្រោះថ្នាក់</div>
                        <div class="surcharge-desc">ទំនិញប្រភេទផ្ទុះ ឬគីមី ត្រូវការការស្នើសុំបន្ថែម</div>
                    </div>
                    <div class="surcharge-badge" style="background:#fff3cd;color:#92400e;font-family:'Kantumruy Pro',sans-serif;">ស្នើសុំ</div>
                </div>
                <div class="surcharge-item">
                    <div class="surcharge-icon"><i class="fas fa-clock"></i></div>
                    <div>
                        <div class="surcharge-title">ការដឹកបន្ទាន់</div>
                        <div class="surcharge-desc">ការដឹកជញ្ជូនក្នុងរយៈពេលខ្លី ត្រូវការការចរចា</div>
                    </div>
                    <div class="surcharge-badge" style="background:#e0f2fe;color:#0369a1;font-family:'Kantumruy Pro',sans-serif;">ចរចា</div>
                </div>
            </div>

        </div>

        {{-- Payment Terms Banner --}}
        <div class="payment-terms-banner">
            <div class="payment-term-item">
                <i class="fas fa-file-invoice-dollar"></i>
                <div>
                    <strong>ការបង់ប្រាក់ ៥០%</strong>
                    <span>នៅពេលបញ្ជាក់ការកក់</span>
                </div>
            </div>
            <div class="payment-term-sep"></div>
            <div class="payment-term-item">
                <i class="fas fa-truck-loading"></i>
                <div>
                    <strong>ដឹកជញ្ជូនទំនិញ</strong>
                    <span>ក្រោយអ្នកគ្រប់គ្រងបានអនុម័ត</span>
                </div>
            </div>
            <div class="payment-term-sep"></div>
            <div class="payment-term-item">
                <i class="fas fa-handshake"></i>
                <div>
                    <strong>ការបង់ប្រាក់ ៥០% ចុងក្រោយ</strong>
                    <span>នៅពេលដឹកទំនិញបានជោគជ័យ</span>
                </div>
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

        <div class="faq-layout">

            {{-- Left: support card --}}
            <div class="faq-side-card">
                <div class="faq-side-icon"><i class="fas fa-headset"></i></div>
                <h3>នៅតែមានសំណួរ?</h3>
                <p>ក្រុមការងាររបស់យើងរួចរាល់ជាទូទៅ ២៤/៧ ដើម្បីជួយដល់អ្នក</p>
                <div class="faq-contact-list">
                    <div class="faq-contact-item">
                        <i class="fas fa-phone-alt"></i>
                        <span>+855 12 345 678</span>
                    </div>
                    <div class="faq-contact-item">
                        <i class="fab fa-telegram-plane"></i>
                        <span>@lstrucking</span>
                    </div>
                    <div class="faq-contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>info@lstrucking.com</span>
                    </div>
                </div>
                <a href="{{ route('contact') }}" class="faq-contact-btn">
                    <i class="fas fa-comment-dots"></i> ទំនាក់ទំនងយើង
                </a>
            </div>

            {{-- Right: FAQ accordion --}}
            <div class="faq-list">
                <div class="faq-item">
                    <div class="faq-question">
                        <div class="faq-q-left">
                            <span class="faq-num">01</span>
                            <h3>តើតម្លៃដឹកជញ្ជូនត្រូវបានគណនាយ៉ាងដូចម្តេច?</h3>
                        </div>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>តម្លៃដឹកជញ្ជូនត្រូវបានគណនាដោយផ្អែកលើ ច្រកផ្លូវចេញ (ព្រះសីហនុ ឬ ភ្នំពេញ) ខេត្តគោលដៅ និងទំងន់ទំនិញ។ ប្រសិនបើទំងន់លើស ២៣ តោន ថ្លៃបន្ថែម $30 ក្នុងមួយតោននឹងត្រូវបន្ថែម។</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <div class="faq-q-left">
                            <span class="faq-num">02</span>
                            <h3>តើមានការបញ្ចុះតម្លៃសម្រាប់ការដឹកជញ្ជូនច្រើនដងទេ?</h3>
                        </div>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>បាទ/ចាស យើងមានការបញ្ចុះតម្លៃពិសេសសម្រាប់អតិថិជនដែលប្រើប្រាស់សេវាកម្មរបស់យើងជាប្រចាំ។ ការបញ្ចុះតម្លៃអាចឡើងដល់ ២០% សម្រាប់កិច្ចសន្យាប្រចាំ១ឆ្នាំ។</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <div class="faq-q-left">
                            <span class="faq-num">03</span>
                            <h3>តើអាចទូទាត់តាមរបៀបណាខ្លះ?</h3>
                        </div>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>យើងទទួលការទូទាត់តាមរយៈ សាច់ប្រាក់ KHQR និងការផ្ទេរប្រាក់តាម ABA Pay ។ ការទូទាត់ ៥០% ជាមុន និង ៥០% ទៀតនៅពេលបញ្ចប់ការដឹក។</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <div class="faq-q-left">
                            <span class="faq-num">04</span>
                            <h3>តើត្រូវការកក់សេវាជាមុនយ៉ាងដូចម្តេច?</h3>
                        </div>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>គ្រាន់តែជ្រើសរើសរថយន្ត បំពេញព័ត៌មានការដឹក និងបង់ប្រាក់ ៥០% ជាប្រាក់កក់ ។ ក្រោយពីអ្នកគ្រប់គ្រងបានអនុម័ត ការដឹកជញ្ជូននឹងចាប់ផ្តើម។</p>
                    </div>
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
            <a href="{{ route('contact') }}" class="cta-button">
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
        
        // Hide result card initially
        const rc = document.getElementById('price-result');
        if (rc) rc.style.display = 'none';
    });
    
    // All shipping rates: [type][origin][province_en]
    const shippingRates = @json($ratesJson ?? []);
    let currentType = 'import';

    function selectServiceType(type) {
        currentType = type;
        // Toggle button styles
        const imp = document.getElementById('btn-import');
        const exp = document.getElementById('btn-export');
        imp.classList.toggle('active', type === 'import');
        exp.classList.toggle('active', type === 'export');
        // Update port label direction
        const portLabelText = document.getElementById('port-label-text');
        if (portLabelText) {
            portLabelText.textContent = type === 'import'
                ? 'មាត់ច្រកកំពង់ផែ (ទំនិញចូល)'
                : 'មាត់ច្រកកំពង់ផែ (ទំនិញចេញ)';
        }
        // Hide result card when type changes
        const rc = document.getElementById('price-result');
        if (rc) rc.style.display = 'none';
    }

    function calculatePrice() {
        const origin = document.getElementById('origin-port').value;
        const dest   = document.getElementById('destination-province').value;

        if (!origin || !dest) {
            alert('សូមជ្រើសរើសប្រភេទ កំពង់ផែ និងខេត្ត-រាជធានី');
            return;
        }

        const rateData = shippingRates[currentType]
                      && shippingRates[currentType][origin]
                      && shippingRates[currentType][origin][dest];
        if (!rateData) {
            alert('មិនមានតម្លៃសម្រាប់ជម្រើសដែលអ្នកបានជ្រើសរើស');
            return;
        }

        const basePrice = parseFloat(rateData.base_price);
        const fmt = new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        const portLabel = origin === 'sihanoukville' ? 'ព្រះសីហនុ' : 'ភ្នំពេញ';
        const routeText = currentType === 'import'
            ? portLabel + ' → ' + rateData.km
            : rateData.km + ' → ' + portLabel;

        document.getElementById('lbl-type-badge').textContent = currentType === 'import' ? 'Import' : 'Export';
        document.getElementById('lbl-route').textContent      = routeText;
        document.getElementById('val-base').textContent       = '$' + fmt.format(basePrice);
        document.getElementById('val-total').textContent      = '$' + fmt.format(basePrice);
        document.querySelector('.result-total .amount').textContent = '$' + fmt.format(basePrice);

        const resultCard = document.getElementById('price-result');
        if (resultCard) {
            resultCard.style.display = 'block';
            setTimeout(() => {
                resultCard.style.opacity   = '1';
                resultCard.style.transform = 'translateY(0)';
            }, 100);
        }
    }

    function switchTable(type) {
        document.getElementById('tbl-import').style.display = type === 'import' ? 'block' : 'none';
        document.getElementById('tbl-export').style.display = type === 'export' ? 'block' : 'none';
        const impBtn = document.getElementById('tbl-btn-import');
        const expBtn = document.getElementById('tbl-btn-export');
        impBtn.style.color            = type === 'import' ? '#ff6b00' : '#64748b';
        impBtn.style.borderBottomColor= type === 'import' ? '#ff6b00' : 'transparent';
        expBtn.style.color            = type === 'export' ? '#ff6b00' : '#64748b';
        expBtn.style.borderBottomColor= type === 'export' ? '#ff6b00' : 'transparent';
    }
</script>

</body>
</html>