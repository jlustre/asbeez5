@php
$title = __('Seller Dashboard');
@endphp
@extends('layouts.main2')

@section('content')
@include('partials.breadcrumbs-section',
['breadcrumbs' => [
['label' => 'Home', 'url' => route('home')],
// ['label' => 'Company', 'url' => route('about')],
['label' => $title],
]
])
<!-- breadcrumb end -->
{{-- <div class="container-fluid-lg section-b-space">
    <div class="row">
        <div class="col-12">
            @auth
            <p class="text-muted">Welcome, {{ filled(auth()->user()->username) ? auth()->user()->username :
                auth()->user()->email }}.</p>
            @endauth
            <div class="card mt-3">
                <div class="card-body">
                    <p>This is a placeholder seller dashboard. Hook up your seller metrics, orders, and products here.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<!-- user dashboard section Start -->
@include('seller.seller-dashboard-section')
<!-- user dashboard section End -->

<!-- seller profile Start -->
@include('seller.edit-seller-profile')
<!-- seller profile End -->

<!-- edit card Start -->
@include('seller.edit-seller-card')
<!-- edit card End -->

<!-- remove profile start -->
@include('seller.remove-seller-profile')
<!-- remove profile end -->

@endsection