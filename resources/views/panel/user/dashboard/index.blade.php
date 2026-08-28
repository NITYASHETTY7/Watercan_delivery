@extends('layouts.main')
@section('title', __('Dashboard'))

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-md mx-auto px-4">
        <!-- Welcome Section -->
        <div class="text-center mb-10">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">
                Welcome, {{ Auth::user()->name ?? 'User' }}
            </h2>
            <p class="text-gray-500 mt-2">Here’s your personalized dashboard overview</p>
        </div>

        <!-- Order Section -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-700 text-white rounded-2xl shadow-md py-10 px-6 text-center">
            <h4 class="text-lg font-semibold mb-3">Ready to place your next order?</h4>
            <div class="mt-4">
                <div class="flex gap-2 items-center flex-col">
                    <a href="{{ route('panel.user.subscriptions.index') }}"
                   class="inline-block bg-white text-blue-600 font-semibold text-sm px-5 py-2.5 rounded-full shadow hover:bg-blue-50 transition">
                    Manage Subscription
                </a>

                    <form action="{{ route('panel.user.cart.store') }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit"
                            class="bg-white text-blue-600 font-semibold text-sm px-5 py-2.5 rounded-full shadow hover:bg-blue-50 transition flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.3 5.2a1 1 0 001 1.3h10.7a1 1 0 001-1.3L17 13M10 17h4"/>
                            </svg>
                            Place a New Order
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
