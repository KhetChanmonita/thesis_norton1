{{-- resources/views/bookings/create-import.blade.php --}}
<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>កក់សេវានាំចូល | Trucking System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/booking.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

@include('partials.header')

<div class="booking-wrapper">
    <div class="container">
        <div class="booking-header">
            <h1><i class="fas fa-import"></i> កក់សេវានាំចូល</h1>
            <p>បំពេញព័ត៌មានខាងក្រោមដើម្បីកក់សេវាដឹកជញ្ជូន</p>
        </div>

        <form id="import-booking-form" action="{{ route('bookings.store.import') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="booking-form">
                <!-- Step 1: Location Information -->
                <div class="form-section">
                    <div class="section-title">
                        <span class="step-number">1</span>
                        <h2>ព័ត៌មានទីតាំង</h2>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="origin_port_id"><i class="fas fa-ship"></i> កំពង់ផែចាប់ផ្តើម <span class="required">*</span></label>
                            <select id="origin_port_id" name="origin_port_id" required>
                                <option value="">-- ជ្រើសរើសកំពង់ផែ --</option>
                                @foreach($ports as $port)
                                    <option value="{{ $port->id }}">{{ $port->name_km }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="destination_province_id"><i class="fas fa-map-pin"></i> ខេត្តគោលដៅ <span class="required">*</span></label>
                            <select id="destination_province_id" name="destination_province_id" required>
                                <option value="">-- ជ្រើសរើសខេត្ត --</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}">{{ $province->name_km }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Cargo Information -->
                <div class="form-section">
                    <div class="section-title">
                        <span class="step-number">2</span>
                        <h2>ព័ត៌មានទំនិញ</h2>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="container_size"><i class="fas fa-box"></i> ទំហំកុងតឺន័រ <span class="required">*</span></label>
                            <select id="container_size" name="container_size" required>
                                <option value="">-- ជ្រើសរើសទំហំ --</option>
                                <option value="45HC">45HC</option>
                                <option value="40HC">40HC</option>
                                <option value="40DC">40DC</option>
                                <option value="20GP">20GP</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="weight"><i class="fas fa-weight-hanging"></i> ទម្ងន់ទំនិញ (គីឡូក្រាម) <span class="required">*</span></label>
                            <input type="number" id="weight" name="weight" min="1" placeholder="ឧ. 25000" required>
                            <small class="form-text">ប្រសិនបើលើសពី ២៣តោន ថ្លៃបន្ថែម ៣០$/តោន</small>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="pickup_date"><i class="fas fa-calendar"></i> កាលបរិច្ឆេទយកទំនិញ <span class="required">*</span></label>
                            <input type="date" id="pickup_date" name="pickup_date" min="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="delivery_date"><i class="fas fa-calendar-check"></i> កាលបរិច្ឆេទបញ្ជូនទំនិញ <span class="required">*</span></label>
                            <input type="date" id="delivery_date" name="delivery_date" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Container Release & Empty Return -->
                <div class="form-section">
                    <div class="section-title">
                        <span class="step-number">3</span>
                        <h2>ឯកសារ និងការប្រគល់កុងតឺន័រវិញ</h2>
                    </div>

                    <div class="form-group">
                        <label for="container_release_file"><i class="fas fa-file-upload"></i> បញ្ចូលឯកសារ Container Release <span class="required">*</span></label>
                        <div class="file-upload-area">
                            <input type="file" id="container_release_file" name="container_release_file" accept=".pdf,.jpg,.jpeg,.png" required>
                            <div class="file-upload-placeholder">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p>ចុចឬអូសឯកសារមកទីនេះ</p>
                                <small>ឯកសារ PDF, JPG, PNG (ទំហំអតិបរមា 5MB)</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="empty_return_depot"><i class="fas fa-warehouse"></i> ទីតាំងប្រគល់កុងតឺន័រវិញ <span class="required">*</span></label>
                        <select id="empty_return_depot" name="empty_return_depot" required>
                            <option value="">-- ជ្រើសរើសទីតាំង --</option>
                            @foreach($depots as $depot)
                                <option value="{{ $depot }}">{{ $depot }}</option>
                            @endforeach
                        </select>
                        <small class="form-text">ថ្លៃប្រគល់កុងតឺន័រវិញ: ១៤$ សម្រាប់ 45HC/40HC/40DC, ៧$ សម្រាប់ 20GP</small>
                    </div>
                </div>

                <!-- Step 4: Truck Selection -->
                <div class="form-section">
                    <div class="section-title">
                        <span class="step-number">4</span>
                        <h2>ជ្រើសរើសរថយន្ត</h2>
                    </div>

                    <div class="truck-list">
                        <div class="truck-filter">
                            <button type="button" class="filter-btn active" data-filter="all">ទាំងអស់</button>
                            <button type="button" class="filter-btn" data-filter="available">ទំនេរ</button>
                        </div>

                        <div class="truck-grid" id="truck-grid">
                            @foreach($trucks as $truck)
                                <div class="truck-card {{ $truck->status == 'available' ? 'available' : 'unavailable' }}" data-status="{{ $truck->status }}">
                                    <div class="truck-radio">
                                        <input type="radio" name="truck_id" id="truck_{{ $truck->id }}" value="{{ $truck->id }}" {{ $truck->status != 'available' ? 'disabled' : '' }}>
                                        <label for="truck_{{ $truck->id }}">
                                            <div class="truck-header">
                                                <span class="truck-plate">{{ $truck->plate_number }}</span>
                                                <span class="truck-status status-{{ $truck->status }}">
                                                    {{ $truck->status == 'available' ? 'ទំនេរ' : 'កំពុងដឹកជញ្ជូន' }}
                                                </span>
                                            </div>
                                            <div class="truck-location">
                                                <i class="fas fa-map-marker-alt"></i> {{ $truck->current_location }}
                                            </div>
                                            @if($truck->current_location_km)
                                                <div class="truck-location-km">
                                                    <i class="fas fa-location-dot"></i> {{ $truck->current_location_km }}
                                                </div>
                                            @endif
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Hidden fields for calculated prices -->
                <input type="hidden" name="transport_fee" id="transport_fee" value="0">
                <input type="hidden" name="overweight_charge" id="overweight_charge" value="0">
                <input type="hidden" name="empty_return_fee" id="empty_return_fee" value="0">
                <input type="hidden" name="total_amount" id="total_amount" value="0">
                <input type="hidden" name="deposit_amount" id="deposit_amount" value="0">

                <!-- Price Summary -->
                <div class="price-summary" id="price-summary" style="display: none;">
                    <h3><i class="fas fa-calculator"></i> សង្ខេបតម្លៃ</h3>

                    <div class="summary-row">
                        <span>ថ្លៃដឹកជញ្ជូនមូលដ្ឋាន:</span>
                        <span id="summary-transport">$0.00</span>
                    </div>
                    <div class="summary-row">
                        <span>ថ្លៃបន្ថែមទម្ងន់លើស:</span>
                        <span id="summary-overweight">$0.00</span>
                    </div>
                    <div class="summary-row">
                        <span>ថ្លៃប្រគល់កុងតឺន័រវិញ:</span>
                        <span id="summary-empty-return">$0.00</span>
                    </div>
                    <div class="summary-row total">
                        <span>តម្លៃសរុប:</span>
                        <span id="summary-total">$0.00</span>
                    </div>
                    <div class="summary-row deposit">
                        <span>ត្រូវបង់ប្រាក់ជាមុន ៥០%:</span>
                        <span id="summary-deposit">$0.00</span>
                    </div>
                </div>

                <!-- Confirmation Panel -->
                <div class="confirmation-panel" id="confirmation-panel" style="display: none;">
                    <div class="confirmation-header">
                        <i class="fas fa-check-circle"></i>
                        <h3>សូមពិនិត្យមើលព័ត៌មានរបស់អ្នក</h3>
                    </div>

                    <div class="confirmation-content" id="confirmation-content"></div>

                    <div class="confirmation-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check"></i> កក់សេវា
                        </button>
                        <button type="button" class="btn btn-secondary" id="cancel-booking">
                            <i class="fas fa-times"></i> បោះបង់
                        </button>
                    </div>
                </div>

                <!-- Submit Button (Initial) -->
                <div class="form-actions">
                    <button type="button" class="btn btn-primary" id="review-booking">
                        <i class="fas fa-search"></i> ពិនិត្យព័ត៌មាន
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@include('partials.footer')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elements
        const form = document.getElementById('import-booking-form');
        const originPort = document.getElementById('origin_port_id');
        const destinationProvince = document.getElementById('destination_province_id');
        const containerSize = document.getElementById('container_size');
        const weight = document.getElementById('weight');
        const emptyReturnDepot = document.getElementById('empty_return_depot');
        const priceSummary = document.getElementById('price-summary');

        // Price calculation
        function calculatePrice() {
            if (!originPort.value || !destinationProvince.value || !containerSize.value || !weight.value) {
                return;
            }

            const data = {
                origin_port_id: originPort.value,
                destination_province_id: destinationProvince.value,
                container_size: containerSize.value,
                weight: weight.value,
                empty_return_depot: emptyReturnDepot.value,
                type: 'import',
                _token: document.querySelector('meta[name="csrf-token"]').content
            };

            fetch('{{ route("booking.calculate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update hidden fields
                    document.getElementById('transport_fee').value = data.transport_fee;
                    document.getElementById('overweight_charge').value = data.overweight_charge;
                    document.getElementById('empty_return_fee').value = data.empty_return_fee;
                    document.getElementById('total_amount').value = data.total_amount;
                    document.getElementById('deposit_amount').value = data.deposit_amount;

                    // Update summary
                    document.getElementById('summary-transport').textContent = '$' + data.transport_fee.toFixed(2);
                    document.getElementById('summary-overweight').textContent = '$' + data.overweight_charge.toFixed(2);
                    document.getElementById('summary-empty-return').textContent = '$' + data.empty_return_fee.toFixed(2);
                    document.getElementById('summary-total').textContent = '$' + data.total_amount.toFixed(2);
                    document.getElementById('summary-deposit').textContent = '$' + data.deposit_amount.toFixed(2);

                    priceSummary.style.display = 'block';
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Event listeners for price calculation
        originPort.addEventListener('change', calculatePrice);
        destinationProvince.addEventListener('change', calculatePrice);
        containerSize.addEventListener('change', calculatePrice);
        weight.addEventListener('input', calculatePrice);
        emptyReturnDepot.addEventListener('change', calculatePrice);

        // File upload preview
        const fileInput = document.getElementById('container_release_file');
        const fileUploadArea = document.querySelector('.file-upload-area');

        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const fileName = this.files[0].name;
                const fileSize = (this.files[0].size / 1024 / 1024).toFixed(2);

                fileUploadArea.innerHTML = `
                    <div class="file-upload-success">
                        <i class="fas fa-check-circle"></i>
                        <p>${fileName}</p>
                        <small>${fileSize} MB</small>
                        <button type="button" class="btn-change-file">ប្តូរឯកសារ</button>
                    </div>
                `;

                // Add change file functionality
                document.querySelector('.btn-change-file').addEventListener('click', function() {
                    fileInput.value = '';
                    location.reload();
                });
            }
        });

        // Truck filter
        const filterBtns = document.querySelectorAll('.filter-btn');
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const filter = this.dataset.filter;
                const trucks = document.querySelectorAll('.truck-card');

                trucks.forEach(truck => {
                    if (filter === 'all') {
                        truck.style.display = 'block';
                    } else if (filter === 'available') {
                        if (truck.dataset.status === 'available') {
                            truck.style.display = 'block';
                        } else {
                            truck.style.display = 'none';
                        }
                    }
                });
            });
        });

        // Review booking
        document.getElementById('review-booking').addEventListener('click', function() {
            // Validate form
            if (!originPort.value || !destinationProvince.value || !containerSize.value ||
                !weight.value || !document.getElementById('pickup_date').value ||
                !document.getElementById('delivery_date').value || !fileInput.files[0] ||
                !emptyReturnDepot.value || !document.querySelector('input[name="truck_id"]:checked')) {
                alert('សូមបំពេញព័ត៌មានទាំងអស់ឲ្យបានត្រឹមត្រូវ');
                return;
            }

            // Build confirmation content
            const selectedTruck = document.querySelector('input[name="truck_id"]:checked');
            const truckCard = selectedTruck.closest('.truck-card');
            const truckPlate = truckCard.querySelector('.truck-plate').textContent;
            const truckLocation = truckCard.querySelector('.truck-location').textContent.trim();

            const originPortText = originPort.selectedOptions[0].text;
            const destinationText = destinationProvince.selectedOptions[0].text;
            const containerSizeText = containerSize.selectedOptions[0].text;
            const depotText = emptyReturnDepot.selectedOptions[0].text;

            const pickupDate = document.getElementById('pickup_date').value;
            const deliveryDate = document.getElementById('delivery_date').value;

            const formattedPickupDate = new Date(pickupDate).toLocaleDateString('km-KH');
            const formattedDeliveryDate = new Date(deliveryDate).toLocaleDateString('km-KH');

            const totalAmount = document.getElementById('total_amount').value;
            const depositAmount = document.getElementById('deposit_amount').value;

            const content = `
                <div class="confirmation-group">
                    <h4>ទីតាំង</h4>
                    <div class="confirmation-row">
                        <span>កំពង់ផែចាប់ផ្តើម:</span>
                        <span>${originPortText}</span>
                    </div>
                    <div class="confirmation-row">
                        <span>ខេត្តគោលដៅ:</span>
                        <span>${destinationText}</span>
                    </div>
                </div>
                <div class="confirmation-group">
                    <h4>ព័ត៌មានទំនិញ</h4>
                    <div class="confirmation-row">
                        <span>ទំហំកុងតឺន័រ:</span>
                        <span>${containerSizeText}</span>
                    </div>
                    <div class="confirmation-row">
                        <span>ទម្ងន់:</span>
                        <span>${weight.value} គីឡូក្រាម</span>
                    </div>
                    <div class="confirmation-row">
                        <span>កាលបរិច្ឆេទយកទំនិញ:</span>
                        <span>${formattedPickupDate}</span>
                    </div>
                    <div class="confirmation-row">
                        <span>កាលបរិច្ឆេទបញ្ជូនទំនិញ:</span>
                        <span>${formattedDeliveryDate}</span>
                    </div>
                    <div class="confirmation-row">
                        <span>ទីតាំងប្រគល់កុងតឺន័រវិញ:</span>
                        <span>${depotText}</span>
                    </div>
                </div>
                <div class="confirmation-group">
                    <h4>រថយន្តដឹកជញ្ជូន</h4>
                    <div class="confirmation-row">
                        <span>ស្លាកលេខ:</span>
                        <span>${truckPlate}</span>
                    </div>
                    <div class="confirmation-row">
                        <span>ទីតាំងបច្ចុប្បន្ន:</span>
                        <span>${truckLocation}</span>
                    </div>
                </div>
                <div class="confirmation-group">
                    <h4>តម្លៃ</h4>
                    <div class="confirmation-row">
                        <span>តម្លៃដឹកជញ្ជូនមូលដ្ឋាន:</span>
                        <span>$${parseFloat(document.getElementById('transport_fee').value).toFixed(2)}</span>
                    </div>
                    <div class="confirmation-row">
                        <span>ថ្លៃបន្ថែមទម្ងន់លើស:</span>
                        <span>$${parseFloat(document.getElementById('overweight_charge').value).toFixed(2)}</span>
                    </div>
                    <div class="confirmation-row">
                        <span>ថ្លៃប្រគល់កុងតឺន័រវិញ:</span>
                        <span>$${parseFloat(document.getElementById('empty_return_fee').value).toFixed(2)}</span>
                    </div>
                    <div class="confirmation-row total">
                        <span>តម្លៃសរុប:</span>
                        <span>$${parseFloat(totalAmount).toFixed(2)}</span>
                    </div>
                    <div class="confirmation-row deposit">
                        <span>ត្រូវបង់ប្រាក់ជាមុន ៥០%:</span>
                        <span>$${parseFloat(depositAmount).toFixed(2)}</span>
                    </div>
                </div>
            `;

            document.getElementById('confirmation-content').innerHTML = content;
            document.getElementById('confirmation-panel').style.display = 'block';
            document.querySelector('.form-actions').style.display = 'none';
            window.scrollTo({ top: document.getElementById('confirmation-panel').offsetTop - 100, behavior: 'smooth' });
        });

        // Cancel booking
        document.getElementById('cancel-booking').addEventListener('click', function() {
            document.getElementById('confirmation-panel').style.display = 'none';
            document.querySelector('.form-actions').style.display = 'block';
        });

        // Validate delivery date >= pickup date
        document.getElementById('pickup_date').addEventListener('change', function() {
            const deliveryDate = document.getElementById('delivery_date');
            deliveryDate.min = this.value;
            if (deliveryDate.value && deliveryDate.value < this.value) {
                deliveryDate.value = '';
            }
        });
    });
</script>

</body>
</html>
