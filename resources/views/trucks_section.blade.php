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
                <span class="stat-number" id="totalTrucks">0</span>
                <span class="stat-label">រថយន្តសរុប</span>
            </div>
            <div class="stat-item">
                <span class="stat-number" id="availableTrucks">0</span>
                <span class="stat-label">រថយន្តដែលអាចប្រើប្រាស់បាន</span>
            </div>
            <div class="stat-item">
                <span class="stat-number" id="statesCovered">25</span>
                <span class="stat-label">ខេត្តនានា</span>
            </div>
            <div class="stat-item">
                <span class="stat-number" id="successfulDeliveries">98%</span>
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
                    <label for="truckSize"><i class="fas fa-weight-hanging"></i> ទំហំ</label>
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

        <!-- Trucks Grid -->
        <div class="trucks-grid">
            @php
                $truckData = [
                    ['id' => '4618.png', 'name' => 'Mitsubishi FUSO', 'size' => '13660Kg', 'color' => 'ពណ៌បៃតង', 'status' => 'available'],
                    ['id' => '5464.png', 'name' => 'Mitsubishi FUSO', 'size' => '12490Kg', 'color' => 'ពណ៌បៃតង', 'status' => 'available'],
                    ['id' => '5356.png', 'name' => 'Mitsubishi FUSO', 'size' => '12970Kg', 'color' => 'ពណ៌បៃតង', 'status' => 'busy'],
                    ['id' => '6426.png', 'name' => 'Mitsubishi FUSO', 'size' => '12880Kg', 'color' => 'ពណ៌បៃតង', 'status' => 'available'],
                    ['id' => '8792.png', 'name' => 'Mitsubishi FUSO', 'size' => '14840Kg', 'color' => 'ពណ៌បៃតង', 'status' => 'available'],
                    ['id' => '9115.png', 'name' => 'Mitsubishi FUSO', 'size' => '12310Kg', 'color' => 'ពណ៌បៃតង', 'status' => 'maintenance'],
                    ['id' => '8338.png', 'name' => 'Mitsubishi FUSO', 'size' => '13660Kg', 'color' => 'ពណ៌បៃតង', 'status' => 'available'],
                    ['id' => '6728.png', 'name' => 'Mitsubishi FUSO', 'size' => '15200Kg', 'color' => 'ពណ៌ស', 'status' => 'available'],
                    ['id' => '6299.png', 'name' => 'Mitsubishi FUSO', 'size' => '13080Kg', 'color' => 'ពណ៌ស', 'status' => 'available'],
                    ['id' => '8380.png', 'name' => 'Mitsubishi FUSO', 'size' => '13730Kg', 'color' => 'ពណ៌ប្រផេះ', 'status' => 'busy'],
                    ['id' => '3891.png', 'name' => 'Mitsubishi FUSO', 'size' => '13080Kg', 'color' => 'ពណ៌ប្រផេះ', 'status' => 'available'],
                ];
            @endphp

            @foreach($truckData as $index => $truck)
            <div class="truck-card" 
                 data-type="{{ str_contains(strtolower($truck['name']), 'fuso') ? 'fuso' : 
                              (str_contains(strtolower($truck['name']), 'isuzu') ? 'isuzu' :
                              (str_contains(strtolower($truck['name']), 'hino') ? 'hino' : 'volvo')) }}"
                 data-size="{{ $truck['size'] > 14000 ? 'large' : ($truck['size'] > 13000 ? 'medium' : 'small') }}"
                 data-status="{{ $truck['status'] }}">
                <div class="truck-image">
                    <img src="{{ asset('images/' . $truck['id']) }}" alt="{{ $truck['name'] }}" loading="lazy">
                    <span class="truck-status status-{{ $truck['status'] }}">
                        @if($truck['status'] == 'available')
                            អាចប្រើប្រាស់បាន
                        @elseif($truck['status'] == 'busy')
                            កំពុងដឹកជញ្ជូន
                        @else
                            កំពុងថែទាំ
                        @endif
                    </span>
                    <button class="truck-favorite" onclick="toggleFavorite(this)">
                        <i class="far fa-heart"></i>
                    </button>
                </div>
                
                <div class="truck-content">
                    <div class="truck-info">
                        <div class="info-row">
                            <span class="info-label">លេខកូដ</span>
                            <span class="info-value">TRK-{{ str_pad($index + 1, 3, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">ឈ្មោះរថយន្ត</span>
                            <span class="info-value">{{ $truck['name'] }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">ទំហំ</span>
                            <span class="info-value">{{ $truck['size'] }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">ពណ៌</span>
                            <span class="info-value">{{ $truck['color'] }}</span>
                        </div>
                    </div>
                    
                    <div class="truck-actions">
                        <button class="view-details" onclick="showTruckDetails({{ $index }})">
                            <i class="fas fa-eye"></i> មើលលម្អិត
                        </button>
                        <button class="quick-book" title="កក់រថយន្ត" onclick="quickBook('TRK-{{ str_pad($index + 1, 3, '0', STR_PAD_LEFT) }}')">
                            <i class="fas fa-calendar-check"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

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
            <form id="bookingForm" method="POST" action="{{ route('trucks.book') }}">
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
                            <select name="booking_type" required>
                                <option value="">-- ជ្រើសរើស --</option>
                                <option value="import">នាំចូល (Import)</option>
                                <option value="export">នាំចេញ (Export)</option>
                            </select>
                        </div>
                        <div class="booking-field">
                            <label>លេខកុងតឺន័រ</label>
                            <input type="text" name="container_number" placeholder="ឧ. MSCU1234567">
                        </div>
                        <div class="booking-field">
                            <label>ទីតាំងទទួល <span class="required">*</span></label>
                            <input type="text" name="pickup_location" placeholder="ឧ. កំពង់ផែស្វ័យយ័តភ្នំពេញ" required>
                        </div>
                        <div class="booking-field">
                            <label>ទីតាំងដឹកទៅ <span class="required">*</span></label>
                            <input type="text" name="dropoff_location" placeholder="ឧ. រោងចក្រ ខេត្តកណ្តាល" required>
                        </div>
                        <div class="booking-field">
                            <label>កាលបរិច្ឆេទទទួល <span class="required">*</span></label>
                            <input type="date" name="pick_up_date" required
                                   min="{{ date('Y-m-d') }}">
                        </div>
                        <div class="booking-field">
                            <label>កាលបរិច្ឆេទដឹកទៅ <span class="required">*</span></label>
                            <input type="date" name="drop_off_date" required
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
        // Count total trucks
        const truckCards = document.querySelectorAll('.truck-card');
        document.getElementById('totalTrucks').textContent = truckCards.length;
        
        // Count available trucks
        const availableTrucks = Array.from(truckCards).filter(card => 
            card.getAttribute('data-status') === 'available'
        ).length;
        
        animateCounter(document.getElementById('availableTrucks'), availableTrucks);
        
        // Add CSS for status colors
        const style = document.createElement('style');
        style.textContent = `
            .status-available { background: linear-gradient(135deg, #10b981, #059669); }
            .status-busy { background: linear-gradient(135deg, #f59e0b, #d97706); }
            .status-maintenance { background: linear-gradient(135deg, #ef4444, #dc2626); }
        `;
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

    // Show truck details modal
    const truckDetails = @json($truckData);

    function showTruckDetails(index) {
        const truck = truckDetails[index];
        const modal = document.getElementById('modalContent');
        
        modal.innerHTML = `
            <div class="modal-truck-details">
                <div class="modal-image">
                    <img src="{{ asset('images/') }}/${truck.id}" alt="${truck.name}">
                    <button class="image-zoom" onclick="zoomImage(this)">
                        <i class="fas fa-search-plus"></i>
                    </button>
                </div>
                <div class="modal-info">
                    <h3>${truck.name}</h3>
                    <div class="modal-info-grid">
                        <div class="modal-info-row">
                            <span class="modal-label">
                                <i class="fas fa-id-card"></i> លេខកូដ
                            </span>
                            <span class="modal-value">TRK-${String(index + 1).padStart(3, '0')}</span>
                        </div>
                        <div class="modal-info-row">
                            <span class="modal-label">
                                <i class="fas fa-weight-hanging"></i> ទំហំ
                            </span>
                            <span class="modal-value">${truck.size}</span>
                        </div>
                        <div class="modal-info-row">
                            <span class="modal-label">
                                <i class="fas fa-palette"></i> ពណ៌
                            </span>
                            <span class="modal-value">${truck.color}</span>
                        </div>
                        <div class="modal-info-row">
                            <span class="modal-label">
                                <i class="fas fa-gas-pump"></i> ប្រភេទសាំង
                            </span>
                            <span class="modal-value">ឌីសែល</span>
                        </div>
                        <div class="modal-info-row">
                            <span class="modal-label">
                                <i class="fas fa-tachometer-alt"></i> កម្លាំងម៉ាស៊ីន
                            </span>
                            <span class="modal-value">៣០០ សេក</span>
                        </div>
                        <div class="modal-info-row">
                            <span class="modal-label">
                                <i class="fas fa-box"></i> សមត្ថភាព
                            </span>
                            <span class="modal-value">${parseInt(truck.size) / 1000} តោន</span>
                        </div>
                    </div>
                    <div class="modal-actions">
                        <button class="book-now" onclick="bookTruck('TRK-${String(index + 1).padStart(3, '0')}')">
                            <i class="fas fa-calendar-check"></i> កក់រថយន្តនេះ
                        </button>
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
        document.getElementById('bookingTruckId').textContent = truckId;
        document.getElementById('formTruckId').value = truckId;
        document.getElementById('bookingModalOverlay').classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeBookingModal() {
        document.getElementById('bookingModalOverlay').classList.remove('open');
        document.body.style.overflow = 'auto';
    }

    // Close booking modal on overlay click
    document.getElementById('bookingModalOverlay').addEventListener('click', function(e) {
        if (e.target === this) closeBookingModal();
    });

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