@extends('layouts.main')
@section('title', @$label)
@section('content')
    @php
        // $mini_sidebar =1;
        $breadcrumb_arr = [
            ['name' => __('ui.documents_categories'), 'url' => 'route('panel.admin.resources.documentation')', 'class' => ''],
            ['name' => $label, 'url' => 'javascript:void(0);', 'class' => 'active'],
        ];
    @endphp

    @push('head')
        <style>
            .sidebar-docs {
                background-color: #f2f5ff;
                height: 460px;
                overflow-y: scroll;
                width: 285px;
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

            .bg-custom-color {
                background-color: #f2f5ff !important;
            }

            .w-90 {
                width: 90% !important;
            }
        </style>
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8 mb-4">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ @$label }}</h5>
                            <span> @lang('ui.list_of') {{ @$label ?? '--' }} </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    @include('panel.admin.include.breadcrumb.index')
                </div>
                <div class="d-flex">
                    <div class="bg-custom-color">
                        <div class="uk-container sidebar-docs">
                            <a href="{{ route('panel.admin.resources.documentation') }}"
                                class="btn btn-light btn-lg btn-block">@lang('ui.back')</a>
                            <div class="text-muted mb-2 p-3 pl-0 pr-0 fw-700 fs-14">
                                <div class="text-dark">
                                    <ul class="pl-3 list-unstyled">
                                        @foreach ($articles as $art)
                                            <li class="text-muted mb-2">
                                                <a href="javascript:void(0)" onclick="loadArticle({{ $art->id }})"
                                                    class="load-article text-muted  active"
                                                    id="load-article-{{ $art->id }}">Q.{{ $loop->iteration }}
                                                    {{ $art->title }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="custom-margin-left html-content">
                        <div class="description fs-14 text-muted w-90 mt" id="textToCopy" >
                            @if (isset($article))
                                {!! $article->description ?? '' !!}
                            @endif
                        </div>
                    </div> --}}
                    @if (isset($article))
                        <div class="article-copy-btn">
                            <a href="javascript:void(0)" class="btn btn-light" id="copyButton">@lang('ui.copy')</a>
                        </div>
                        <div class="custom-margin-left">
                            <div class="d-flex justify-content-start area-head-title">
                                <h2 class="mb-0 text-muted question fw-700">Q.</h2>
                                <div style="padding-left: 5px" class="text-heading text-dark fw-700">
                                    {{ @$article->title }}
                                </div>
                            </div>
                            <div style="margin-left: 35px;" class="text-muted">
                                <i class="ik ik-clock"></i>
                                <span>
                                    {{ $article->created_at ? $article->created_at->diffForHumans() : '--' }}
                                </span>
                            </div>
                            <div class="fs-14 text-muted">
                                <p>{!! $article->description ?? 'Description not available.' !!}</p>
                            </div>
                        </div>
                    @else
                        <div class="custom-margin-left">
                            <div class="pt-4 pb-4">
                                <br>
                                <i class="fa fa-quote-left mb-2 pl-2"></i>
                                <br>
                                <h4 class="fw-500">
                                    <blockquote class="pl-2" style="border-left: 3px solid #ff3c63 ">
                                        You have to embrace – not fear – the challenges. Dream, be fearless, and follow
                                        through.</strong>
                                    </blockquote>
                                </h4>
                            </div>
                        </div>
                    @endif
                </div>

                <div id="data" class="load-article fw-700">
                    @if (!isset($article))
                        @lang('ui.no_articals_select')
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    {{-- START JS HELPERS INIT --}}
    <script>
        function loadArticle(id) {
            $('.load-article').removeClass('active');
            $(`#load-article-${id}`).addClass('active').siblings().removeClass('active');
            let url = "http://127.0.0.1:8000/admin/resources/articles/" + id + "/show";
            window.history.pushState(null, null, url);
            $.ajax({
                url: url,
                method: 'GET',
                data: {
                    id: id ?? null
                },
                success: function(res) {
                    $('#textToCopy').html(res.article.description);
                    $(res).ready(function() {
                        $('#data').hide();
                    });
                }
            });
        }
    </script>
    {{-- END JS HELPERS INIT --}}

    {{-- START CUSTOME JS INIT --}}
    <script></script>
    {{-- END CUSTOME JS INIT --}}
@endpush
