<div id="confirmActionModal"
    class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-[9999] flex items-center justify-center">
    <div class="bg-white rounded-2xl p-6 w-[22rem] shadow-xl border border-gray-100 text-center space-y-4">
        <h3 id="modalTitle" class="text-xl font-bold text-gray-900"></h3>
        <p id="modalMessage" class="text-gray-600 text-sm leading-snug"></p>
        
        {{-- Compensation Note (Hidden by default, shown when deduction applies) --}}
        <p id="compensationNote" class="text-xs text-red-600 font-medium hidden">
            <i class="fas fa-triangle-exclamation mr-1"></i> 
           A standard deduction of ₹100 will be applied.
        </p>

        <div class="flex justify-center gap-3 pt-2">
            <button id="confirmActionYes" class="bg-red-500 hover:bg-red-600 text-white text-sm px-4 py-2 rounded-md transition-colors">Yes, Cancel</button>
            <button id="confirmActionNo"
                class="bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm px-4 py-2 rounded-md transition-colors">No, Keep Order</button>
        </div>
    </div>
</div>