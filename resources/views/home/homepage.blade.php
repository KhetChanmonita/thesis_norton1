@extends('layouts.app')

@section('title', 'ទំព័រដើម - LS Trucking Service')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/responsive-home.css') }}">
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-overlay"></div>

        <div class="hero-content">
            <!-- Large Khmer Welcome Text -->
            <div class="khmer-welcome-container">
                <h1 class="khmer-welcome-text">សូមស្វាគមន៍មកកាន់</h1>
            </div>
            
            <div class="responsive-text-center">
                <p>
                    សេវាកម្មដឹកជញ្ជូនទំនិញដែលផ្ដល់ទំនុកចិត្តបំផុតនៅកម្ពុជា។ ក្រុមហ៊ុនយើងមានផ្តល់សេវា<br class="responsive-hide-mobile">
                    ដឹកជញ្ជូនពីរោងចក្រទៅកាន់កំពង់ផែស្វ័យយ័តព្រះសីហនុ និងកំពង់ផែស្វ័យយ័ត<br class="responsive-hide-mobile">
                    ភ្នំពេញ (EXPORT) ហើយយើងក៏ផ្ដល់សេវាដឹកជញ្ជូនពីកំពង់ផែស្វ័យយ័ត<br class="responsive-hide-mobile">
                    ព្រះសីហនុ និងកំពង់ផែស្វ័យយ័តភ្នំពេញ ទៅកាន់រោងចក្រនៅក្នុងស្រុក<br class="responsive-hide-mobile">
                    (IMPORT) ដោយមានគុណភាពនិងសុវត្ថិភាពខ្ពស់បំផុត។ <br class="responsive-hide-mobile">
                </p>
            </div>

            <div class="hero-buttons">
                <a href="#" class="btn-primary">កក់សេវា</a>
                <a href="#" class="btn-outline">ស្វែងយល់បន្ថែម</a>
            </div>
        </div>
    </section>

    <!-- Include Service -->
    @include('service')
@endsection