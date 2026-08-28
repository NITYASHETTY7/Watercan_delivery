@extends('layouts.main')
@section('title', __('Driver Dashboard'))

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-md mx-auto px-4">
        <!-- Welcome Section -->
        <div class="text-center mb-10">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">
                Welcome, {{ Auth::user()->name ?? 'Driver' }}
            </h2>
            <p class="text-gray-500 mt-2">Here’s a quick overview of your deliveries</p>
        </div>

        <!-- Delivery Overview -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-700 text-white rounded-2xl shadow-md py-10 px-6 text-center">
            <div class="mb-3">
                <i class="fi fi-truck-side text-4xl text-white/90"></i>
            </div>
            <h4 class="text-lg font-semibold mb-2">You have active deliveries!</h4>
            <p class="text-white/90 text-sm">
                Stay updated and manage your assigned orders efficiently.
            </p>

            <div class="mt-6">
                <a href="{{ route('panel.driver.order.index') }}"
                   class="inline-flex items-center justify-center bg-white text-blue-700 font-semibold text-sm px-5 py-2.5 rounded-full shadow hover:bg-blue-50 transition">
                    <i class="fi fi-tr-truck-moving text-base mr-2"></i>
                    View Assigned Orders
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
