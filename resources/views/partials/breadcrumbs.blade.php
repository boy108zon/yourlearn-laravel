@php
   $breadcrumbs = app(App\Services\BreadcrumbService::class)->generateBreadcrumbs();
@endphp
@if(!empty($breadcrumbs))
<div class="container-fluid">
    <div aria-label="breadcrumb">
        <ol class="breadcrumb bg-light p-3 rounded shadow-sm">
            

            @foreach ($breadcrumbs as $breadcrumb)
                <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}">
                    @if ($loop->last)
                        {{ $breadcrumb->title }} 
                    @else
                        <a href="{{ url($breadcrumb->url) }}" class="text-decoration-none text-dark">
                            <i class="{{ $breadcrumb->icon}} me-2"></i>{{ $breadcrumb->title }}
                        </a>
                    @endif
                </li>
            @endforeach
        </ol>
    </div>
</div>
@endif

