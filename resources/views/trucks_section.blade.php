{{-- resources/views/trucks_section.blade.php --}}
<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>អំពីឡាន | Trucking System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;500;600;700&family=Montserrat:wght@600;700;800;900&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/trucks_section.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
@include('partials.header')

@if(session('booking_success'))
<div id="bookingSuccessAlert" style="position:fixed;top:90px;left:50%;transform:translateX(-50%);z-index:9999;background:linear-gradient(135deg,#10b981,#059669);color:#fff;padding:14px 28px;border-radius:12px;box-shadow:0 6px 20px rgba(16,185,129,0.4);font-family:'Kantumruy Pro',sans-serif;font-size:0.95rem;font-weight:600;display:flex;align-items:center;gap:10px;max-width:90vw;text-align:center;">
    <i class="fas fa-check-circle" style="font-size:1.2rem;"></i>
    {{ session('booking_success') }}
</div>
<script>setTimeout(()=>{ const a=document.getElementById('bookingSuccessAlert'); if(a) a.remove(); }, 5000);</script>
@endif

@if($errors->any())
<div id="bookingErrorAlert" style="position:fixed;top:90px;left:50%;transform:translateX(-50%);z-index:99999;
     background:{{ $errors->has('container_number') ? 'linear-gradient(135deg,#f97316,#ea580c)' : 'linear-gradient(135deg,#ef4444,#dc2626)' }};
     color:#fff;padding:14px 24px;border-radius:12px;
     box-shadow:0 6px 20px {{ $errors->has('container_number') ? 'rgba(249,115,22,0.45)' : 'rgba(239,68,68,0.4)' }};
     font-family:'Kantumruy Pro',sans-serif;font-size:0.9rem;font-weight:600;max-width:92vw;text-align:center;
     display:flex;align-items:center;gap:10px;">
    <i class="fas {{ $errors->has('container_number') ? 'fa-barcode' : 'fa-exclamation-circle' }}" style="font-size:1.2rem;flex-shrink:0;"></i>
    <span>@foreach($errors->all() as $e) {{ $e }} @endforeach</span>
</div>
<script>
    // Reopen booking modal so user can fix the error
    document.addEventListener('DOMContentLoaded', function(){
        document.getElementById('bookingModalOverlay').classList.add('open');
        document.body.style.overflow = 'hidden';
        setTimeout(()=>{ const a=document.getElementById('bookingErrorAlert'); if(a){ a.style.transition='opacity .4s'; a.style.opacity='0'; setTimeout(()=>a.remove(),400); } }, 10000);
    });
</script>
@endif

<!-- Trucks Section -->
<section class="trucks_section">
    <div class="container">
        <!-- Decorative Background Elements -->
        <div class="bg-elements">
            <div class="element element-1"></div>
            <div class="element element-2"></div>
            <div class="element element-3"></div>
        </div>
        
        <!-- Section Title -->
        <div class="section-title">
            <h2>ក្រុមរថយន្តរបស់យើង</h2>
            <p>ជ្រើសរើសរថយន្តដែលសមស្របសម្រាប់តម្រូវការដឹកជញ្ជូនរបស់អ្នក</p>
        </div>

        <!-- Statistics Bar -->
        <div class="trucks-stats">
            <div class="stat-item">
                <span class="stat-number" id="totalTrucks">{{ $trucks->count() }}</span>
                <span class="stat-label">រថយន្តសរុប</span>
            </div>
            <div class="stat-item">
                <span class="stat-number" id="availableTrucks">{{ $availableCount }}</span>
                <span class="stat-label">រថយន្តអាចប្រើប្រាស់</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">25</span>
                <span class="stat-label">ខេត្តនានា</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">98%</span>
                <span class="stat-label">ការដឹកជញ្ជូនជោគជ័យ</span>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="trucks-filter">
            <div class="filter-row">
                <div class="filter-group">
                    <label for="truckType"><i class="fas fa-truck"></i> ប្រភេទរថយន្ត</label>
                    <select id="truckType" class="filter-select">
                        <option value="all">ទាំងអស់</option>
                        <option value="fuso">Mitsubishi FUSO</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="truckSize"><i class="fas fa-weight-hanging"></i> ទម្ងន់</label>
                    <select id="truckSize" class="filter-select">
                        <option value="all">ទាំងអស់</option>
                        <option value="small">តូច (១០-១២តោន)</option>
                        <option value="medium">មធ្យម (១៣-១៥តោន)</option>
                        <option value="large">ធំ (១៦តោន↑)</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="truckStatus"><i class="fas fa-circle"></i> ស្ថានភាព</label>
                    <select id="truckStatus" class="filter-select">
                        <option value="all">ទាំងអស់</option>
                        <option value="available">អាចប្រើប្រាស់បាន</option>
                        <option value="busy">រវល់</option>
                        <option value="maintenance">កំពុងថែទាំ</option>
                    </select>
                </div>
                <div class="filter-actions">
                    <button class="reset-filter">
                        <i class="fas fa-redo"></i> កំណត់ឡើងវិញ
                    </button>
                </div>
            </div>
        </div>

        <!-- Trucks Grid — dynamic from database, random order -->
        <div class="trucks-grid" id="trucksGrid">
            @forelse($trucks as $index => $truck)
            @php
                $nameLower  = strtolower($truck->truck_name ?? '');
                $typeKey    = str_contains($nameLower, 'fuso')  ? 'fuso'  :
                              (str_contains($nameLower, 'isuzu') ? 'isuzu' :
                              (str_contains($nameLower, 'hino')  ? 'hino'  : 'other'));
                $cap        = (float)($truck->capacity_ton ?? 0);
                $sizeKey    = $cap >= 15 ? 'large' : ($cap >= 12 ? 'medium' : 'small');
                $isExtra    = $index >= 6;
                $truckStatus    = $truck->status ?? 'available';
                $hasActiveDriver = $truck->drivers->where('status', 'active')->count() > 0;
                $canBook        = $truckStatus === 'available' && $hasActiveDriver;
                $statusLabel    = match($truckStatus) {
                    'in_progress' => 'កំពុងដំណើរការ',
                    'delivering'  => 'កំពុងដឹកទំនិញ',
                    'maintenance' => 'កំពុងជួសជុល',
                    default       => 'ទំនេរ',
                };
                $blockReason = !$canBook ? (
                    $truckStatus === 'in_progress' ? 'រថយន្តកំពុងដំណើរការលើការកក់មួយ' :
                    ($truckStatus === 'delivering'  ? 'រថយន្តកំពុងដឹកទំនិញ' :
                    ($truckStatus === 'maintenance' ? 'រថយន្តកំពុងជួសជុល' :
                                                     'អ្នកបើកបរមិនអាចប្រើប្រាស់'))
                ) : null;
            @endphp
            <div class="truck-card{{ $isExtra ? ' truck-extra' : '' }}"
                 style="{{ $isExtra ? 'display:none;' : '' }}"
                 data-type="{{ $typeKey }}"
                 data-size="{{ $sizeKey }}"
                 data-status="{{ $truckStatus }}">

                <div class="truck-image">
                    @if($truck->truck_picture && file_exists(public_path($truck->truck_picture)))
                        <img src="{{ asset($truck->truck_picture) }}"
                             alt="{{ $truck->truck_name }}" loading="lazy">
                    @else
                        <img src="{{ asset('images/truck-bg.jpg') }}"
                             alt="{{ $truck->truck_name }}" loading="lazy">
                    @endif
                    <span class="truck-status status-{{ $truckStatus }}">{{ $statusLabel }}</span>
                    <button class="truck-favorite" onclick="toggleFavorite(this)">
                        <i class="far fa-heart"></i>
                    </button>
                </div>

                <div class="truck-content">
                    <div class="truck-info">
                        <div class="info-row">
                            <span class="info-label">ស្លាកលេខរថយន្ត</span>
                            <span class="info-value">{{ $truck->plate_number ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">ឈ្មោះរថយន្ត</span>
                            <span class="info-value">{{ $truck->truck_name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">ចំនួនភ្លៅរថយន្ត</span>
                            <span class="info-value">{{ $truck->truck_size ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">ពណ៌រថយន្ត</span>
                            <span class="info-value">{{ $truck->truck_color ?? '—' }}</span>
                        </div>
                        @if($truck->capacity_ton)
                        <div class="info-row">
                            <span class="info-label">ទម្ងន់រថយន្ត</span>
                            <span class="info-value">{{ $truck->capacity_ton }} តោន</span>
                        </div>
                        @endif
                    </div>

                    @if(!$canBook)
                    <div style="display:flex;align-items:center;gap:6px;padding:5px 10px;
                                background:#fee2e2;border-radius:8px;margin-bottom:8px;">
                        <i class="fas fa-ban" style="color:#dc2626;font-size:0.75rem;"></i>
                        <span style="font-size:0.75rem;color:#991b1b;font-weight:600;">
                            {{ $blockReason }}
                        </span>
                    </div>
                    @endif

                    <div class="truck-actions">
                        <button class="view-details" onclick="showTruckDetails({{ $index }})">
                            <i class="fas fa-eye"></i> មើលលម្អិត
                        </button>
                        @if($canBook)
                        <button class="quick-book" title="កក់រថយន្ត"
                                onclick="quickBook('{{ $truck->plate_number }}')">
                            <i class="fas fa-calendar-check"></i>
                        </button>
                        @else
                        <button class="quick-book"
                                style="opacity:0.4;cursor:not-allowed;background:#e2e8f0;
                                       color:#94a3b8;border-color:#e2e8f0;"
                                title="{{ $blockReason }}" disabled>
                            <i class="fas fa-ban"></i>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div style="grid-column:1/-1;text-align:center;padding:60px;color:#94a3b8;">
                <i class="fas fa-truck" style="font-size:3rem;display:block;margin-bottom:12px;opacity:0.3;"></i>
                <p>មិនទាន់មានរថយន្ត</p>
            </div>
            @endforelse
        </div>

        {{-- See More button — only shown when there are more than 6 trucks --}}
        @if($trucks->count() > 6)
        <div style="text-align:center;margin-top:28px;" id="seeMoreWrap">
            <button onclick="showMoreTrucks()" class="see-more-btn"
                    style="display:inline-flex;align-items:center;gap:10px;
                           padding:13px 32px;background:linear-gradient(135deg,#FF6B00,#e55a00);
                           color:#fff;border:none;border-radius:12px;cursor:pointer;
                           font-family:'Kantumruy Pro',sans-serif;font-size:0.95rem;font-weight:700;
                           box-shadow:0 4px 16px rgba(255,107,0,0.35);transition:all 0.2s;">
                <i class="fas fa-plus-circle"></i>
                មើលបន្ថែម &nbsp;
                <span style="background:rgba(255,255,255,0.25);padding:2px 10px;border-radius:20px;
                              font-family:'Montserrat',sans-serif;font-size:0.85rem;font-weight:800;">
                    {{ $trucks->count() - 6 }} ទៀត
                </span>
            </button>
        </div>
        @endif

        <!-- CTA Section -->
            <div class="trucks-cta">
                <div class="cta-content">
                    <h2 class="cta-title">មិនទាន់ឃើញរថយន្តដែលអ្នកចង់ប្រើទេ?</h2>
                    <p class="cta-description">ទាក់ទងមកកាន់យើងខ្ញុំ យើងមានរថយន្តជាច្រើនទៀតសម្រាប់តម្រូវការរបស់អ្នក</p>
                    <div class="cta-buttons">
                        {{-- Option 1: Use direct URLs (most reliable) --}}
                        <a href="/contact" class="cta-button primary">
                            <i class="fas fa-phone-alt"></i> ទាក់ទងយើងខ្ញុំ
                        </a>
                        <a href="/services" class="cta-button secondary">
                            <i class="fas fa-info-circle"></i> សេវាកម្មរបស់យើង
                        </a>
                        
                        {{-- Or Option 2: Contact buttons that always work --}}
                        <div class="contact-info" style="margin-top: 20px; display: flex; gap: 15px; flex-wrap: wrap;">
                            <a href="tel:+85512345678" class="contact-link">
                                <i class="fas fa-phone"></i> +855 12 345 678
                            </a>
                            <a href="https://wa.me/85512345678" target="_blank" class="contact-link">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                            <a href="mailto:info@trucking.com" class="contact-link">
                                <i class="fas fa-envelope"></i> info@trucking.com
                            </a>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</section>

<!-- ===== BOOKING MODAL ===== -->
<div class="booking-modal-overlay" id="bookingModalOverlay">
    <div class="booking-modal">
        <div class="booking-modal-header">
            <div class="booking-modal-title">
                <i class="fas fa-calendar-check modal-icon"></i>
                <div>
                    <h2>កក់រថយន្ត</h2>
                    <p>លេខរថយន្ត: <span id="bookingTruckId">-</span></p>
                </div>
            </div>
            <button class="booking-modal-close" onclick="closeBookingModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="booking-modal-body">
            <form id="bookingForm" method="POST" action="{{ route('trucks.book') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="truck_code" id="formTruckId">
                <input type="hidden" name="booking_date" value="{{ date('Y-m-d') }}">
                <input type="hidden" name="status" value="pending">

                {{-- ===== SECTION 1: Customer Info ===== --}}
                <div class="booking-section">
                    <div class="booking-section-title">
                        <i class="fas fa-user"></i> ព័ត៌មានអតិថិជន
                    </div>
                    <div class="booking-grid">
                        <div class="booking-field">
                            <label>ឈ្មោះពេញ <span class="required">*</span></label>
                            <input type="text" name="full_name"
                                   value="{{ Auth::check() ? Auth::user()->user_name : '' }}"
                                   placeholder="បញ្ចូលឈ្មោះពេញ" required>
                        </div>
                        <div class="booking-field">
                            <label>លេខទូរស័ព្ទ <span class="required">*</span></label>
                            <input type="tel" name="phone"
                                   value="{{ Auth::check() ? Auth::user()->phone : '' }}"
                                   placeholder="012 345 678" required>
                        </div>
                        <div class="booking-field">
                            <label>អ៊ីមែល</label>
                            <input type="email" name="email"
                                   value="{{ Auth::check() ? Auth::user()->email : '' }}"
                                   placeholder="example@email.com">
                        </div>
                        <div class="booking-field">
                            <label>ឈ្មោះក្រុមហ៊ុន</label>
                            <input type="text" name="company_name" placeholder="ឈ្មោះក្រុមហ៊ុន (បើមាន)">
                        </div>
                        <div class="booking-field booking-field-full">
                            <label>អាសយដ្ឋាន</label>
                            <input type="text" name="address" placeholder="អាសយដ្ឋានលម្អិត">
                        </div>
                    </div>
                </div>

                {{-- ===== SECTION 2: Booking Info ===== --}}
                <div class="booking-section">
                    <div class="booking-section-title">
                        <i class="fas fa-truck"></i> ព័ត៌មានការដឹកជញ្ជូន
                    </div>
                    <div class="booking-grid">
                        <div class="booking-field">
                            <label>ប្រភេទការដឹកជញ្ជូន <span class="required">*</span></label>
                            <select name="booking_type" id="bookingTypeSelect" required onchange="toggleDateLabels(this.value)">
                                <option value="">-- ជ្រើសរើស --</option>
                                <option value="import">នាំចូល (Import)</option>
                                <option value="export">នាំចេញ (Export)</option>
                            </select>
                        </div>
                        <div class="booking-field">
                            <label>លេខកុងតឺន័រ <span style="font-size:0.75em;color:#e53e3e;">(មិនអាចដូចគ្នា — អក្សរ ៤ តួ ចុងត្រូវជា U + លេខ ៧ខ្ទង់)</span></label>
                            <input type="text" name="container_number" placeholder="ឧ. TIIU1234567"
                                   pattern="[A-Za-z]{3}[Uu][0-9]{7}"
                                   title="ទម្រង់ត្រឹមត្រូវ៖ អក្សរ ៤ តួ ដែលតួចុងជា U បន្តដោយលេខ ៧ ខ្ទង់ (ឧ. TIIU1234567)"
                                   maxlength="11"
                                   style="text-transform:uppercase;">
                            <small style="color:#718096;font-size:0.75rem;">ឧទាហរណ៍៖ TIIU1234567 (អក្សរ TII + U + លេខ ៧ខ្ទង់)</small>
                        </div>
                        <div class="booking-field" id="pickupLocationField">
                            <label>ទីតាំងទទួល <span class="required">*</span></label>
                            <input type="text" name="pickup_location" placeholder="ឧ. កំពង់ផែស្វ័យយ័តភ្នំពេញ" required>
                        </div>
                        <div class="booking-field" id="dropoffLocationField">
                            <label>ទីតាំងដឹកទៅ <span class="required">*</span></label>
                            <select name="dropoff_location" required>
                                <option value="">-- ជ្រើសរើសខេត្ត/ក្រុង --</option>
                                @foreach($provinces as $p)
                                <option value="{{ $p['km'] }}">{{ $p['km'] }} ({{ $p['en'] }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="booking-field booking-field-full">
                            <label>
                                តំណទីតាំងដឹកទៅ (Google Maps)
                                <span style="font-size:0.75em;color:#718096;font-weight:400;"> (ជម្រើស — ចម្លងតំណពី Google Maps)</span>
                            </label>
                            <input type="url" name="dropoff_location_link"
                                   placeholder="https://maps.google.com/?q=..."
                                   value="{{ old('dropoff_location_link') }}">
                        </div>
                        <div class="booking-field">
                            <label><span id="pickUpDateLabel">កាលបរិច្ឆេទថ្ងៃដឹក</span> <span class="required">*</span></label>
                            <input type="date" name="pick_up_date" id="pickUpDateInput" required
                                   min="{{ date('Y-m-d') }}"
                                   onchange="document.getElementById('dropOffDateInput').min = this.value;">
                        </div>
                        <div class="booking-field">
                            <label><span id="dropOffDateLabel">កាលបរិច្ឆេទថ្ងៃទម្លាក់</span> <span class="required">*</span></label>
                            <input type="date" name="drop_off_date" id="dropOffDateInput" required
                                   min="{{ date('Y-m-d') }}">
                        </div>
                        <div class="booking-field">
                            <label>ទម្ងន់ទំនិញ (គីឡូក្រាម) <span class="required">*</span></label>
                            <input type="number" name="cargo_weight" placeholder="ឧ. 12000" min="1" step="0.01" required>
                        </div>
                        <div class="booking-field">
                            <label>តម្លៃដឹកជញ្ជូន (ដុល្លារ)</label>
                            <input type="number" name="total_price" placeholder="ឧ. 500" min="0" step="0.01">
                        </div>
                    </div>
                </div>

                {{-- ===== SECTION 3: Cargo Document Upload ===== --}}
                <div class="booking-section">
                    <div class="booking-section-title">
                        <i class="fas fa-file-upload"></i> ឯកសារ / បញ្ជីទំនិញ
                    </div>
                    <div class="booking-grid">
                        <div class="booking-field booking-field-full">
                            <label>
                                ឯកសារបញ្ជីទំនិញ
                                <span style="font-size:0.75em;color:#718096;font-weight:400;"> (PDF, JPG, PNG — អតិបរមា 5MB)</span>
                            </label>
                            <label for="cargoFileInput" id="cargoFileLabel"
                                   style="display:flex;align-items:center;gap:12px;padding:12px 16px;
                                          border:2px dashed #CBD5E0;border-radius:10px;cursor:pointer;
                                          background:#F7FAFC;transition:border-color 0.2s;width:100%;">
                                <i class="fas fa-cloud-upload-alt" style="font-size:1.4rem;color:#a0aec0;"></i>
                                <div>
                                    <div id="cargoFileName" style="font-size:0.88rem;color:#4a5568;font-weight:600;">
                                        ចុចដើម្បីជ្រើសរើសឯកសារ
                                    </div>
                                    <div style="font-size:0.75rem;color:#a0aec0;margin-top:2px;">
                                        ឧ. Contract Release, Packing List
                                    </div>
                                </div>
                            </label>
                            <input type="file" name="cargo_list_file" id="cargoFileInput"
                                   accept=".pdf,.jpg,.jpeg,.png" style="display:none;"
                                   onchange="updateCargoFileName(this)">
                        </div>
                    </div>
                </div>

                <div class="booking-modal-footer">
                    <span class="booking-footer-note">
                        <i class="fas fa-info-circle"></i> វាលដែលមាន <span style="color:#e53e3e;">*</span> ចាំបាច់ត្រូវបំពេញ
                    </span>
                    <div class="booking-footer-actions">
                        <button type="button" class="btn-cancel" onclick="closeBookingModal()">
                            <i class="fas fa-times"></i> បោះបង់
                        </button>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-paper-plane"></i> ដាក់ស្នើការកក់
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Truck Details Modal -->
<div class="modal" id="truckModal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeModal()">&times;</span>
        <div id="modalContent"></div>
    </div>
</div>

@include('partials.footer')

<!-- JavaScript -->
<script>
    const isAuthenticated = @json(auth()->check());
    const loginUrl = "{{ route('login') }}";

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

    // Initialize counters when page loads
    document.addEventListener('DOMContentLoaded', function() {
        // Animate total trucks counter from DB count
        animateCounter(document.getElementById('totalTrucks'), {{ $trucks->count() }});
        animateCounter(document.getElementById('availableTrucks'), {{ $availableCount }});

        // Status badge colours
        const style = document.createElement('style');
        style.textContent = '.status-available{background:linear-gradient(135deg,#10b981,#059669);}';
        document.head.appendChild(style);
    });

    // Truck filtering functionality
    function filterTrucks() {
        const type = document.getElementById('truckType').value;
        const size = document.getElementById('truckSize').value;
        const status = document.getElementById('truckStatus').value;
        
        const trucks = document.querySelectorAll('.truck-card');
        let visibleCount = 0;
        
        trucks.forEach(truck => {
            const truckType = truck.getAttribute('data-type');
            const truckSize = truck.getAttribute('data-size');
            const truckStatus = truck.getAttribute('data-status');
            
            const typeMatch = type === 'all' || truckType === type;
            const sizeMatch = size === 'all' || truckSize === size;
            const statusMatch = status === 'all' || truckStatus === status;
            
            if (typeMatch && sizeMatch && statusMatch) {
                truck.style.display = 'flex';
                truck.style.animation = 'fadeIn 0.5s ease';
                visibleCount++;
            } else {
                truck.style.display = 'none';
            }
        });
        
        // Update available trucks counter
        if (status === 'all') {
            const availableTrucks = Array.from(trucks).filter(t => 
                t.getAttribute('data-status') === 'available'
            ).length;
            document.getElementById('availableTrucks').textContent = availableTrucks;
        } else if (status === 'available') {
            document.getElementById('availableTrucks').textContent = visibleCount;
        }
    }

    // Reset filters
    document.querySelector('.reset-filter').addEventListener('click', function() {
        document.getElementById('truckType').value = 'all';
        document.getElementById('truckSize').value = 'all';
        document.getElementById('truckStatus').value = 'all';
        filterTrucks();
    });

    // Add event listeners to filters
    document.getElementById('truckType').addEventListener('change', filterTrucks);
    document.getElementById('truckSize').addEventListener('change', filterTrucks);
    document.getElementById('truckStatus').addEventListener('change', filterTrucks);

    // Toggle favorite
    function toggleFavorite(button) {
        const icon = button.querySelector('i');
        button.classList.toggle('active');
        
        if (button.classList.contains('active')) {
            icon.classList.remove('far');
            icon.classList.add('fas');
            showNotification('បានបន្ថែមទៅក្នុងបញ្ជីរថយន្តដែលអ្នកចូលចិត្ត');
        } else {
            icon.classList.remove('fas');
            icon.classList.add('far');
        }
    }

    // Truck details from DB — prepared in PHP, passed as JSON
    const truckDetails = @json($trucksJson);

    // Show/hide extra trucks
    function showMoreTrucks() {
        document.querySelectorAll('.truck-extra').forEach(function(c) {
            c.style.display = 'flex';
            c.style.animation = 'fadeIn 0.5s ease';
        });
        var wrap = document.getElementById('seeMoreWrap');
        if (wrap) wrap.style.display = 'none';
        // Update counter to full count
        document.getElementById('totalTrucks').textContent =
            document.querySelectorAll('.truck-card').length;
    }

    function showTruckDetails(index) {
        const truck = truckDetails[index];
        if (!truck) return;
        const modal = document.getElementById('modalContent');

        const imgSrc = truck.truck_picture
            ? '{{ asset("") }}' + truck.truck_picture
            : '{{ asset("images/truck-bg.jpg") }}';

        // Status badge
        const statusInfo = {
            available:   { label: 'ទំនេរ',             color: '#065f46', bg: '#d1fae5' },
            delivering:  { label: 'កំពុងដឹកទំនិញ',   color: '#92400e', bg: '#fef3c7' },
            maintenance: { label: 'កំពុងជួសជុល',     color: '#991b1b', bg: '#fee2e2' },
        };
        const st    = statusInfo[truck.status] || statusInfo.available;
        const badge = `<span style="display:inline-flex;align-items:center;gap:5px;padding:4px 14px;
                         border-radius:20px;font-size:0.78rem;font-weight:700;
                         background:${st.bg};color:${st.color};">
                         <i class="fas fa-circle" style="font-size:0.4rem;"></i>${st.label}</span>`;

        // Driver rows
        let driversHtml = '';
        if (truck.drivers && truck.drivers.length > 0) {
            truck.drivers.forEach(function(d) {
                const avatar = d.driver_picture
                    ? `<img src="{{ asset("") }}${d.driver_picture}"
                            style="width:38px;height:38px;border-radius:50%;object-fit:cover;
                                   border:2px solid #e2e8f0;flex-shrink:0;">`
                    : `<div style="width:38px;height:38px;border-radius:50%;
                                   background:linear-gradient(135deg,#667eea,#764ba2);
                                   display:flex;align-items:center;justify-content:center;
                                   font-weight:800;font-size:0.85rem;color:#fff;flex-shrink:0;">
                           ${d.full_name ? d.full_name.charAt(0).toUpperCase() : '?'}
                       </div>`;
                const dStatus = d.status === 'active' ? 'កំពុងធ្វើការ' :
                                d.status === 'on_leave' ? 'ឈប់សម្រាក' : 'មិនសកម្ម';
                driversHtml += `
                    <div style="display:flex;align-items:center;gap:10px;padding:8px 0;
                                border-bottom:1px solid #f1f5f9;">
                        ${avatar}
                        <div>
                            <div style="font-weight:700;color:#1e293b;font-size:0.88rem;">
                                ${d.full_name || '—'}
                            </div>
                            <div style="font-size:0.75rem;color:#64748b;margin-top:2px;">
                                ${d.phone ? '<i class="fas fa-phone" style="margin-right:4px;font-size:0.65rem;"></i>' + d.phone : ''}
                                &nbsp;·&nbsp; ${dStatus}
                            </div>
                        </div>
                    </div>`;
            });
        } else {
            driversHtml = `<div style="color:#94a3b8;font-size:0.83rem;padding:8px 0;">
                               មិនទាន់មានអ្នកបើកបរ
                           </div>`;
        }

        modal.innerHTML = `
            <div class="modal-truck-details">
                <div class="modal-image">
                    <img src="${imgSrc}" alt="${truck.truck_name}"
                         style="object-fit:contain;background:#1e293b;">
                    <button class="image-zoom" onclick="zoomImage(this)">
                        <i class="fas fa-search-plus"></i>
                    </button>
                </div>
                <div class="modal-info">
                    <div style="display:flex;align-items:center;justify-content:space-between;
                                flex-wrap:wrap;gap:8px;margin-bottom:14px;">
                        <h3 style="margin:0;">${truck.truck_name}</h3>
                        ${badge}
                    </div>

                    <div class="modal-info-grid">
                        <div class="modal-info-row">
                            <span class="modal-label"><i class="fas fa-id-card"></i> ស្លាកលេខរថយន្ត</span>
                            <span class="modal-value">${truck.plate_number || '—'}</span>
                        </div>
                        <div class="modal-info-row">
                            <span class="modal-label"><i class="fas fa-weight-hanging"></i> ចំនួនភ្លៅរថយន្ត</span>
                            <span class="modal-value">${truck.truck_size || '—'}</span>
                        </div>
                        <div class="modal-info-row">
                            <span class="modal-label"><i class="fas fa-palette"></i> ពណ៌រថយន្ត</span>
                            <span class="modal-value">${truck.truck_color || '—'}</span>
                        </div>

                        <div class="modal-info-row">
                            <span class="modal-label"><i class="fas fa-box"></i>ទម្ងន់រថយន្ត</span>
                            <span class="modal-value">${truck.capacity_ton ? truck.capacity_ton + ' តោន' : '—'}</span>
                        </div>
                        <div class="modal-info-row">
                            <span class="modal-label"><i class="fas fa-map-marker-alt" style="color:#FF6B00;"></i> ទីតាំងបច្ចុប្បន្ន</span>
                            <span class="modal-value" style="${truck.location ? 'color:#FF6B00;font-weight:700;' : 'color:#94a3b8;'}">
                                ${truck.location || 'មិនទាន់បានកំណត់'}
                            </span>
                        </div>
                    </div>

                    <div style="margin-top:16px;">
                        <div style="font-weight:700;color:#1e293b;font-size:0.85rem;margin-bottom:8px;">
                            <i class="fas fa-user-tie" style="color:#FF6B00;margin-right:6px;"></i>
                            អ្នកបើកបររថយន្ត
                        </div>
                        ${driversHtml}
                    </div>

                    <div class="modal-actions">
                        ${truck.can_book
                            ? `<button class="book-now" onclick="bookTruck('${truck.plate_number}')">
                                   <i class="fas fa-calendar-check"></i> កក់រថយន្តនេះ
                               </button>`
                            : `<div style="display:flex;align-items:center;gap:8px;padding:10px 16px;
                                           background:#fee2e2;border-radius:10px;
                                           color:#991b1b;font-size:0.83rem;font-weight:700;">
                                   <i class="fas fa-ban"></i>
                                   ${truck.block_reason || 'មិនអាចកក់'}
                               </div>`
                        }
                        <button class="modal-close-btn" onclick="closeModal()">
                            <i class="fas fa-times"></i> បិទ
                        </button>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('truckModal').style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    // Close modal
    function closeModal() {
        document.getElementById('truckModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('truckModal');
        if (event.target == modal) {
            closeModal();
        }
    }

    // Open booking modal
    function openBookingModal(truckId) {
        if (!isAuthenticated) {
            window.location.href = loginUrl;
            return;
        }
        document.getElementById('bookingTruckId').textContent = truckId;
        document.getElementById('formTruckId').value = truckId;
        document.getElementById('bookingModalOverlay').classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeBookingModal() {
        document.getElementById('bookingModalOverlay').classList.remove('open');
        document.body.style.overflow = 'auto';
    }

    function updateCargoFileName(input) {
        var label = document.getElementById('cargoFileName');
        if (input.files && input.files[0]) {
            label.textContent = input.files[0].name;
            label.style.color = '#38a169';
            document.getElementById('cargoFileLabel').style.borderColor = '#38a169';
        } else {
            label.textContent = 'ចុចដើម្បីជ្រើសរើសឯកសារ';
            label.style.color = '#4a5568';
            document.getElementById('cargoFileLabel').style.borderColor = '#CBD5E0';
        }
    }

    // Close booking modal on overlay click
    document.getElementById('bookingModalOverlay').addEventListener('click', function(e) {
        if (e.target === this) closeBookingModal();
    });

    // Switch date field labels based on booking type (import / export)
    function toggleDateLabels(type) {
        var pickUpLabel  = document.getElementById('pickUpDateLabel');
        var dropOffLabel = document.getElementById('dropOffDateLabel');
        var pickupField  = document.getElementById('pickupLocationField');
        var dropoffField = document.getElementById('dropoffLocationField');
        if (type === 'export') {
            pickUpLabel.textContent  = 'ថ្ងៃលើកទូរ';
            dropOffLabel.textContent = 'ថ្ងៃឡើងទំនិញ';
            pickupField.style.order  = '2';
            dropoffField.style.order = '1';
        } else {
            pickUpLabel.textContent  = 'កាលបរិច្ឆេទថ្ងៃដឹក';
            dropOffLabel.textContent = 'កាលបរិច្ឆេទថ្ងៃទម្លាក់';
            pickupField.style.order  = '';
            dropoffField.style.order = '';
        }
    }

    // Quick book function (calendar icon on card)
    function quickBook(truckId) {
        closeModal(); // close details modal if open
        openBookingModal(truckId);
    }

    function bookTruck(truckId) {
        closeModal();
        openBookingModal(truckId);
    }

    function zoomImage(button) {
        const image = button.parentElement.querySelector('img');
        image.classList.toggle('zoomed');
        
        if (image.classList.contains('zoomed')) {
            image.style.transform = 'scale(1.5)';
            button.innerHTML = '<i class="fas fa-search-minus"></i>';
        } else {
            image.style.transform = 'scale(1)';
            button.innerHTML = '<i class="fas fa-search-plus"></i>';
        }
    }

    // Notification function
    function showNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'notification';
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            z-index: 10000;
            animation: slideIn 0.3s ease;
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Add animation keyframes
    const animationStyle = document.createElement('style');
    animationStyle.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .truck-card {
            animation: fadeIn 0.5s ease;
            animation-fill-mode: both;
        }
        
        .truck-card:nth-child(1) { animation-delay: 0.1s; }
        .truck-card:nth-child(2) { animation-delay: 0.2s; }
        .truck-card:nth-child(3) { animation-delay: 0.3s; }
        .truck-card:nth-child(4) { animation-delay: 0.4s; }
        .truck-card:nth-child(5) { animation-delay: 0.5s; }
        .truck-card:nth-child(6) { animation-delay: 0.6s; }
        .truck-card:nth-child(7) { animation-delay: 0.7s; }
        .truck-card:nth-child(8) { animation-delay: 0.8s; }
        .truck-card:nth-child(9) { animation-delay: 0.9s; }
        .truck-card:nth-child(10) { animation-delay: 1s; }
        .truck-card:nth-child(11) { animation-delay: 1.1s; }
    `;
    document.head.appendChild(animationStyle);
</script>


</body>
</html>