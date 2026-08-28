@extends('layouts.main')
@section('title', __('Order Tracking'))
@section('content')

  <div class="max-w-5xl mx-auto space-y-8">

    <!-- Header -->
    <div class="text-center mb-4">
      <h1 class="text-2xl font-semibold text-gray-800">Delivery Performance Report</h1>
      <p class="text-gray-500 text-[13px]">Updated on 04 Nov 2025</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 !mt-0">
      <div class="card bg-gradient-to-r from-blue-500 to-blue-800 text-white py-2 px-4 rounded-xl">
        <p class="text-sm opacity-90">Completed Deliveries</p>
        <h2 class="text-4xl font-bold mt-1">64</h2>
        <p class="text-xs opacity-80 mt-1">Out of total 68 orders</p>
      </div>

      <div class="card flex justify-between items-start">
        <div class="flex items-center gap-2">
          <span class="text-gray-700 font-normal text-[15px]">Total Orders:</span>
          <span class="text-gray-900 font-bold text-[15px]">68</span>
        </div>
        <div class="flex items-center gap-2">
          <span class="text-gray-700 font-normal text-[15px]">Cancelled:</span>
          <span class="text-red-600 font-bold text-[15px]">4</span>
        </div>
      </div>

      <div class="card flex flex-col justify-center items-start">
        <p class="text-gray-700 font-semibold mb-1">Overall Success Rate</p>
        <h2 class="text-3xl font-bold text-blue-600">94%</h2>
        <p class="text-xs text-gray-500 mt-1">Deliveries completed successfully</p>
      </div>
    </div>

    <!-- Chart Section -->
    <div class="card">
      <h2 class="text-lg font-semibold text-gray-800 mb-4">Delivery Overview (Last 6 Days)</h2>
      <canvas id="deliveryChart" height="120"></canvas>
    </div>

    <!-- Table Section -->
    <div class="card pb-10">
      <h2 class="text-lg font-semibold text-gray-800 mb-4">Last 30 Days Delivery Data</h2>
      <div class="overflow-x-auto">
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
            <tr class="hover:bg-gray-50"style="">
              <td class="px-4 py-2 border text-center text-gray-700 min-w-[150px]">03 Nov 2025</td>
              <td class="px-4 py-2 border text-center text-gray-500 font-semibold">3</td>
              <td class="px-4 py-2 border text-center text-green-600 font-semibold">12</td>
              <td class="px-4 py-2 border text-center text-red-500 font-semibold">1</td>
              <td class="px-4 py-2 border text-center text-gray-700 min-w-[150px]">16</td>
            </tr>
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-2 border text-center text-gray-700 min-w-[150px]">02 Nov 2025</td>
              <td class="px-4 py-2 border text-center text-gray-500 font-semibold">2</td>
              <td class="px-4 py-2 border text-center text-green-600 font-semibold">15</td>
              <td class="px-4 py-2 border text-center text-red-500 font-semibold">0</td>
              <td class="px-4 py-2 border text-center text-gray-700 min-w-[150px]">17</td>
            </tr>
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-2 border text-center text-gray-700 min-w-[150px]">01 Nov 2025</td>
              <td class="px-4 py-2 border text-center text-gray-500 font-semibold">4</td>
              <td class="px-4 py-2 border text-center text-green-600 font-semibold">11</td>
              <td class="px-4 py-2 border text-center text-red-500 font-semibold">2</td>
              <td class="px-4 py-2 border text-center text-gray-700 min-w-[150px]">17</td>
            </tr>
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-2 border text-center text-gray-700 min-w-[150px]">31 Oct 2025</td>
              <td class="px-4 py-2 border text-center text-gray-500 font-semibold">1</td>
              <td class="px-4 py-2 border text-center text-green-600 font-semibold">13</td>
              <td class="px-4 py-2 border text-center text-red-500 font-semibold">1</td>
              <td class="px-4 py-2 border text-center text-gray-700 min-w-[150px]">15</td>
            </tr>
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-2 border text-center text-gray-700 min-w-[150px]">30 Oct 2025</td>
              <td class="px-4 py-2 border text-center text-gray-500 font-semibold">2</td>
              <td class="px-4 py-2 border text-center text-green-600 font-semibold">10</td>
              <td class="px-4 py-2 border text-center text-red-500 font-semibold">1</td>
              <td class="px-4 py-2 border text-center text-gray-700 min-w-[150px]">13</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </div>

@endsection
@push('script')
    <script>
    const ctx = document.getElementById('deliveryChart').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Oct 30', 'Oct 31', 'Nov 1', 'Nov 2', 'Nov 3', 'Nov 4'],
        datasets: [
          {
            label: 'Delivered',
            data: [10, 13, 11, 15, 12, 14],
            backgroundColor: 'rgba(34,197,94,0.8)',
            borderRadius: 5
          },
          {
            label: 'Cancelled',
            data: [1, 1, 2, 0, 1, 0],
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
            labels: { color: '#374151', font: { size: 13 } }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: { color: '#f3f4f6' },
            title: {
              display: true,
              text: 'Deliveries',
              color: '#374151',
              font: { family: 'Inter', size: 13 }
            },
            ticks: { color: '#4b5563' }
          },
          x: {
            grid: { display: false },
            ticks: { color: '#4b5563' }
          }
        }
      }
    });
  </script>
    
@endpush
