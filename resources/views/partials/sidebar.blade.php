<aside id="sidebar" class="js-sidebar bg-white">
    <div class="h-100">
         <div class="sidebar-logo d-flex align-items-center">
            <img style="max-width: 150px;max-height: 30px;" src="https://yourlearn.in/dist/img/yourlearn-logo.png" alt="YourLearn Logo" class="sidebar-logo-img img-fluid" />
            <a href="https://yourlearn.in" class="">Your Learn</a>
        </div>
        
        <ul class="sidebar-nav">
            @foreach($menus as $menu)
                @include('partials.sidebar-item', ['menu' => $menu]) 
            @endforeach
        </ul>

    </div>
</aside>
