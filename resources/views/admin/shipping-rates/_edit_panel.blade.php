{{-- _edit_panel.blade.php  params: $panelId, $mapKey, $portLabel, $direction --}}

{{-- Styles loaded via admin_shipping_rates.css --}}

{{-- Province selector --}}
<div style="margin-bottom:18px;">
    <label class="sr-province-label">
        <i class="fas fa-map-marker-alt" style="margin-right:5px;color:#ff6b00;"></i>
        ជ្រើសរើសខេត្ត-រាជធានី
    </label>
    <div class="sr-select-wrap">
        <select id="sel_{{ $panelId }}" style="display:none;">
            <option value="">-- ជ្រើសរើស --</option>
            @foreach($provinces as $p)
            <option value="{{ $p['en'] }}">{{ $p['km'] }}  ({{ $p['en'] }})</option>
            @endforeach
        </select>

        <button type="button" id="btn_{{ $panelId }}" class="sr-select-btn"
                onclick="toggleProvinceDropdown('{{ $panelId }}')">
            <span id="btn_label_{{ $panelId }}">-- ជ្រើសរើស --</span>
            <i class="fas fa-chevron-down"
               style="position:absolute;right:14px;top:50%;transform:translateY(-50%);
                      color:#94a3b8;font-size:0.75rem;pointer-events:none;"></i>
        </button>

        <div id="list_{{ $panelId }}" class="sr-select-list">
            <div class="sr-select-option"
                 onclick="selectProvince('{{ $panelId }}','{{ $mapKey }}','','-- ជ្រើសរើស --')">
                -- ជ្រើសរើស --
            </div>
            @foreach($provinces as $p)
            <div class="sr-select-option"
                 onclick="selectProvince('{{ $panelId }}','{{ $mapKey }}','{{ $p['en'] }}', {{ json_encode($p['km'].'  ('.$p['en'].')') }})">
                {{ $p['km'] }}  ({{ $p['en'] }})
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Price edit (hidden until province chosen) --}}
<div id="price_wrap_{{ $panelId }}" style="display:none;">

    <div class="sr-price-current">
        <div>
            <div class="sr-price-current-lbl"><i class="fas fa-tag" style="margin-right:5px;"></i>តម្លៃបច្ចុប្បន្ន</div>
        </div>
        <span id="current_{{ $panelId }}" class="sr-price-current-val">$0.00</span>
    </div>

    <form id="form_{{ $panelId }}" method="POST" action="">
        @csrf @method('PUT')
        <div class="sr-form-row">
            <div class="sr-input-group">
                <label class="sr-input-label">
                    <i class="fas fa-edit" style="margin-right:4px;"></i>
                    តម្លៃថ្មី
                </label>
                <div class="sr-price-input-wrap">
                    <span class="sr-price-prefix">$</span>
                    <input type="number" id="price_{{ $panelId }}" name="base_price"
                           min="0" step="0.01" placeholder="0.00"
                           class="sr-price-input">
                </div>
            </div>
            <button type="submit" id="save_{{ $panelId }}" class="sr-save-btn">
                <i class="fas fa-save"></i> រក្សាទុក
            </button>
        </div>
    </form>
</div>
