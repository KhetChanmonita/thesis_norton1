{{-- resources/views/trucks_section.blade.php --}}
<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('images/trucking-logo.png') }}">
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
<div id="bookingSuccessAlert" class="ts-success-alert">
    <i class="fas fa-check-circle ts-success-icon"></i>
    {{ session('booking_success') }}
</div>
<script>setTimeout(()=>{ const a=document.getElementById('bookingSuccessAlert'); if(a) a.remove(); }, 5000);</script>
@endif

@if($errors->any())
<div id="bookingErrorAlert" class="ts-error-alert"
     style="background:{{ $errors->has('container_number') ? 'linear-gradient(135deg,#f97316,#ea580c)' : 'linear-gradient(135deg,#ef4444,#dc2626)' }};
            box-shadow:0 6px 20px {{ $errors->has('container_number') ? 'rgba(249,115,22,0.45)' : 'rgba(239,68,68,0.4)' }};">
    <i class="fas {{ $errors->has('container_number') ? 'fa-barcode' : 'fa-exclamation-circle' }} ts-error-icon"></i>
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
                        @foreach($truckTypes as $type)
                        <option value="{{ \Illuminate\Support\Str::slug($type) }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label for="truckSize"><i class="fas fa-weight-hanging"></i> ទម្ងន់</label>
                    <select id="truckSize" class="filter-select">
                        <option value="all">ទាំងអស់</option>
                        <option value="small">(១២-១៣តោន)</option>
                        <option value="medium">(១៤-១៥តោន)</option>
                        <option value="large">(១៦តោន↑)</option>
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
                $typeKey    = \Illuminate\Support\Str::slug($truck->truck_name ?? 'other');
                $cap        = (float)($truck->capacity_ton ?? 0);
                $sizeKey    = $cap >= 16 ? 'large' : ($cap >= 14 ? 'medium' : ($cap >= 12 ? 'small' : 'other'));
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
                    <div class="ts-blocked-notice">
                        <i class="fas fa-ban ts-blocked-icon"></i>
                        <span class="ts-blocked-text">
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
                        <button class="quick-book ts-quick-book-disabled"
                                title="{{ $blockReason }}" disabled>
                            <i class="fas fa-ban"></i>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="ts-empty-state">
                <i class="fas fa-truck ts-empty-icon"></i>
                <p>មិនទាន់មានរថយន្ត</p>
            </div>
            @endforelse
        </div>

        {{-- See More button — only shown when there are more than 6 trucks --}}
        @if($trucks->count() > 6)
        <div class="ts-see-more-wrap" id="seeMoreWrap">
            <button onclick="showMoreTrucks()" class="see-more-btn ts-see-more-btn">
                <i class="fas fa-plus-circle"></i>
                មើលបន្ថែម &nbsp;
                <span class="ts-see-more-badge">
                    {{ $trucks->count() - 6 }} <span>ទៀត</span>
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
                        
                        {{-- Or Option 2: Contact buttons that always work --}}
                        <div class="contact-info ts-contact-info-alt">
                            <a href="tel:0962679042" class="contact-link">
                                <i class="fas fa-phone"></i> 096 267 9042
                            </a>
                            <a href="https://t.me/+855962679042" target="_blank" class="contact-link">
                                <i class="fab fa-telegram"></i> Telegram
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
                            <label>ឈ្មោះពេញ</label>
                            <input type="text" name="full_name"
                                   value="{{ Auth::check() ? Auth::user()->user_name : '' }}"
                                   placeholder="បញ្ចូលឈ្មោះពេញ" required>
                        </div>
                        <div class="booking-field">
                            <label>លេខទូរស័ព្ទ</label>
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
                            <label>ប្រភេទការដឹកជញ្ជូន</label>
                            <select name="booking_type" id="bookingTypeSelect" required onchange="toggleDateLabels(this.value)">
                                <option value="">-- ជ្រើសរើស --</option>
                                <option value="import">នាំចូល (Import)</option>
                                <option value="export">នាំចេញ (Export)</option>
                            </select>
                        </div>
                        <div class="booking-field">
                            <label>លេខកុងតឺន័រ <span class="ts-hint-red">(មិនអាចដូចគ្នា — អក្សរ ៤ តួ ចុងត្រូវជា U + លេខ ៧ខ្ទង់)</span></label>
                            <input type="text" name="container_number" placeholder="ឧ. TIIU1234567"
                                   pattern="[A-Za-z]{3}[Uu][0-9]{7}"
                                   title="ទម្រង់ត្រឹមត្រូវ៖ អក្សរ ៤ តួ ដែលតួចុងជា U បន្តដោយលេខ ៧ ខ្ទង់ (ឧ. TIIU1234567)"
                                   maxlength="11"
                                   class="ts-uppercase">
                            <small class="ts-hint-gray">ឧទាហរណ៍៖ TIIU1234567 (អក្សរ TII + U + លេខ ៧ខ្ទង់)</small>
                        </div>
                        <div class="booking-field">
                            <label>ទំហំកុងតឺន័រ</label>
                            <select name="container_size">
                                <option value="">-- ជ្រើសរើសទំហំ --</option>
                                <option value="20F">20F</option>
                                <option value="40F">40F</option>
                                <option value="45F">45F</option>
                            </select>
                        </div>
                        <div class="booking-field" id="pickupLocationField">
                            <label><span id="pickupLocationLabel">ទីតាំងទទួល</span></label>
                            <input type="text" name="pickup_location" id="pickupLocationInput"
                                   placeholder="ឧ. កំពង់ផែស្វ័យយ័តភ្នំពេញ" required>
                            <select name="pickup_location" id="pickupLocationSelect" style="display:none;" disabled>
                                <option value="">-- ជ្រើសរើសកំពង់ផែ --</option>
                                <option value="កំពង់ផែស្វ័យយ័តព្រះសីហនុ">កំពង់ផែស្វ័យយ័តព្រះសីហនុ (SHV Port)</option>
                                <option value="កំពង់ផែស្វ័យយ័តភ្នំពេញ">កំពង់ផែស្វ័យយ័តភ្នំពេញ (Phnom Penh Port)</option>
                            </select>
                        </div>
                        <div class="booking-field" id="dropoffLocationField">
                            <label><span id="dropoffLocationLabel">ទីតាំងដឹកទៅ</span></label>
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
                                <span class="ts-hint-gray-inline"> (ជម្រើស — ចម្លងតំណពី Google Maps)</span>
                            </label>
                            <input type="url" name="dropoff_location_link"
                                   placeholder="https://maps.google.com/?q=..."
                                   value="{{ old('dropoff_location_link') }}">
                        </div>
                        <div class="booking-field">
                            <label><span id="pickUpDateLabel">កាលបរិច្ឆេទថ្ងៃដឹក</span></label>
                            <input type="date" name="pick_up_date" id="pickUpDateInput" required
                                   min="{{ date('Y-m-d') }}"
                                   onchange="document.getElementById('dropOffDateInput').min = this.value;">
                        </div>
                        <div class="booking-field">
                            <label><span id="dropOffDateLabel">កាលបរិច្ឆេទថ្ងៃទម្លាក់</span></label>
                            <input type="date" name="drop_off_date" id="dropOffDateInput" required
                                   min="{{ date('Y-m-d') }}">
                        </div>
                        <div class="booking-field">
                            <label>ទម្ងន់ទំនិញ (គីឡូក្រាម)</label>
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
                                <span class="ts-hint-gray-inline"> (PDF, JPG, PNG — អតិបរមា 5MB)</span>
                            </label>
                            <label for="cargoFileInput" id="cargoFileLabel" class="ts-cargo-label">
                                <i class="fas fa-cloud-upload-alt ts-cargo-icon"></i>
                                <div>
                                    <div id="cargoFileName" class="ts-cargo-filename">
                                        ចុចដើម្បីជ្រើសរើសឯកសារ
                                    </div>
                                    <div class="ts-cargo-hint">
                                        ឧ. Contract Release, Packing List
                                    </div>
                                </div>
                            </label>
                            <input type="file" name="cargo_list_file" id="cargoFileInput"
                                   accept=".pdf,.jpg,.jpeg,.png" class="ts-hidden"
                                   onchange="updateCargoFileName(this)">
                        </div>
                    </div>
                </div>

                <div class="booking-modal-footer">
                    <span class="booking-footer-note">
                        <i class="fas fa-info-circle"></i> វាលដែលមាន ចាំបាច់ត្រូវបំពេញ
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

        const trucks = document.querySelectorAll('.truck-card');

        trucks.forEach(truck => {
            const truckType = truck.getAttribute('data-type');
            const truckSize = truck.getAttribute('data-size');

            const typeMatch = type === 'all' || truckType === type;
            const sizeMatch = size === 'all' || truckSize === size;

            if (typeMatch && sizeMatch) {
                truck.style.display = 'flex';
                truck.style.animation = 'fadeIn 0.5s ease';
            } else {
                truck.style.display = 'none';
            }
        });
    }

    // Reset filters
    document.querySelector('.reset-filter').addEventListener('click', function() {
        document.getElementById('truckType').value = 'all';
        document.getElementById('truckSize').value = 'all';
        filterTrucks();
    });

    // Add event listeners to filters
    document.getElementById('truckType').addEventListener('change', filterTrucks);
    document.getElementById('truckSize').addEventListener('change', filterTrucks);

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
        const badge = `<span class="ts-status-badge" style="background:${st.bg};color:${st.color};">
                         <i class="fas fa-circle ts-badge-dot"></i>${st.label}</span>`;

        // Driver rows
        let driversHtml = '';
        if (truck.drivers && truck.drivers.length > 0) {
            truck.drivers.forEach(function(d) {
                const avatar = d.driver_picture
                    ? `<img src="{{ asset("") }}${d.driver_picture}" class="ts-driver-avatar-img">`
                    : `<div class="ts-driver-avatar-placeholder">
                           ${d.full_name ? d.full_name.charAt(0).toUpperCase() : '?'}
                       </div>`;
                const dStatus = d.status === 'active' ? 'កំពុងធ្វើការ' :
                                d.status === 'on_leave' ? 'ឈប់សម្រាក' : 'មិនសកម្ម';
                driversHtml += `
                    <div class="ts-driver-row">
                        ${avatar}
                        <div>
                            <div class="ts-driver-name">
                                ${d.full_name || '—'}
                            </div>
                            <div class="ts-driver-phone">
                                ${d.phone ? '<i class="fas fa-phone ts-driver-phone-icon"></i>' + d.phone : ''}
                                &nbsp;·&nbsp; ${dStatus}
                            </div>
                        </div>
                    </div>`;
            });
        } else {
            driversHtml = `<div class="ts-no-driver">
                               មិនទាន់មានអ្នកបើកបរ
                           </div>`;
        }

        modal.innerHTML = `
            <div class="modal-truck-details">
                <div class="modal-image">
                    <img src="${imgSrc}" alt="${truck.truck_name}" class="ts-modal-img">
                    <button class="image-zoom" onclick="zoomImage(this)">
                        <i class="fas fa-search-plus"></i>
                    </button>
                </div>
                <div class="modal-info">
                    <div class="ts-modal-title-row">
                        <h3 class="ts-modal-h3">${truck.truck_name}</h3>
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
                            <span class="modal-label"><i class="fas fa-map-marker-alt ts-icon-orange"></i> ទីតាំងបច្ចុប្បន្ន</span>
                            <span class="modal-value ${truck.location ? 'ts-location-set' : 'ts-location-unset'}">
                                ${truck.location || 'មិនទាន់បានកំណត់'}
                            </span>
                        </div>
                    </div>

                    <div class="ts-drivers-section">
                        <div class="ts-drivers-title">
                            <i class="fas fa-user-tie ts-drivers-title-icon"></i>
                            អ្នកបើកបររថយន្ត
                        </div>
                        ${driversHtml}
                    </div>

                    <div class="modal-actions">
                        ${truck.can_book
                            ? `<button class="book-now" onclick="bookTruck('${truck.plate_number}')">
                                   <i class="fas fa-calendar-check"></i> កក់រថយន្តនេះ
                               </button>`
                            : `<div class="ts-block-reason">
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

        var pickupLocLabel   = document.getElementById('pickupLocationLabel');
        var dropoffLocLabel  = document.getElementById('dropoffLocationLabel');
        var pickupInput      = document.getElementById('pickupLocationInput');
        var pickupSelect     = document.getElementById('pickupLocationSelect');

        if (type === 'import') {
            pickupLocLabel.textContent  = 'ទីតាំងលើកទំនិញ';
            dropoffLocLabel.textContent = 'ទីតាំងទម្លាក់ទំនិញ';
        } else if (type === 'export') {
            pickupLocLabel.textContent  = 'ទីតាំងទម្លាក់ទំនិញ';
            dropoffLocLabel.textContent = 'ទីតាំងច្រកទំនិញ';
        } else {
            pickupLocLabel.textContent  = 'ទីតាំងទទួល';
            dropoffLocLabel.textContent = 'ទីតាំងដឹកទៅ';
        }

        if (type === 'import' || type === 'export') {
            pickupInput.style.display  = 'none';
            pickupInput.disabled       = true;
            pickupInput.required       = false;
            pickupSelect.style.display = 'block';
            pickupSelect.disabled      = false;
            pickupSelect.required      = true;
        } else {
            pickupInput.style.display  = 'block';
            pickupInput.disabled       = false;
            pickupInput.required       = true;
            pickupSelect.style.display = 'none';
            pickupSelect.disabled      = true;
            pickupSelect.required      = false;
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

    // Add animation keyframes
    const animationStyle = document.createElement('style');
    animationStyle.textContent = `
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