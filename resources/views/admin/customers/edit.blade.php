@extends('admin.layouts.admin')
@section('title','កែប្រែអតិថិជន')
@section('page-title')<span>កែប្រែ</span>អតិថិជន@endsection

@section('content')
<div class="card" style="max-width:640px;margin:0 auto;">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-user-edit"></i> កែប្រែព័ត៌មានអតិថិជន</div>
        <a href="{{ route('admin.customers.index') }}" class="btn btn-sm" style="background:#f1f5f9;color:#64748b;">
            <i class="fas fa-arrow-left"></i> ត្រឡប់ក្រោយ
        </a>
    </div>

    <div style="padding:28px 28px 32px;">

        @if($errors->any())
            <div style="background:#fef2f2;border:1px solid #fca5a5;border-radius:8px;padding:12px 16px;margin-bottom:20px;color:#dc2626;font-size:0.88rem;">
                <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.customers.update', $customer->customer_id) }}">
            @csrf @method('PUT')

            <div class="form-grid">
                <div class="form-group form-full">
                    <label class="form-label">ឈ្មោះពេញ <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="full_name" class="form-control"
                           value="{{ old('full_name', $customer->full_name) }}" required>
                </div>

                <div class="form-group form-full">
                    <label class="form-label">អ៊ីមែល <span style="color:#ef4444;">*</span></label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email', $customer->email) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">លេខទូរស័ព្ទ</label>
                    <input type="tel" name="phone" class="form-control"
                           value="{{ old('phone', $customer->phone) }}" placeholder="012 345 678">
                </div>

                <div class="form-group">
                    <label class="form-label">ក្រុមហ៊ុន</label>
                    <input type="text" name="company_name" class="form-control"
                           value="{{ old('company_name', $customer->company_name) }}" placeholder="ឈ្មោះក្រុមហ៊ុន">
                </div>

                <div class="form-group form-full">
                    <label class="form-label">អាសយដ្ឋាន</label>
                    <textarea name="address" class="form-control" rows="3"
                              placeholder="អាសយដ្ឋាន">{{ old('address', $customer->address) }}</textarea>
                </div>
            </div>

            <div style="display:flex;gap:12px;margin-top:8px;">
                <button type="submit" class="btn btn-primary" style="padding:10px 28px;">
                    <i class="fas fa-save"></i> រក្សាទុក
                </button>
                <a href="{{ route('admin.customers.index') }}" class="btn btn-sm"
                   style="background:#f1f5f9;color:#64748b;padding:10px 20px;">
                    បោះបង់
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
