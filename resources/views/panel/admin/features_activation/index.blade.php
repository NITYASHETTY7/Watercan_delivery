@extends('layouts.main')
@section('title', __('ui.features_activation'))
@section('content')
    @php
        $breadcrumb_arr = [
            ['name' => __('ui.features_activation'), 'url' => 'javascript:void(0);', 'class' => 'active'],
        ];
    @endphp
    <div class="container-fluid" id="data_container">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5> @lang('ui.features_activation') </h5>
                            <span> @lang('ui.website_page_heading') </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div>
                        @include('panel.admin.include.breadcrumb.index')
                    </div>
                    @include('panel.admin.setting.sitemodal.index', [
                        'title' => 'How to use',
                        'content' => 'You able to add or remove some functionality from this settings.',
                    ])
                </div>
            </div>
        </div>
        <div class="row">
            @foreach (@$groups as $key => $group)
                <div class="col-md-12 mt-3">
                    <h5>
                        {{ @$key }}
                    </h5>
                </div>
                @if (isset($group['options']) && is_iterable($group['options']))
                    @foreach ($group['options'] as $option)
                        <div class="col-md-3">
                            <div class="card mb-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <strong class="text-center">{{ isset($option['name']) ? $option['name'] : '' }}
                                            <i class="ik ik-help-circle text-muted" data-toggle="tooltip"
                                                title="{{ isset($option['tooltip']) ? $option['tooltip'] : '' }}"></i></strong>
                                        <div class="text-center">
                                            <input type="checkbox" class="js-switch save"
                                                data-key="{{ isset($option['key']) ? $option['key'] : '' }}" value="1"
                                                @if (getSetting(@$option['key']) == 1) checked @endif data-switchery="true" />
                                        </div>
                                    </div>

                                    @if (isset($option['sub_options']) && count($option['sub_options']))
                                        <hr>
                                        <div class="mb-3">
                                            <strong class="text-muted">
                                                Sub Options:
                                            </strong>
                                        </div>
                                        <ul class="list-unstyled">
                                            @foreach ($option['sub_options'] as $subOption)
                                                <li class="d-flex justify-content-between">
                                                    <p class="text-left fw-600">
                                                        {{ isset($subOption['name']) ? $subOption['name'] : '' }}
                                                    </p>
                                                    <div>
                                                        <input type="checkbox" class="js-switch save"
                                                            data-key="{{ isset($subOption['key']) ? $subOption['key'] : '' }}"
                                                            value="1" @if (isset($subOption['key']) && getSetting($subOption['key']) == 1) checked @endif
                                                            data-switchery="true" />
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            @endforeach
        </div>
    </div>
    @include('panel.admin.features_activation.include.pass_code.index')
@endsection

@push('script')
    {{-- START AJAX FORM INIT --}}
    <script>
        $('.save').change(function() {
            var key = $(this).data('key');
            var val = 0;
            if ($(this).prop('checked')) {
                val = 1;
            }
            $.ajax({
                url: "{{ route('panel.admin.setting.features-activation.store') }}",
                dataType: "json",
                method: "post",
                data: {
                    key: key,
                    val: val,
                },
                success: function(json) {
                    callback(json);
                }
            });
        });
    </script>
    {{-- START AJAX FORM INIT --}}

    {{-- START JS HELPERS INIT --}}
    <script>
        var isVerified = false;
        $(document).ready(function() {
            checkEligibility(isVerified);
        });

        function checkEligibility(isVerified) {
            if (isVerified == false) {
                $('#data_container').hide();
                $('#DelegateAccessModel').modal('show');
                // Delay the focus to ensure the modal is fully opened
                $('#DelegateAccessModel').on('shown.bs.modal', function() {
                    $('#accessCodeInput').focus();
                });
            } else {
                $('.close').trigger('click');
                $('#data_container').show();
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            var accessCodeForm = document.getElementById('accessCodeForm');
            var errorMessage = document.getElementById('errorMessage');

            accessCodeForm.addEventListener('submit', function(event) {
                event.preventDefault();
                var accessCodeInput = document.getElementById('accessCodeInput').value;
                if (accessCodeInput === '874387') {
                    isVerified = true;
                    checkEligibility(isVerified);
                    accessCodeInput.value = '';
                    errorMessage.style.display = 'none';
                } else {
                    errorMessage.style.display = 'block';
                }
            });
        });
    </script>
    {{-- END JS HELPERS INIT --}}
@endpush
