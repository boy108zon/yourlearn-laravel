<nav class="navbar navbar-expand-lg px-3 border-bottom">
    @if(!empty($showSidebar) && ($showSidebar ?? true))
    <button id="sidebar-toggle" class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseWidthExample" aria-expanded="true" aria-controls="collapseWidthExample">
        <i class="bi bi-arrows-expand-vertical"></i>
    </button>
    @endif

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="navbar-collapse collapse w-100" id="navbarNav">
        <ul class="navbar-nav me-auto">
        </ul>

        <ul class="navbar-nav ms-auto">
            @guest
                @if (Route::has('login'))
                    
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                          <span class="ms-2 d-none d-sm-inline"> <i class="bi bi-person"></i> Profile</span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end w-auto" aria-labelledby="navbarDropdown">
                            <li><h6 class="dropdown-header">Welcome</h6></li>
                            <li><p class="dropdown-item"><a class="btn btn-primary" href="{{ route('login') }}">{{ __('Login') }}</a> To access your account.</p></li>
                            
                            <li><hr class="dropdown-divider"></li>
                            
                            <li><a class="dropdown-item" href="#">Manage Orders</a></li>
                            <li><a class="dropdown-item" href="#">Wishlist</a></li>
                        
                            <li><hr class="dropdown-divider"></li>

                            
                            <li><a class="dropdown-item" href="#">Edit Profile</a></li>
                            <li><a class="dropdown-item" href="#">Contact Us</a></li>
                        </ul>
                     </li>
                    
                @endif

                <li class="nav-item">
                        <a class="nav-link" href="{{ route('cart.index') }}"><i class="bi bi-bag"></i> Bag</a>
                </li>
            @else
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        <img src="{{ asset(Auth::user()->profile_picture) }}" class="avatar img-fluid rounded-circle" alt="User Profile" style="width: 30px; height: 30px; object-fit: cover;">
                        
                        <span class="ms-2 d-none d-sm-inline">Profile</span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><h6 class="dropdown-header">Welcome</h6></li>
                        <li><p class="dropdown-item">{{ Auth::user()->name }} , Manage your profile.</p></li>

                        <li><hr class="dropdown-divider"></li>

                        <li><a class="dropdown-item" href="#">Orders</a></li>
                        <li><a class="dropdown-item" href="#">Wishlist</a></li>
                        <li><a class="dropdown-item" href="#">Gift Cards</a></li>
                        <li><a class="dropdown-item" href="#">Contact Us</a></li>
                       
                        <li><hr class="dropdown-divider"></li>
                        
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            @endguest
        </ul>
    </div>
</nav>
