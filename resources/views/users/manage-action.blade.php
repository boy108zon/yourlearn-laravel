@if($actions)
    <div class="dropdown">
        <button class="btn btn-sm btn-bd-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            Choose
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                @foreach($actions as $action)
                  <li><a class="dropdown-item" href="{{ $action['route'] }}">{{ $action['label'] }}</a></li>
                @endforeach
        </ul>
    </div>
@endif
