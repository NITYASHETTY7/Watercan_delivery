@extends('layouts.main')
@section('title', __('Delivery Report'))
@section('content')

    <div class="p-4 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Delivery Performance Report</h2>
            <p class="text-sm text-gray-500">Updated on {{ $updatedOn }}</p>
        </div>
    </div>
    <div class="bg-gray-50 min-h-screen py-5 px-4">
        <div class="max-w-5xl mx-auto space-y-8">


            @if ($isNewDriver)
                <!-- Message for new drivers -->
                <div class="flex flex-col items-center justify-center text-center py-10 text-gray-500 text-sm min-h-[70vh]">

                    <img src="{{ asset('user/assets/icons/career-growth.png') }}"alt="No Data"
                        class="w-[50px] mx-auto">
                    <p class="text-center text-gray-800 font-normal text-[15px] py-2">
                        No Insights to view!
                    </p>
                </div>
            @else
                <!-- Summary Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 !mt-0">
                    <div class="card bg-gradient-to-r from-blue-500 to-blue-800 text-white py-2 px-4 rounded-xl">
                        <p class="text-sm opacity-90">Completed Deliveries</p>
                        <h2 class="text-4xl font-bold mt-1">{{ $summary['delivered'] }}</h2>
                        <p class="text-xs opacity-80 mt-1">Out of total {{ $summary['total'] }} orders</p>
                    </div>

                    <div class="card flex justify-between items-start bg-white shadow-sm rounded-xl p-4">
                        <div class="flex items-center gap-2">
                            <span class="text-gray-700 font-normal text-[15px]">Total Orders:</span>
                            <span class="text-gray-900 font-bold text-[15px]">{{ $summary['total'] }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-gray-700 font-normal text-[15px]">Cancelled:</span>
                            <span class="text-red-600 font-bold text-[15px]">{{ $summary['cancelled'] }}</span>
                        </div>
                    </div>

                    <div class="card flex flex-col justify-center items-start bg-white shadow-sm rounded-xl p-4">
                        <p class="text-gray-700 font-semibold mb-1">Overall Success Rate</p>
                        <h2 class="text-5xl font-extrabold text-blue-600">{{ $summary['success_rate'] }}%</h2>
                        <p class="text-xs text-gray-500 mt-1">Deliveries completed successfully</p>
                    </div>
                </div>

                <!-- Chart Section -->
                <div class="card bg-white shadow-sm rounded-xl p-4">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Delivery Overview (Last 6 Days)</h2>
                    <canvas id="deliveryChart" height="220"></canvas>
                </div>

                <!-- Table Section -->
                <div class="card bg-white shadow-sm rounded-xl p-4 pb-10">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Delivery Performance History</h2>
                    <div class="overflow-x-auto max-h-[300px]">
                        <table class="min-w-full border border-gray-100 rounded-lg overflow-hidden text-sm">
                            <thead class="bg-gray-100 text-gray-700">
                                <tr>
                                    <th class="px-4 py-2 border">Date</th>
                                    <th class="px-4 py-2 border text-center">Pending</th>
                                    <th class="px-4 py-2 border text-center">Delivered</th>
                                    <th class="px-4 py-2 border text-center">Cancelled</th>
                                    <th class="px-4 py-2 border text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($tableData as $day)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 border text-center text-gray-700 min-w-[150px]">
                                            {{ $day['date'] }}</td>
                                        <td class="px-4 py-2 border text-center text-gray-500 font-semibold">
                                            {{ $day['pending'] }}</td>
                                        <td class="px-4 py-2 border text-center text-green-600 font-semibold">
                                            {{ $day['delivered'] }}</td>
                                        <td class="px-4 py-2 border text-center text-red-500 font-semibold">
                                            {{ $day['cancelled'] }}</td>
                                        <td class="px-4 py-2 border text-center text-gray-700 min-w-[150px]">
                                            {{ $day['total'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </div>

@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        @if (!$isNewDriver)
            const ctx = document.getElementById('deliveryChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartData['labels']) !!},
                    datasets: [{
                            label: 'Delivered',
                            data: {!! json_encode($chartData['delivered']) !!},
                            backgroundColor: 'rgba(34,197,94,0.8)',
                            borderRadius: 5
                        },
                        {
                            label: 'Cancelled',
                            data: {!! json_encode($chartData['cancelled']) !!},
                            backgroundColor: 'rgba(239,68,68,0.8)',
                            borderRadius: 5
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                color: '#374151',
                                font: {
                                    size: 13
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f3f4f6'
                            },
                            title: {
                                display: true,
                                text: 'Deliveries',
                                color: '#374151',
                                font: {
                                    family: 'Inter',
                                    size: 13
                                }
                            },
                            ticks: {
                                color: '#4b5563'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#4b5563'
                            }
                        }
                    }
                }
            });
        @endif
    </script>
@endpush
