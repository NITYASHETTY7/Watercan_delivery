<div id="confirmCancelModal"
    class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-[999] flex items-center justify-center">
    <div class="bg-white rounded-2xl p-6 w-[20rem] shadow-xl border border-gray-100 text-center space-y-4">
        <h3 class="text-lg font-semibold text-gray-900">Cancel Order</h3>
        <p class="text-gray-600 text-sm">Are you sure you want to cancel this order?</p>
        <div class="flex justify-center gap-3 pt-2">
            <button id="confirmCancelYes" class="bg-red-500 text-white text-sm px-4 py-2 rounded-md">Yes</button>
            <button id="confirmCancelNo"
                class="bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm px-4 py-2 rounded-md">No</button>
        </div>
    </div>
</div>
