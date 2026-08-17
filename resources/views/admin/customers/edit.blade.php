@extends('admin.layouts.admin')
@section('title','កែប្រែអតិថិជន')
@section('page-title')<span>កែប្រែ</span>អតិថិជន@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin_customers.css') }}">
@endpush

@section('content')
<div class="card cst-edit-card">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-user-edit"></i> កែប្រែព័ត៌មានអតិថិជន</div>
        <a href="{{ route('admin.customers.index') }}" class="btn btn-sm cst-btn-cancel">
            <i class="fas fa-arrow-left"></i> ត្រឡប់ក្រោយ
        </a>
    </div>

    <div class="cst-edit-body">

        @if($errors->any())
            <div class="cst-error-alert">
                <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.customers.update', $customer->customer_id) }}">
            @csrf @method('PUT')

            <div class="form-grid">
                <div class="form-group form-full">
                    <label class="form-label">ឈ្មោះពេញ</label>
                    <input type="text" name="full_name" class="form-control"
                           value="{{ old('full_name', $customer->full_name) }}" required>
                </div>

                <div class="form-group form-full">
                    <label class="form-label">អ៊ីមែល</label>
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

            <div class="cst-edit-btn-row">
                <button type="submit" class="btn btn-primary cst-btn-save">
                    <i class="fas fa-save"></i> រក្សាទុក
                </button>
                <a href="{{ route('admin.customers.index') }}" class="btn btn-sm cst-btn-cancel-form">
                    បោះបង់
                </a>
            </div>
        </form>
    </div>
</div>
@endsection