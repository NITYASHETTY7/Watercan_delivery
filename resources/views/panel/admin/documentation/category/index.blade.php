@extends('layouts.main')
@section('title', @$label)
@section('content')
    @php
        // $mini_sidebar =1;
        $breadcrumb_arr = [['name' => $label, 'url' => 'javascript:void(0);', 'class' => 'active']];
    @endphp

    @push('head')
        <style>
            .sidebar-docs {
                background-color: #f2f5ff;
                height: 460px;
                overflow-y: scroll;
                width: 20%;
                position: fixed;
            }

            .custom-margin-left {
                margin-left: 300px;
            }

            .text-heading {
                font-size: 20px;
                margin-top: 5px;
            }

            .fs-14 {
                font-size: 14px;
                font-weight: 600;
            }

            .active {
                color: #000 !important;
            }

            .load-article:hover {
                color: #000 !important;
            }
        </style>
    @endpush

    <div class="container-fluid container-fluid-height">
        <div class="page-header">
            <div class="row align-items-end mb-4">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ @$label }}</h5>
                            <span> @lang('ui.list_of') {{ $label ?? '--' }} </span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    @include('panel.admin.include.breadcrumb.index')
                </div>
            </div>
            <div class="row">
                @foreach ($categories ?? [] as $category)
                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                        <div class="category-document">
                            <a href="{{ route('panel.admin.resources.category', secureToken($category->id)) }}"
                                id="load-article-{{ @$category->id ?? '' }}" class="d-flex justify-content-between">
                                <h5 class="m-0 fw-700">{{ $loop->iteration ?? '' }}. {{ $category->name ?? '' }}</h5>
                                <div>
                                    <i class="ik ik-chevron-right fa-2x"></i>
                                </div>
                            </a>
                            <a href="{{ route('panel.admin.resources.category', secureToken($category->id)) }}"
                                class="m-0 text-muted">{{ $category->faqs->count() }} Question</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>


@endsection
@push('script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    {{-- START AJAX FROM INIT --}}
    <script>
        function loadCaregories(id) {
            let category_id = id;
            $('.load-article').removeClass('active');
            $(`#load-article-${id}`).addClass('active');
            let url = "http://127.0.0.1:8000/admin/resources/" + id + "?category_id=" + category_id;
            window.history.pushState(null, null, url);
            $.ajax({
                url: url,
                method: 'GET',
                data: {
                    id: id
                },
                success: function(res) {
                    $('#textToCopy').html(res.artical.description);
                    $(res).ready(function() {
                        $('#data').hide();
                    });
                }
            })
        }
    </script>
    {{-- END AJAX FORM INIT --}}

    {{-- START CUSTOM JS INIT --}}
    <script></script>
    {{-- END CUSTOM JS INIT --}}
@endpush
