<style>
    @media (max-width: 767.98px) {
        .breadcrumb-container {
            width: 100%;
            display: flex;
            background-color: rgb(245, 245, 245);
            padding: 0;
            margin-top: 5px;

        }
    }
</style>

@if (isset($breadcrumb_arr))
    <nav class="breadcrumb-container" aria-label="breadcrumb" style="margin: 0;">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('panel.admin.dashboard.index') }}"><i class="ik ik-home"></i></a>
            </li>
            @foreach ($breadcrumb_arr as $breadcrumb_item)
        
                @if ($breadcrumb_item !== null && is_array($breadcrumb_item))
                    <li class="breadcrumb-item {{ $breadcrumb_item['class'] ?? '' }}"><a
                            href="{{ $breadcrumb_item['url'] ?? 'javascript:void(0);' }}"
                            class="item">{{ $breadcrumb_item['name'] ?? '' }}
                        </a></li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif
