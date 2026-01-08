@php
$title = __('FAQ');
@endphp
@extends('layouts.main', ['title' => $title])

@section('content')

<!-- breadcrumb start -->
@include('partials.breadcrumbs2-section')
<!-- breadcrumb end -->

<!-- Faq Question section Start -->
@include('faq.faq-question')
<!-- Faq Question section End -->

<!-- Faq Section Start -->
@include('faq.faq-section')
<!-- Faq Section End -->
@endsection