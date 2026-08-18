<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>សេវាកម្មរបស់យើង | Trucking Services</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="សេវាកម្មដឹកជញ្ជូនកម្ពុជា - ដឹកជញ្ជូនពីកំពង់ផែទៅរោងចក្រ តាមខេត្តនីមួយៗ">

    {{-- CSS & Fonts --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;500;600;700&family=Montserrat:wght@600;700;800;900&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/services_header.css') }}">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('images/trucking-logo.png') }}">
</head>
<body>

    @include('partials.header')

    <!-- Hero Section -->
    <section class="services-hero">
        <div class="hero-overlay"></div>
        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    <span><i class="fas fa-star"></i> សេវាកម្មដែលអាចទុកចិត្តបាន</span>
                </div>
                <h1 class="hero-title">
                    <span class="highlight">សេវាកម្មដឹកជញ្ជូន</span> ដែលផ្តល់ជូន
                </h1>
                <p class="hero-description">
                    យើងផ្តល់ជូននូវដំណោះស្រាយដឹកជញ្ជូនពេញលេញ ពីការដឹកជញ្ជូនពីកំពង់ផែទៅរោងចក្រ
                    និងពីរោងចក្រទៅកំពង់ផែតាមគ្រប់ខេត្តក្រុង។
                </p>
            </div>
        </div>

        <!-- Animated elements -->
        <div class="hero-truck"></div>
        <div class="hero-truck truck-2"></div>
        <div class="floating-icon"><i class="fas fa-ship"></i></div>
        <div class="floating-icon icon-2"><i class="fas fa-industry"></i></div>
        <div class="floating-icon icon-3"><i class="fas fa-map-marked-alt"></i></div>
    </section>

    <!-- Services Categories -->
    <section class="categories-section">
        <div class="container">
            <div class="section-header">
                <h6 class="section-subtitle">សេវាកម្មចម្បង</h6>
                <h2 class="section-title">សេវាកម្មដឹកជញ្ជូនរបស់យើង</h2>
                <p class="section-description">
                    យើងផ្តល់ជូនសេវាកម្មដឹកជញ្ជូនពេញលេញ តាមប្រភេទទំនិញ និងទីតាំងដែលអ្នកត្រូវការ
                </p>
            </div>

            <div class="categories-grid">
                <!-- Import from Sihanoukville -->
                <div class="category-card" onclick="showServiceDetails('shv-import')">
                    <div class="category-icon">
                        <i class="fas fa-download"></i>
                    </div>
                    <h3>នាំចូលពីកំពង់ផែស្វ័យយ័តព្រះសីហនុ</h3>
                    <div class="port-badges">
                        <span class="port-badge import-badge">
                            <i class="fas fa-file-import"></i> នាំចូល
                        </span>
                        <span class="port-badge shv-badge">
                            <i class="fas fa-anchor"></i> កំពង់ផែស្វ័យយ័តព្រះសីហនុ
                        </span>
                    </div>
                    <p>ដឹកជញ្ជូនទំនិញនាំចូលពីកំពង់ផែស្វ័យយ័តព្រះសីហនុទៅរោងចក្រតាមគ្រប់ខេត្ត</p>
                    <ul class="category-features">
                        <li><i class="fas fa-check-circle"></i> ដោះស្រាយឯកសារពន្ធដារ</li>
                        <li><i class="fas fa-check-circle"></i> ការដឹកជញ្ជូនរហ័ស</li>
                        <li><i class="fas fa-check-circle"></i> ធានាសុវត្ថិភាពទំនិញ</li>
                    </ul>
                </div>

                <!-- Import from Phnom Penh -->
                <div class="category-card" onclick="showServiceDetails('pp-import')">
                    <div class="category-icon">
                        <i class="fas fa-download"></i>
                    </div>
                    <h3>នាំចូលពីកំពង់ផែស្វ័យយ័តភ្នំពេញ</h3>
                    <div class="port-badges">
                        <span class="port-badge import-badge">
                            <i class="fas fa-file-import"></i> នាំចូល
                        </span>
                        <span class="port-badge pp-badge">
                            <i class="fas fa-ship"></i> កំពង់ផែស្វ័យយ័តភ្នំពេញ
                        </span>
                    </div>
                    <p>ដឹកជញ្ជូនទំនិញនាំចូលពីកំពង់ផែស្វ័យយ័តភ្នំពេញទៅរោងចក្រនៅទូទាំងប្រទេស</p>
                    <ul class="category-features">
                        <li><i class="fas fa-check-circle"></i> ដឹកជញ្ជូនក្នុងរដ្ឋធានី</li>
                        <li><i class="fas fa-check-circle"></i> ការគ្រប់គ្រងសារពើភ័ណ្ឌ</li>
                        <li><i class="fas fa-check-circle"></i> តាមដានជូនជាពេលវេលាពិត</li>
                    </ul>
                </div>

                <!-- Export to Sihanoukville -->
                <div class="category-card" onclick="showServiceDetails('shv-export')">
                    <div class="category-icon">
                        <i class="fas fa-upload"></i>
                    </div>
                    <h3>នាំចេញទៅកំពង់ផែស្វ័យយ័តព្រះសីហនុ</h3>
                    <div class="port-badges">
                        <span class="port-badge export-badge">
                            <i class="fas fa-file-export"></i> នាំចេញ
                        </span>
                        <span class="port-badge shv-badge">
                            <i class="fas fa-anchor"></i> កំពង់ផែស្វ័យយ័តព្រះសីហនុ
                        </span>
                    </div>
                    <p>ដឹកជញ្ជូនទំនិញនាំចេញពីរោងចក្រតាមខេត្តទៅកំពង់ផែស្វ័យយ័តព្រះសីហនុ</p>
                    <ul class="category-features">
                        <li><i class="fas fa-check-circle"></i> ដឹកជញ្ជូនតាមប្រភេទទំនិញ</li>
                        <li><i class="fas fa-check-circle"></i> ដោះស្រាយឯកសារនាំចេញ</li>
                        <li><i class="fas fa-check-circle"></i> ធានាពេលវេលាដឹកជញ្ជូន</li>
                    </ul>
                </div>

                <!-- Export to Phnom Penh -->
                <div class="category-card" onclick="showServiceDetails('pp-export')">
                    <div class="category-icon">
                        <i class="fas fa-upload"></i>
                    </div>
                    <h3>នាំចេញទៅកំពង់ផែស្វ័យយ័តភ្នំពេញ</h3>
                    <div class="port-badges">
                        <span class="port-badge export-badge">
                            <i class="fas fa-file-export"></i> នាំចេញ
                        </span>
                        <span class="port-badge pp-badge">
                            <i class="fas fa-ship"></i> កំពង់ផែស្វ័យយ័តភ្នំពេញ
                        </span>
                    </div>
                    <p>ដឹកជញ្ជូនទំនិញនាំចេញពីរោងចក្រតាមខេត្តទៅកំពង់ផែស្វ័យយ័តភ្នំពេញ</p>
                    <ul class="category-features">
                        <li><i class="fas fa-check-circle"></i> ដឹកជញ្ជូនទំនិញឧស្សាហកម្ម</li>
                        <li><i class="fas fa-check-circle"></i> ដឹកជញ្ជូនទំនិញកសិកម្ម</li>
                        <li><i class="fas fa-check-circle"></i> សេវាកម្មដឹកជញ្ជូនពេញលេញ</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Detailed Services -->
    <section class="detailed-services">

        {{-- Logistics Process Section --}}
        <div class="sh-process-wrap">

            {{-- Header Banner --}}
            <div class="sh-proc-banner">
                <div class="sh-proc-banner-inner container">
                    <span class="sh-proc-tag">នាំចូល &amp; នាំចេញ</span>
                    <h2 class="sh-proc-main-title">សកម្មភាពពេលដឹកជញ្ជូនទំនិញ</h2>
                    <p class="sh-proc-sub">ដឹកជញ្ជូនទំនិញរបស់អ្នកដោយប្រុងប្រយ័ត្ន និងមានប្រសិទ្ធភាព</p>
                    <div class="sh-proc-badges">
                        <span class="sh-proc-badge">សុវត្ថិភាព</span>
                        <span class="sh-proc-badge">គួរទុកចិត្ត</span>
                        <span class="sh-proc-badge">មានប្រសិទ្ធភាព</span>
                        <span class="sh-proc-badge">ទាន់ពេលវេលា</span>
                    </div>
                </div>
            </div>

            {{-- 5 Steps --}}
            <div class="sh-proc-steps-wrap">
                <div class="container">
                    <div class="sh-proc-steps">

                        <div class="sh-proc-step">
                            <div class="sh-proc-step-img-wrap">
                                <img src="{{ asset('images/import image/1.png') }}" alt="Pick up at port" class="sh-proc-step-img">
                                <span class="sh-proc-step-num">01</span>
                            </div>
                            <div class="sh-proc-step-body">
                                <div class="sh-proc-step-icon"><i class="fas fa-anchor"></i></div>
                                <h4 class="sh-proc-step-title">លើកទូរកុងតឺន័រពីកំពង់ផែ</h4>
                                <p class="sh-proc-step-desc">ចូលលើកទូរកុងតឺន័រពីកំពង់ផែនៅពេលដែលនាវាមកដល់។</p>
                            </div>
                        </div>

                        <div class="sh-proc-connector"><i class="fas fa-chevron-right"></i></div>

                        <div class="sh-proc-step">
                            <div class="sh-proc-step-img-wrap">
                                <img src="{{ asset('images/import image/2.png') }}" alt="Arrived at factory" class="sh-proc-step-img">
                                <span class="sh-proc-step-num">02</span>
                            </div>
                            <div class="sh-proc-step-body">
                                <div class="sh-proc-step-icon"><i class="fas fa-industry"></i></div>
                                <h4 class="sh-proc-step-title">រថយន្តមកដល់រោងចក្រ ឬឃ្លាំងទំនិញ</h4>
                                <p class="sh-proc-step-desc">ធ្វើការដឹកទំនិញទៅដល់រោងចក្រ ឬឃ្លាំងទំនិញរបស់អតិថិជន។</p>
                            </div>
                        </div>

                        <div class="sh-proc-connector"><i class="fas fa-chevron-right"></i></div>

                        <div class="sh-proc-step">
                            <div class="sh-proc-step-img-wrap">
                                <img src="{{ asset('images/import image/3.png') }}" alt="Cut seal drop cargo" class="sh-proc-step-img">
                                <span class="sh-proc-step-num">03</span>
                            </div>
                            <div class="sh-proc-step-body">
                                <div class="sh-proc-step-icon"><i class="fas fa-box-open"></i></div>
                                <h4 class="sh-proc-step-title">កាត់ត្រា និទម្លាក់ទំនិញចុះ</h4>
                                <p class="sh-proc-step-desc">កាត់ត្រាទំនិញ​ហើយទម្លាក់ទំនិញចុះនៅទីតាំងគោលដៅ។</p>
                            </div>
                        </div>

                        <div class="sh-proc-connector"><i class="fas fa-chevron-right"></i></div>

                        <div class="sh-proc-step">
                            <div class="sh-proc-step-img-wrap">
                                <img src="{{ asset('images/import image/4.png') }}" alt="Return empty container" class="sh-proc-step-img">
                                <span class="sh-proc-step-num">04</span>
                            </div>
                            <div class="sh-proc-step-body">
                                <div class="sh-proc-step-icon"><i class="fas fa-undo-alt"></i></div>
                                <h4 class="sh-proc-step-title">យកទូរកុងតឺន័រទទេទៅទម្លាក់នៅដេប៉ូ</h4>
                                <p class="sh-proc-step-desc">ត្រឡប់ Container ទទេទៅកំពង់ផែ ឬដេប៉ូវិញទាន់ពេលវេលា។</p>
                            </div>
                        </div>

                        <div class="sh-proc-connector"><i class="fas fa-chevron-right"></i></div>

                        <div class="sh-proc-step">
                            <div class="sh-proc-step-img-wrap">
                                <img src="{{ asset('images/import image/5.png') }}" alt="Receive receipt" class="sh-proc-step-img">
                                <span class="sh-proc-step-num">05</span>
                            </div>
                            <div class="sh-proc-step-body">
                                <div class="sh-proc-step-icon"><i class="fas fa-file-alt"></i></div>
                                <h4 class="sh-proc-step-title">ទទួលវិក្កយបត្រទំនិញ</h4>
                                <p class="sh-proc-step-desc">អតិថិជនទទួលបានវិក្កយបត្រ និងឯកសារបញ្ជាក់ការដឹកទំនិញផ្លូវការ។</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Features Bar --}}
            <div class="sh-proc-features">
                <div class="container">
                    <div class="sh-proc-features-inner">
                        <div class="sh-proc-feat-cols">
                            <div class="sh-proc-feat-col">
                                <div class="sh-proc-feat-icon"><i class="fas fa-shield-alt"></i></div>
                                <div class="sh-proc-feat-text">
                                    <strong>សុវត្ថិភាពជាមុន</strong>
                                    <span>យើងផ្តល់អាទិភាពដល់សុវត្ថិភាពទំនិញរបស់អ្នក ជាងអ្វីទាំងអស់។</span>
                                </div>
                            </div>
                            <div class="sh-proc-feat-col">
                                <div class="sh-proc-feat-icon"><i class="fas fa-users"></i></div>
                                <div class="sh-proc-feat-text">
                                    <strong>ក្រុមការងារមានបទពិសោធន៍</strong>
                                    <span>អ្នកបើករថយន្ត និងក្រុមការងាររបស់យើង មានបទពិសោធន៍ច្រើនឆ្នាំ។</span>
                                </div>
                            </div>
                            <div class="sh-proc-feat-col">
                                <div class="sh-proc-feat-icon"><i class="fas fa-clock"></i></div>
                                <div class="sh-proc-feat-text">
                                    <strong>ដឹកជញ្ជូនទាន់ពេលវេលា</strong>
                                    <span>យើងប្តេជ្ញាដឹកទំនិញរបស់អ្នក ទៅដល់គោលដៅទាន់ពេលរៀងរាល់ដង។</span>
                                </div>
                            </div>
                            <div class="sh-proc-feat-col">
                                <div class="sh-proc-feat-icon"><i class="fas fa-file-contract"></i></div>
                                <div class="sh-proc-feat-text">
                                    <strong>ឯកសារត្រឹមត្រូវ</strong>
                                    <span>ឯកសារ និងលិខិតបញ្ជាក់ទាំងអស់ ត្រូវបានដោះស្រាយដោយភាពត្រឹមត្រូវ។</span>
                                </div>
                            </div>
                        </div>
                        <div class="sh-proc-feat-truck">
                            <img src="{{ asset('images/trucks/1781847524_9115.png') }}" alt="LS Trucking">
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="container">
            <div class="section-header center">
                
                <h2 class="section-title">សេវាកម្មដឹកជញ្ជូនពិសេស</h2>
                <p class="section-description">
                    ស្វែងយល់ពីសេវាកម្មដឹកជញ្ជូនរបស់យើងដែលត្រូវបានរចនាឡើងសម្រាប់តម្រូវការរបស់អ្នក។
                </p>
            </div>

            <div class="svc-cards-grid">

                <a href="{{ route('service.detail', 'import-shv') }}" class="svc-card svc-import">
                    <div class="svc-card-icon"><i class="fas fa-anchor"></i></div>
                    <div class="svc-card-body">
                        <span class="svc-card-badge import">នាំចូល</span>
                        <h4 class="svc-card-title">ពីកំពង់ផែស្វ័យយ័ត<br>ព្រះសីហនុ</h4>
                    </div>
                    <div class="svc-card-arrow"><i class="fas fa-arrow-right"></i></div>
                </a>

                <a href="{{ route('service.detail', 'import-pp') }}" class="svc-card svc-import">
                    <div class="svc-card-icon"><i class="fas fa-ship"></i></div>
                    <div class="svc-card-body">
                        <span class="svc-card-badge import">នាំចូល</span>
                        <h4 class="svc-card-title">ពីកំពង់ផែស្វ័យយ័ត<br>ភ្នំពេញ</h4>
                    </div>
                    <div class="svc-card-arrow"><i class="fas fa-arrow-right"></i></div>
                </a>

                <a href="{{ route('service.detail', 'export-shv') }}" class="svc-card svc-export">
                    <div class="svc-card-icon"><i class="fas fa-anchor"></i></div>
                    <div class="svc-card-body">
                        <span class="svc-card-badge export">នាំចេញ</span>
                        <h4 class="svc-card-title">ទៅកំពង់ផែស្វ័យយ័ត<br>ព្រះសីហនុ</h4>
                    </div>
                    <div class="svc-card-arrow"><i class="fas fa-arrow-right"></i></div>
                </a>

                <a href="{{ route('service.detail', 'export-pp') }}" class="svc-card svc-export">
                    <div class="svc-card-icon"><i class="fas fa-ship"></i></div>
                    <div class="svc-card-body">
                        <span class="svc-card-badge export">នាំចេញ</span>
                        <h4 class="svc-card-title">ទៅកំពង់ផែស្វ័យយ័ត<br>ភ្នំពេញ</h4>
                    </div>
                    <div class="svc-card-arrow"><i class="fas fa-arrow-right"></i></div>
                </a>

            </div>

            <div class="services-tabs" style="display:none;">
                <div class="tab-buttons">

                <div class="tab-content">
                    <div class="tab-pane active" id="shv-import">
                        <!-- Will be loaded dynamically -->
                    </div>

                    <!-- Other tab panes will be loaded dynamically -->
                    <div class="tab-pane" id="pp-import">
                        <!-- Will be loaded dynamically -->
                    </div>
                    <div class="tab-pane" id="shv-export">
                        <!-- Will be loaded dynamically -->
                    </div>
                    <div class="tab-pane" id="pp-export">
                        <!-- Will be loaded dynamically -->
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- Why Choose Us -->
    <section class="why-choose-section">
        <div class="container">
            <div class="section-header">
                <h6 class="section-subtitle">ហេតុអ្វីត្រូវជ្រើសរើសយើង</h6>
                <h2 class="section-title">អត្ថប្រយោជន៍នៃការធ្វើការជាមួយយើង</h2>
                <p class="section-description">
                    ក្រុមហ៊ុនដឹកជញ្ជូនរបស់យើងមានបទពិសោធន៍ជាង 3ឆ្នាំក្នុងវិស័យដឹកជញ្ជូន។
                </p>
            </div>

            <div class="why-choose-grid">
                <div class="why-choose-card">
                    <div class="why-icon">
                        <i class="fas fa-shield-alt"></i>
                        <div class="icon-bg"></div>
                    </div>
                    <h3>សុវត្ថិភាពខ្ពស់បំផុត</h3>
                    <p>រថយន្តទាំងអស់ត្រូវបានប្រើប្រាស់បច្ចេកវិទ្យាតាមដានជូនដែលមានប្រសិទ្ធភាពខ្ពស់បំផុត</p>
                </div>

                <div class="why-choose-card">
                    <div class="why-icon">
                        <i class="fas fa-clock"></i>
                        <div class="icon-bg"></div>
                    </div>
                    <h3>ដឹកជញ្ជូនទាន់ពេល</h3>
                    <p>អត្រាដឹកជញ្ជូនទាន់ពេលរបស់យើងគឺ ៩៨% ដោយសារប្រព័ន្ធរៀបចំផ្លូវដ៏មានប្រសិទ្ធភាព</p>
                </div>

                <div class="why-choose-card">
                    <div class="why-icon">
                        <i class="fas fa-dollar-sign"></i>
                        <div class="icon-bg"></div>
                    </div>
                    <h3>តម្លៃប្រកួតប្រជែង</h3>
                    <p>យើងផ្តល់ជូននូវតម្លៃដ៏ប្រសើរបំផុតដោយមិនប៉ះពាល់ដល់គុណភាពសេវាកម្ម</p>
                </div>

                <div class="why-choose-card">
                    <div class="why-icon">
                        <i class="fas fa-headset"></i>
                        <div class="icon-bg"></div>
                    </div>
                    <h3>គាំទ្រ 24/7</h3>
                    <p>ក្រុមជំនួយការរបស់យើងត្រៀមខ្លួនជានិច្ចដើម្បីឆ្លើយតបនឹងសំណួររបស់អ្នក</p>
                </div>

                <div class="why-choose-card">
                    <div class="why-icon">
                        <i class="fas fa-map-marked-alt"></i>
                        <div class="icon-bg"></div>
                    </div>
                    <h3>គ្របដណ្តប់ទូទាំងប្រទេស</h3>
                    <p>សេវាកម្មរបស់យើងមាននៅទូទាំងប្រទេសកម្ពុជា គ្រប់ខេត្តក្រុង</p>
                </div>

                <div class="why-choose-card">
                    <div class="why-icon">
                        <i class="fas fa-file-contract"></i>
                        <div class="icon-bg"></div>
                    </div>
                    <h3>សេវាកម្មពន្ធដារ</h3>
                    <p>យើងផ្តល់ជូនសេវាកម្មដោះស្រាយឯកសារពន្ធដារនាំចូល/នាំចេញ</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section class="process-section">
        <div class="container">
            <div class="section-header center">
                <h6 class="section-subtitle">ដំណើរការធ្វើការ</h6>
                <h2 class="section-title">តើយើងធ្វើការយ៉ាងដូចម្តេច?</h2>
                <p class="section-description">
                    ដំណើរការធ្វើការសាមញ្ញរបស់យើងធានាបាននូវការដឹកជញ្ជូនដោយរលូន និងគ្មានការរំខាន។
                </p>
            </div>

            <div class="process-timeline">
                <div class="process-step">
                    <div class="step-number">01</div>
                    <div class="step-content">
                        <div class="step-icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <h3>ស្នើសុំការប្រឹក្សាយោបល់</h3>
                        <p>ទាក់ទងយើងខ្ញុំដើម្បីពិភាក្សាអំពីតម្រូវការដឹកជញ្ជូនរបស់អ្នក</p>
                    </div>
                </div>

                <div class="process-step">
                    <div class="step-number">02</div>
                    <div class="step-content">
                        <div class="step-icon">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <h3>ទទួលការវាយតម្លៃ</h3>
                        <p>យើងនឹងវាយតម្លៃតម្រូវការរបស់អ្នក និងផ្តល់តម្លៃប្រកួតប្រជែង</p>
                    </div>
                </div>

                <div class="process-step">
                    <div class="step-number">03</div>
                    <div class="step-content">
                        <div class="step-icon">
                            <i class="fas fa-file-contract"></i>
                        </div>
                        <h3>ចុះកិច្ចសន្យា</h3>
                        <p>យើងនឹងរៀបចំកិច្ចសន្យាជាផ្លូវការជាមួយលក្ខខណ្ឌច្បាស់លាស់</p>
                    </div>
                </div>

                <div class="process-step">
                    <div class="step-number">04</div>
                    <div class="step-content">
                        <div class="step-icon">
                            <i class="fas fa-ship"></i>
                        </div>
                        <h3>ទទួលទំនិញពីកំពង់ផែ</h3>
                        <p>ក្រុមរបស់យើងនឹងទទួលទំនិញពីកំពង់ផែស្វ័យយ័តព្រះសីហនុ ឬភ្នំពេញ</p>
                    </div>
                </div>

                <div class="process-step">
                    <div class="step-number">05</div>
                    <div class="step-content">
                        <div class="step-icon">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <h3>ដឹកជញ្ជូនទៅរោងចក្រ</h3>
                        <p>ដឹកជញ្ជូនទំនិញទៅរោងចក្រតាមខេត្តដែលបានកំណត់</p>
                    </div>
                </div>

                <div class="process-step">
                    <div class="step-number">06</div>
                    <div class="step-content">
                        <div class="step-icon">
                            <i class="fas fa-warehouse"></i>
                        </div>
                        <h3>ដល់រោងចក្រ ឬឃ្លាំង និងបញ្ចុះទំនិញ</h3>
                        <p>នៅពេលរថយន្តមកដល់រោងចក្រ ឬឃ្លាំងគោលដៅ អ្នកបើកបរនឹងសហការជាមួយអ្នកគ្រប់គ្រងទីតាំង ដើម្បីបញ្ចុះ និងផ្ទៀងផ្ទាត់ទំនិញឲ្យត្រូវតាមបញ្ជី មុននឹងប្រគល់ជូនអតិថិជនដោយផ្ទាល់</p>
                    </div>
                </div>

                <div class="process-step">
                    <div class="step-number">07</div>
                    <div class="step-content">
                        <div class="step-icon">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <h3>ការដឹកជញ្ជូនបានសម្រេច</h3>
                        <p>ទំនិញរបស់អ្នកត្រូវបានដឹកជញ្ជូនទៅកាន់ទីតាំងដោយសុវត្ថិភាព</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2 class="cta-title">ត្រៀមខ្លួនដើម្បីចាប់ផ្តើមដឹកជញ្ជូនរបស់អ្នក?</h2>
                <p class="cta-description">
                    ទាក់ទងយើងខ្ញុំសម្រាប់ការប្រឹក្សាយោបល់ដោយឥតគិតថ្លៃ និងទទួលបានតម្លៃប្រកួតប្រជែងសម្រាប់តម្រូវការដឹកជញ្ជូនរបស់អ្នក។
                </p>
                <div class="cta-buttons">
                    <a href="{{ route('contact') }}" class="cta-button primary">
                        <i class="fas fa-phone-alt"></i>
                        ទូរស័ព្ទទៅយើងខ្ញុំ
                    </a>
                </div>
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-clock"></i>
                        <span>ម៉ោងធ្វើការ: ថ្ងៃច័ន្ទ-សៅរ៍ (៨:០០-១៧:០០)</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>ទីតាំង: ផ្លូវបឹងទទឹងថ្ងៃ២, ខណ្ឌជ្រោយចង្វា, រាជធានីភ្នំពេញ</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('partials.footer')

    <!-- JavaScript -->
    <script src="{{ asset('js/services_header.js') }}"></script>
    <script>
        // Service data
        const serviceData = {
            'shv-import': {
                title: 'ដឹកជញ្ជូននាំចូលពីកំពង់ផែស្វ័យយ័តព្រះសីហនុ',
                icon: 'fas fa-file-import',
                type: 'នាំចូល',
                port: 'ស្វ័យយ័តព្រះសីហនុ',
                description: 'សេវាកម្មដឹកជញ្ជូនទំនិញនាំចូលពីកំពង់ផែស្វ័យយ័តព្រះសីហនុទៅរោងចក្រតាមខេត្តនីមួយៗ។',
                features: [
                    'ដោះស្រាយឯកសារពន្ធដារនាំចូលដោយអ្នកជំនាញ',
                    'ការដឹកជញ្ជូនរហ័ស និងមានប្រសិទ្ធភាព',
                    'ធានាសុវត្ថិភាពទំនិញ',
                    'តាមដានជូនជាពេលវេលាពិត',
                    'ធានាពេលវេលាដឹកជញ្ជូន'
                ],
                time: '២-៤ ថ្ងៃ',
                coverage: 'ទូទាំងប្រទេសកម្ពុជា',
                price: 'ពី $៥០០ ឡើងទៅ'
            },
            'pp-import': {
                title: 'ដឹកជញ្ជូននាំចូលពីកំពង់ផែភ្នំពេញ',
                icon: 'fas fa-file-import',
                type: 'នាំចូល',
                port: 'ភ្នំពេញ',
                description: 'សេវាកម្មដឹកជញ្ជូនទំនិញនាំចូលពីកំពង់ផែភ្នំពេញទៅរោងចក្រនៅទូទាំងប្រទេស។',
                features: [
                    'ដឹកជញ្ជូនក្នុងរដ្ឋធានី',
                    'ការគ្រប់គ្រងសារពើភ័ណ្ឌ',
                    'តាមដានជូនជាពេលវេលាពិត',
                    'ដោះស្រាយឯកសារពន្ធដារលឿន',
                    'សេវាកម្មចែកចាយចុងក្រោយ'
                ],
                time: '១-២ ថ្ងៃ',
                coverage: 'ភ្នំពេញ និងខេត្តជិតខាង',
                price: 'ពី $៣០០ ឡើងទៅ'
            },
            'shv-export': {
                title: 'ដឹកជញ្ជូននាំចេញទៅកំពង់ផែស៊ីហានុវិល',
                icon: 'fas fa-file-export',
                type: 'នាំចេញ',
                port: 'ស៊ីហានុវិល',
                description: 'សេវាកម្មដឹកជញ្ជូនទំនិញនាំចេញពីរោងចក្រតាមខេត្តទៅកំពង់ផែស៊ីហានុវិល។',
                features: [
                    'ដឹកជញ្ជូនតាមប្រភេទទំនិញ',
                    'ដោះស្រាយឯកសារនាំចេញពេញលេញ',
                    'ធានាពេលវេលាដឹកជញ្ជូន',
                    'ការព្រមានមុនពេលចេញដំណើរ',
                    'ការធានាសុវត្ថិភាពទំនិញ'
                ],
                time: '៣-៥ ថ្ងៃ',
                coverage: 'ទូទាំងប្រទេសកម្ពុជា',
                price: 'ពី $៦០០ ឡើងទៅ'
            },
            'pp-export': {
                title: 'ដឹកជញ្ជូននាំចេញទៅកំពង់ផែភ្នំពេញ',
                icon: 'fas fa-file-export',
                type: 'នាំចេញ',
                port: 'ភ្នំពេញ',
                description: 'សេវាកម្មដឹកជញ្ជូនទំនិញនាំចេញពីរោងចក្រតាមខេត្តទៅកំពង់ផែភ្នំពេញ។',
                features: [
                    'ដឹកជញ្ជូនទំនិញឧស្សាហកម្ម',
                    'ដឹកជញ្ជូនទំនិញកសិកម្ម',
                    'សេវាកម្មដឹកជញ្ជូនពេញលេញ',
                    'ការគ្រប់គ្រងសារពើភ័ណ្ឌ',
                    'របាយការណ៍ដឹកជញ្ជូនលម្អិត'
                ],
                time: '២-៣ ថ្ងៃ',
                coverage: 'ភ្នំពេញ និងតំបន់ជុំវិញ',
                price: 'ពី $៤០០ ឡើងទៅ'
            }
        };

        // Tab data for detailed services
        const tabData = {
            'pp-import': {
                title: "ដឹកជញ្ជូននាំចូលពីកំពង់ផែភ្នំពេញ",
                description: "សេវាកម្មដឹកជញ្ជូនទំនិញនាំចូលពីកំពង់ផែភ្នំពេញទៅរោងចក្រនៅទូទាំងប្រទេស។ យើងផ្តល់ជូននូវដំណោះស្រាយដឹកជញ្ជូនពេញលេញ ពីការទទួលទំនិញពីកំពង់ផែ រហូតដល់ការចែកចាយទៅកន្លែងដែលត្រូវការ។",
                image: "https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80",
                features: [
                    {
                        icon: "fas fa-warehouse",
                        title: "ការគ្រប់គ្រងសារពើភ័ណ្ឌ",
                        description: "ប្រព័ន្ធគ្រប់គ្រងសារពើភ័ណ្ឌទំនើបសម្រាប់ទំនិញនាំចូល"
                    },
                    {
                        icon: "fas fa-truck",
                        title: "ដឹកជញ្ជូនក្នុងរដ្ឋធានី",
                        description: "សេវាកម្មដឹកជញ្ជូនរហ័សក្នុងតំបន់ភ្នំពេញ"
                    },
                    {
                        icon: "fas fa-map-marked-alt",
                        title: "តាមដានជូនជាពេលវេលាពិត",
                        description: "ប្រព័ន្ធតាមដានជូន GPS សម្រាប់ការតាមដានជូនភ្លាមៗ"
                    },
                    {
                        icon: "fas fa-file-contract",
                        title: "ដោះស្រាយឯកសារពន្ធដារ",
                        description: "ការដោះស្រាយឯកសារពន្ធដារនាំចូលដោយអ្នកជំនាញ"
                    }
                ]
            },
            'shv-export': {
                title: "ដឹកជញ្ជូននាំចេញទៅកំពង់ផែស្វ័យយ័តព្រះសីហនុ",
                description: "សេវាកម្មដឹកជញ្ជូនទំនិញនាំចេញពីរោងចក្រតាមខេត្តទៅកំពង់ផែស្វ័យយ័តព្រះសីហនុ យើងផ្តល់ជូននូវដំណោះស្រាយដឹកជញ្ជូនពេញលេញ ពីការទទួលទំនិញពីរោងចក្រ រហូតដល់ការដឹកជញ្ជូនទៅកំពង់ផែ។",
                image: "https://images.unsplash.com/photo-1553729784-e91953dec042?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80",
                features: [
                    {
                        icon: "fas fa-boxes",
                        title: "ដឹកជញ្ជូនតាមប្រភេទទំនិញ",
                        description: "សេវាកម្មដឹកជញ្ជូនសម្រាប់ទំនិញគ្រប់ប្រភេទ"
                    },
                    {
                        icon: "fas fa-passport",
                        title: "ដោះស្រាយឯកសារនាំចេញ",
                        description: "ការដោះស្រាយឯកសារនាំចេញពេញលេញ"
                    },
                    {
                        icon: "fas fa-clock",
                        title: "ធានាពេលវេលាដឹកជញ្ជូន",
                        description: "ធានាពេលវេលាដឹកជញ្ជូនទៅកំពង់ផែ"
                    },
                    {
                        icon: "fas fa-shield-alt",
                        title: "ការធានាសុវត្ថិភាព",
                        description: "ការធានាសុវត្ថិភាពទំនិញពេញដំណើរ"
                    }
                ]
            },
            'pp-export': {
                title: "ដឹកជញ្ជូននាំចេញទៅកំពង់ផែភ្នំពេញ",
                description: "សេវាកម្មដឹកជញ្ជូនទំនិញនាំចេញពីរោងចក្រតាមខេត្តទៅកំពង់ផែភ្នំពេញ។ យើងផ្តល់ជូននូវដំណោះស្រាយដឹកជញ្ជូនពេញលេញ ពីការទទួលទំនិញពីរោងចក្រ រហូតដល់ការដឹកជញ្ជូនទៅកំពង់ផែភ្នំពេញ។",
                image: "https://images.unsplash.com/photo-1549399542-7e3f8b79c341?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80",
                features: [
                    {
                        icon: "fas fa-industry",
                        title: "ដឹកជញ្ជូនទំនិញឧស្សាហកម្ម",
                        description: "សេវាកម្មដឹកជញ្ជូនសម្រាប់ផលិតផលឧស្សាហកម្ម"
                    },
                    {
                        icon: "fas fa-seedling",
                        title: "ដឹកជញ្ជូនទំនិញកសិកម្ម",
                        description: "សេវាកម្មដឹកជញ្ជូនសម្រាប់ផលិតផលកសិកម្ម"
                    },
                    {
                        icon: "fas fa-clipboard-list",
                        title: "សេវាកម្មដឹកជញ្ជូនពេញលេញ",
                        description: "ដំណោះស្រាយដឹកជញ្ជូនពីចាប់ផ្តើមដល់បញ្ចប់"
                    },
                    {
                        icon: "fas fa-chart-line",
                        title: "របាយការណ៍ដឹកជញ្ជូន",
                        description: "របាយការណ៍ដឹកជញ្ជូនលម្អិតសម្រាប់អតិថិជន"
                    }
                ]
            }
        };

        // Show service details modal
        function showServiceDetails(serviceId) {
            const service = serviceData[serviceId];
            if (!service) return;

            // Add click animation to card
            const card = event.currentTarget;
            card.classList.add('clicked');
            setTimeout(() => card.classList.remove('clicked'), 300);

            // Update modal content
            document.getElementById('modalTitle').innerHTML = `
                <i class="${service.icon}"></i> ${service.title}
            `;

            document.getElementById('modalBody').innerHTML = `
                <div class="service-route">
                    <i class="fas fa-${service.type === 'នាំចូល' ? 'download' : 'upload'}"></i>
                    <span>${service.type} ${service.type === 'នាំចូល' ? 'ពី' : 'ទៅ'} កំពង់ផែ${service.port}</span>
                </div>

                <p>${service.description}</p>

                <div class="modal-info-grid">
                    <div class="modal-info-row">
                        <span class="modal-label">
                            <i class="fas fa-clock"></i> រយៈពេល
                        </span>
                        <span class="modal-value">${service.time}</span>
                    </div>
                    <div class="modal-info-row">
                        <span class="modal-label">
                            <i class="fas fa-map-marker-alt"></i> តំបន់គ្របដណ្តប់
                        </span>
                        <span class="modal-value">${service.coverage}</span>
                    </div>
                    <div class="modal-info-row">
                        <span class="modal-label">
                            <i class="fas fa-dollar-sign"></i> តម្លៃប្រហែល
                        </span>
                        <span class="modal-value">${service.price}</span>
                    </div>
                    <div class="modal-info-row">
                        <span class="modal-label">
                            <i class="fas fa-truck"></i> ប្រភេទសេវាកម្ម
                        </span>
                        <span class="modal-value">${service.type}</span>
                    </div>
                </div>

                <div class="modal-features">
                    <h4><i class="fas fa-star"></i> លក្ខណៈពិសេស</h4>
                    <ul>
                        ${service.features.map(feature => `
                            <li><i class="fas fa-check"></i> ${feature}</li>
                        `).join('')}
                    </ul>
                </div>

                <div class="modal-actions">
                    <button class="modal-btn primary" onclick="showBookingModal('${serviceId}')">
                        <i class="fas fa-calendar-check"></i> កក់សេវាកម្មនេះ
                    </button>
                    <button class="modal-btn secondary" onclick="closeModal()">
                        <i class="fas fa-times"></i> បិទ
                    </button>
                </div>
            `;

            // Show modal
            document.getElementById('serviceModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Show booking modal
        function showBookingModal(type) {
            let title = '';
            let description = '';

            if (type === 'basic') {
                title = 'សេវាកម្មដឹកជញ្ជូនមូលដ្ឋាន';
                description = 'សម្រាប់អាជីវកម្មតូច - $២៩៩/ខែ';
            } else if (type === 'standard') {
                title = 'សេវាកម្មដឹកជញ្ជូនស្តង់ដារ';
                description = 'សម្រាប់អាជីវកម្មមធ្យម - $៧៩៩/ខែ';
            } else if (type === 'enterprise') {
                title = 'សេវាកម្មដឹកជញ្ជូនក្រុមហ៊ុន';
                description = 'សម្រាប់អាជីវកម្មធំ - $១,៩៩៩/ខែ';
            } else {
                const service = serviceData[type];
                if (service) {
                    title = service.title;
                    description = `តម្លៃប្រហែល: ${service.price}`;
                }
            }

            document.getElementById('bookingBody').innerHTML = `
                <h4 style="margin-bottom: 10px;">${title}</h4>
                <p style="color: #666; margin-bottom: 20px;">${description}</p>

                <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin: 20px 0;">
                    <p style="margin-bottom: 15px;"><strong>សូមបំពេញព័ត៌មានខាងក្រោម:</strong></p>

                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">ឈ្មោះអ្នកប្រើប្រាស់</label>
                        <input type="text" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" placeholder="បញ្ចូលឈ្មោះរបស់អ្នក">
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">លេខទូរស័ព្ទ</label>
                        <input type="tel" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" placeholder="បញ្ចូលលេខទូរស័ព្ទ">
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">អ៊ីមែល</label>
                        <input type="email" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" placeholder="បញ្ចូលអ៊ីមែល">
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">ព័ត៌មានបន្ថែម</label>
                        <textarea style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; min-height: 100px;" placeholder="បញ្ចូលព័ត៌មានបន្ថែមអំពីតម្រូវការរបស់អ្នក"></textarea>
                    </div>
                </div>

                <div class="modal-actions">
                    <button class="modal-btn primary" onclick="submitBooking()">
                        <i class="fas fa-paper-plane"></i> ដាក់ស្នើសុំ
                    </button>
                    <button class="modal-btn secondary" onclick="closeBookingModal()">
                        <i class="fas fa-times"></i> បោះបង់
                    </button>
                </div>
            `;

            document.getElementById('bookingModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Submit booking
        function submitBooking() {
            closeBookingModal();
            showNotification('ការកក់សេវាកម្មត្រូវបានដាក់ស្នើជោគជ័យ! យើងនឹងទាក់ទងអ្នកក្នុងរយៈពេល ២៤ម៉ោង។');
        }

        // Open tab in detailed services
        function openTab(tabId) {
            // Update tab buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.currentTarget.classList.add('active');

            // Update tab panes
            document.querySelectorAll('.tab-pane').forEach(pane => {
                pane.classList.remove('active');
            });

            const tabPane = document.getElementById(tabId);
            if (tabPane.innerHTML.trim() === '') {
                // Load tab content dynamically
                const data = tabData[tabId];
                if (data) {
                    const featuresHTML = data.features.map(feature => `
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="${feature.icon}"></i>
                            </div>
                            <h4>${feature.title}</h4>
                            <p>${feature.description}</p>
                        </div>
                    `).join('');

                    tabPane.innerHTML = `
                        <div class="tab-pane-content">
                            <div class="tab-image">
                                <img src="${data.image}" alt="${data.title}">
                                <div class="image-badge">
                                    <span><i class="fas fa-${tabId.includes('import') ? 'file-import' : 'file-export'}"></i> ${tabId.includes('shv') ? 'កំពង់ផែស្វ័យយ័តព្រះសីហនុ' : 'កំពង់ផែស្វយ័យ័តភ្នំពេញ'}</span>
                                </div>
                            </div>
                            <div class="tab-info">
                                <h3>${data.title}</h3>
                                <p>${data.description}</p>
                                <div class="features-grid">
                                    ${featuresHTML}
                                </div>
                                <button onclick="window.location.href='{{ route('contact') }}'" class="cta-button">
                                    <span>ស្នើសុំការប្រឹក្សាយោបល់</span>
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    `;
                }
            }

            tabPane.classList.add('active');
        }

        // Show notification
        function showNotification(message, isError = false) {
            const notification = document.createElement('div');
            notification.className = `notification ${isError ? 'error' : ''}`;
            notification.innerHTML = `
                <i class="fas fa-${isError ? 'exclamation-triangle' : 'check-circle'}"></i>
                <span>${message}</span>
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Modal functions
        function closeModal() {
            document.getElementById('serviceModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        function showContactModal() {
            document.getElementById('contactModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeContactModal() {
            document.getElementById('contactModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        function showEmailModal() {
            document.getElementById('emailModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeEmailModal() {
            document.getElementById('emailModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        function closeBookingModal() {
            document.getElementById('bookingModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Close modals when clicking outside
        document.addEventListener('click', function(event) {
            const modals = document.querySelectorAll('.service-modal');
            modals.forEach(modal => {
                if (event.target === modal) {
                    modal.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
            });
        });

        // Close modals with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const modals = document.querySelectorAll('.service-modal');
                modals.forEach(modal => {
                    modal.classList.remove('active');
                });
                document.body.style.overflow = 'auto';
            }
        });

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Set language to Khmer
            document.documentElement.lang = 'km';

            // Animate stats counter
            function animateCounter(element, target) {
                let current = 0;
                const increment = target / 50;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        element.textContent = target;
                        clearInterval(timer);
                    } else {
                        element.textContent = Math.floor(current);
                    }
                }, 30);
            }

            // Animate hero stats
            const heroStats = document.querySelectorAll('.hero-stats .stat-number');
            heroStats.forEach(stat => {
                const target = parseInt(stat.textContent);
                if (!isNaN(target)) {
                    animateCounter(stat, target);
                }
            });

            // Add click effect to all category cards
            document.querySelectorAll('.category-card').forEach(card => {
                card.addEventListener('click', function() {
                    this.classList.add('clicked');
                    setTimeout(() => this.classList.remove('clicked'), 300);
                });
            });
        });
    </script>

</body>
</html>