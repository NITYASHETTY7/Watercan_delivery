{{-- Add Address  Modal --}}
    <div id="addAddressModal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-[999] flex items-center justify-center top-0">
        <div class="bg-white rounded-xl w-[90%] shadow-xl border border-gray-100 relative overflow-auto max-h-[90vh]">

            <!-- Header -->
            <div class="flex justify-between items-center mb-2 px-4 py-2 border-b">
                <h2 class="text-lg font-semibold text-gray-900 mb-0">Add New Address</h2>
                <button id="closeModalBtn" class="text-gray-700">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <div class="p-4 pt-0">
                <form action="{{ route('panel.user.address.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="hidden"name="sso_token" value="{{ request()->get('sso_token') }}">

                    <!-- Address Type -->
                    <div>
                        <label class="text-sm font-medium text-gray-700">Address Type</label>
                        <select name="type" class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 text-sm"
                            required>
                            <option value="0" selected>Home</option>
                            <option value="1">Office</option>
                        </select>
                    </div>

                    <!-- Google Autocomplete Search -->
                    <div>
                        <label class="text-sm font-medium text-gray-700">Search Address</label>
                        <input id="google_address" type="text"
                            class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 text-sm"
                            placeholder="Search location…" />
                    </div>

                    <!-- Google Map -->
                    <div>
                        <div id="map" class="w-full h-64 rounded-lg border"></div>
                    </div>

                    <!-- Address Line 1 (Filled by Google) -->
                    <div>
                        <label class="text-sm font-medium text-gray-700">Address Line 1</label>
                        <input type="text" id="address_1" name="address_1"
                            class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 text-sm"
                            placeholder="Flat, House no., Building" required />
                    </div>

                    <!-- Address Line 2 -->
                    <div>
                        <label class="text-sm font-medium text-gray-700">Address Line 2</label>
                        <input type="text" name="address_2"
                            class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 text-sm"
                            placeholder="Area, Street" />
                    </div>

                    <!-- Hidden Lat/Lng -->
                    <input type="hidden" id="latitude" name="latitude" required />
                    <input type="hidden" id="longitude" name="longitude" required />

                    <!-- Pincode -->
                    <div>
                        <label class="text-sm font-medium text-gray-700">Pincode</label>
                        <input type="text" id="pincode" name="pincode"
                            class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 text-sm" maxlength="6"
                            placeholder="560095" required />
                    </div>

                    <!-- Submit -->
                    <div class="pt-3">
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg shadow-sm">
                            Save Address
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>