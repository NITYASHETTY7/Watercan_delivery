<div class="modal fade" id="assignDriverModal" tabindex="-1" role="dialog" aria-labelledby="assignDriverModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignDriverModalLabel"><i class="fa fa-user-tie mr-1"></i> Assign Driver</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('panel.admin.orders.assign-driver', secureToken(@$order->id)) }}" method="get" id="assignDriverFormModal">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="assign_to_modal" class="fw-700">Select Driver <span class="text-danger">*</span></label>
                        <div>
                            <x-select 
                                name="assign_to"
                                id="assign_to_modal"
                                class="form-control select2 getUsersList" 
                                label="Driver"
                                validation="required"
                                value="{{ old('assign_to', $order->assign_to ?? '') }}"
                                optionName="name"
                            />
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label for="remark_modal" class="fw-700">Add Remark (for your reference)</label>
                        <textarea 
                            name="remark" 
                            id="remark_modal" 
                            class="form-control" 
                            rows="3" 
                            placeholder="Enter remark here">{{ old('remark', $order->remark ?? '') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Assign Driver</button>
                </div>
            </form>
        </div>
    </div>
</div>