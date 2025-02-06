<li class="sidebar-item {{ $menu->is_active ? 'active' : '' }}">
    <a href="{{ $menu->url }}" class="sidebar-link {{ count($menu->children) > 0 ? 'collapsed' : '' }}"
       @if(count($menu->children) > 0)
           data-bs-target="#menu-{{ $menu->id }}" 
           data-bs-toggle="collapse" 
           aria-expanded="{{ $menu->is_active ? 'true' : 'false' }}"
       @endif
    >
        <i class="fa-solid {{ $menu->icon }} pe-2"></i>
        {{ $menu->title }}
    </a>

    @if(count($menu->children) > 0)
    <ul id="menu-{{ $menu->id }}" class="sidebar-dropdown list-unstyled collapse ps-4 mt-2 {{ $menu->is_active ? 'show' : '' }}" data-bs-parent="#sidebar">
            @foreach($menu->children as $child)
                @include('partials.sidebar-item', ['menu' => $child])
            @endforeach
        </ul>
    @endif
</li>
