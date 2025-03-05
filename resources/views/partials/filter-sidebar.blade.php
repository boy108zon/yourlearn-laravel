<aside id="sidebar" class="js-sidebar bg-white">
    <div class="h-100">
         <div class="sidebar-logo d-flex align-items-center">
            <img style="max-width: 150px;max-height: 30px;" src="https://yourlearn.in/dist/img/yourlearn-logo.png" alt="YourLearn Logo" class="sidebar-logo-img img-fluid" />
            <a href="https://yourlearn.in" class="">Your Learn</a>
        </div>
        
        <aside class="border-end p-4">
            <div>
                <div class="row mb-4">
                    <div class="col-12 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fs-5 text-dark"><strong>Filters</strong></h6>
                        <button id="clear-filters" class="btn btn-light btn-sm">Clear All Filters</button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="category-filter" class="form-label">Select Category</label>
                        <select id="category-filter" class="form-select form-select-sm">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <hr />
                <div class="row">
                   <h6 class="mb-3 fs-5 text-dark"><strong>Range</strong></h6>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="min-price" class="form-label">Min Price</label>
                        <input type="number" id="min-price" class="form-control form-control-sm" placeholder="Min Price" min="0" step="1">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="max-price" class="form-label">Max Price</label>
                        <input type="number" id="max-price" class="form-control form-control-sm" placeholder="Max Price" min="0" step="1">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 mb-3">
                    <label for="search-box" class="form-label">Search Products</label>
                    <input type="text" id="search-box" class="form-control form-control-sm" placeholder="Search for a product..." />
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <button id="apply-filters" class="btn btn-primary w-100 btn-sm">
                            Apply Filters
                        </button>
                    </div>   
                </div>    
            </div>
        </aside>
    </div>
</aside>
