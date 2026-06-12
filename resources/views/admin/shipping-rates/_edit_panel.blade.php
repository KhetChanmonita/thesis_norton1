{{-- _edit_panel.blade.php  params: $panelId, $mapKey, $portLabel, $direction --}}
<div class="card">
    <div class="card-header">
        <div class="card-title" style="font-size:0.88rem;">
            @if($direction === 'import')
                <i class="fas fa-anchor" style="color:#ff6b00;margin-right:6px;"></i>
                <span>{{ $portLabel }}</span>
                <span style="color:#94a3b8;margin:0 6px;">&rarr;</span>
                <span style="color:#64748b;">ខេត្ត/រាជធានី</span>
            @else
                <i class="fas fa-truck-loading" style="color:#3b82f6;margin-right:6px;"></i>
                <span style="color:#64748b;">ខេត្ត/រាជធានី</span>
                <span style="color:#94a3b8;margin:0 6px;">&rarr;</span>
                <span>{{ $portLabel }}</span>
            @endif
        </div>
    </div>

    <div style="padding:22px 20px;">

        {{-- Province selector --}}
        <div style="margin-bottom:18px;">
            <label style="display:block;font-size:0.8rem;font-weight:700;color:#64748b;margin-bottom:7px;">
                <i class="fas fa-map-marker-alt" style="margin-right:5px;color:#ff6b00;"></i>
                ជ្រើសរើសខេត្ត-រាជធានី
            </label>
            <div style="position:relative;">
                <select id="sel_{{ $panelId }}"
                        onchange="onProvinceChange('{{ $panelId }}','{{ $mapKey }}')"
                        style="width:100%;padding:11px 38px 11px 14px;border:1.5px solid #e2e8f0;
                               border-radius:10px;font-family:'Kantumruy Pro',sans-serif;
                               font-size:0.9rem;font-weight:600;color:#1e293b;background:#fff;
                               appearance:none;cursor:pointer;outline:none;transition:border 0.2s;">
                    <option value="">-- ជ្រើសរើសខេត្ត --</option>
                    @foreach($provinces as $p)
                    <option value="{{ $p['en'] }}">{{ $p['km'] }}  ({{ $p['en'] }})</option>
                    @endforeach
                </select>
                <i class="fas fa-chevron-down"
                   style="position:absolute;right:14px;top:50%;transform:translateY(-50%);
                          color:#94a3b8;font-size:0.75rem;pointer-events:none;"></i>
            </div>
        </div>

        {{-- Price edit (hidden until province chosen) --}}
        <div id="price_wrap_{{ $panelId }}" style="display:none;">

            <div style="background:#f8fafc;border-radius:10px;padding:14px 16px;margin-bottom:16px;
                        display:flex;align-items:center;justify-content:space-between;">
                <span style="font-size:0.8rem;color:#64748b;font-weight:600;">តម្លៃបច្ចុប្បន្ន</span>
                <span id="current_{{ $panelId }}"
                      style="font-size:1.1rem;font-weight:800;color:#ff6b00;font-family:'Poppins',sans-serif;">
                    $0.00
                </span>
            </div>

            <form id="form_{{ $panelId }}" method="POST" action=""
                  style="display:flex;align-items:flex-end;gap:10px;">
                @csrf @method('PUT')
                <div style="flex:1;">
                    <label style="display:block;font-size:0.8rem;font-weight:700;color:#64748b;margin-bottom:7px;">
                        <i class="fas fa-edit" style="margin-right:5px;"></i>
                        តម្លៃថ្មី ($)
                    </label>
                    <div style="position:relative;">
                        <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);
                                     font-weight:800;color:#ff6b00;font-size:0.95rem;">$</span>
                        <input type="number" id="price_{{ $panelId }}" name="base_price"
                               min="0" step="0.01" placeholder="0.00"
                               style="width:100%;padding:11px 12px 11px 28px;border:1.5px solid #e2e8f0;
                                      border-radius:10px;font-family:'Poppins',sans-serif;
                                      font-weight:700;font-size:1rem;outline:none;
                                      transition:border 0.2s;box-sizing:border-box;">
                    </div>
                </div>
                <button type="submit" id="save_{{ $panelId }}"
                        style="padding:11px 22px;background:linear-gradient(135deg,#ff6b00,#ff8c00);
                               color:#fff;border:none;border-radius:10px;font-family:'Kantumruy Pro',sans-serif;
                               font-size:0.88rem;font-weight:700;cursor:pointer;white-space:nowrap;
                               display:flex;align-items:center;gap:7px;transition:opacity 0.2s;">
                    <i class="fas fa-save"></i> រក្សាទុក
                </button>
            </form>
        </div>

    </div>
</div>