@if ($user_kycs->count() > 0)
    <div class="">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped w-630" id="user_kycs_table">
                    <thead>
                        <tr>
                            <th style="width: 5%;" scope="col">{{ __('ui.sno') }}</th>
                            <th style="width: 25%;" class="col-2">{{ __('ui.id') }}</th>
                            <th style="width: 15%;" class="col-2">{{ __('ui.document_name') }}</th>
                            <th style="width: 15%;" class="col-2">{{ __('ui.document_no') }}</th>
                            <th style="width: 15%;" class="col-2">{{ __('ui.status') }}</th>
                            <th style="width: 20%;" class="col-2">{{ __('ui.created_at') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($user_kycs as $userKyc)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> <a href="{{ route('panel.admin.users.verification.show', secureToken($userKyc->id)) }}"
                                        class="text-dark">{{ $userKyc->getPrefix() }}</a> </td>
                                <td> {{ $userKyc->details['document_type'] ?? '' }} </td>
                                <td> {{ $userKyc->details['document_number'] ?? '' }} </td>
                                <td> {{ App\Models\UserKyc::STATUSES[$userKyc->status]['label'] ?? '' }}</td>
                                <td> {{ $userKyc->created_at->format('Y-m-d') ?? '' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="5">{{ __('ui.no_data') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endif
