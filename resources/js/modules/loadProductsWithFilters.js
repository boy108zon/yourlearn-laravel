let currentPage = 1;
let currentCategory = null; 
let currentPriceRange = { min: null, max: null };
let currentSearchQuery = ''; 
let isLoading = false;
let isLastPage = false;

const handleInfiniteScroll = () => {
    if (isLoading || isLastPage) return; 
    const scrollPosition = window.scrollY + window.innerHeight;
    const documentHeight = document.documentElement.scrollHeight;

    if (scrollPosition >= documentHeight - 200) { 
        currentPage += 1;
        loadProductsWithFilters(currentPage, currentCategory, currentPriceRange, currentSearchQuery);
    }
};

window.addEventListener('scroll', handleInfiniteScroll);


const loadProductsWithFilters = (page = 1, categoryId = currentCategory, priceRange = currentPriceRange, searchQuery = currentSearchQuery) => {
    if (isLoading) return;
    isLoading = true;

    const url = '/get-products';

    const data = {
        page,
        category_id: categoryId,
        price_range: priceRange,
        search: searchQuery
    };

    const loadMoreGif = document.getElementById('loader');

    if (loadMoreGif) {
        loadMoreGif.style.display = 'block';
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(url, {
        method: 'POST', 
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(data)
    })
    .then(response => response.ok ? response.json() : Promise.reject('Failed to fetch products'))
    .then(data => {
        const productContainer = document.getElementById('product-list');

        if (data && data.products && data.products.data.length && productContainer) {
            if (page === 1) productContainer.innerHTML = '';

            data.products.data.forEach(product => {
                const categoryList = product.categories.map(category => {
                    return `<span class="badge bg-primary">${category.name}</span>`;
                }).join(' ');

                const productCard = `
                    <div class="col-md-3 col-sm-6 py-2">
                        <div class="product-grid border border-1  shadow-sm">
                            <div class="product-image">
                                <a href="#" class="image">
                                    <img class="pic-1" src="/storage/${product.image_url}" alt="${product.name}" loading="lazy">
                                    <img class="pic-2" src="/storage/${product.image_url}" alt="${product.name}" loading="lazy">
                                </a>
                                ${
                                  product.stock_quantity <= 0
                                      ? `<div class="position-absolute top-50 start-50 translate-middle badge text-bg-danger text-wrap py-1 px-3 rounded">
                                          <small><strong>Out of Stock</strong></small>
                                      </div>`
                                      : ''
                                }
                                <a href="#" class="product-like-icon" data-tip="Add to Wishlist">
                                    <i class="bi bi-heart"></i>
                                </a>
                                <ul class="product-links">
                                    <li><a href="#"><i class="bi bi-search"></i></a></li>
                                    <li><a href="/cart/add/${product.id}"><i class="bi bi-cart-plus"></i></a></li>
                                    <li><a href="#"><i class="bi bi-shuffle"></i></a></li>
                                </ul>
                            </div>
                            <div class="product-content d-flex flex-column align-items-start p-3">
                                <h3 class="title mb-2"><a href="#">${product.name}</a></h3>
                                <div class="category mb-2">
                                    ${categoryList}
                                </div>
                                <div class="price text-muted">$${parseFloat(product.price).toFixed(2)}</div>
                                <p class="description text-muted mb-2">${product.description}</p>
                            </div>
                        </div>
                    </div>
                `;
                productContainer.innerHTML += productCard;
            });

            currentPage = page;
            isLastPage = data.products.current_page >= data.products.last_page; 

            const loadMoreButton = document.getElementById('load-more');
            loadMoreButton.style.display = isLastPage ? 'none' : 'block';  
        } else {
            if (page === 1) {
                if (productContainer) {
                    productContainer.innerHTML = '<p>No products found.</p>';
                }
                
            }
        }
    })
    .catch(error => {
        console.error('Error loading products:', error);
        isLoading = false;
    })
    .finally(() => {
        isLoading = false;
        if (loadMoreGif) {
            loadMoreGif.style.display = 'none'; 
        }
        
    });
};


const clearAllFilters = () => {
    document.getElementById('category-filter').value = '';
    document.getElementById('min-price').value = '';
    document.getElementById('max-price').value = '';
    document.getElementById('search-box').value = '';
    currentCategory = null;
    currentPriceRange = { min: null, max: null };
    currentSearchQuery = ''; 
    currentPage = 1;
    loadProductsWithFilters(currentPage, currentCategory, currentPriceRange, currentSearchQuery);
};


const clearFiltersButton = document.getElementById('clear-filters');
if (clearFiltersButton) {
  clearFiltersButton.addEventListener('click', clearAllFilters);
}

const searchBox = document.getElementById('search-box');
if (searchBox) {
  searchBox.addEventListener('input', event => {
    currentSearchQuery = event.target.value;
    currentPage = 1;
    loadProductsWithFilters(currentPage, currentCategory, currentPriceRange, currentSearchQuery);
  });
}

const loadMoreButton = document.getElementById('load-more');
if (loadMoreButton) {
  loadMoreButton.addEventListener('click', () => {
    currentPage += 1;
    loadProductsWithFilters(currentPage, currentCategory, currentPriceRange, currentSearchQuery);
  });
}

const categoryFilter = document.getElementById('category-filter');
if (categoryFilter) {
  categoryFilter.addEventListener('change', event => {
    currentCategory = event.target.value;
    currentPage = 1;
    loadProductsWithFilters(currentPage, currentCategory, currentPriceRange, currentSearchQuery);
  });
}

const minPriceInput = document.getElementById('min-price');
if (minPriceInput) {
  minPriceInput.addEventListener('input', event => {
    currentPriceRange.min = event.target.value ? parseFloat(event.target.value) : null;
    currentPage = 1;
    loadProductsWithFilters(currentPage, currentCategory, currentPriceRange, currentSearchQuery);
  });
}

const maxPriceInput = document.getElementById('max-price');
if (maxPriceInput) {
  maxPriceInput.addEventListener('input', event => {
    currentPriceRange.max = event.target.value ? parseFloat(event.target.value) : null;
    currentPage = 1;
    loadProductsWithFilters(currentPage, currentCategory, currentPriceRange, currentSearchQuery);
  });
}

const applyFiltersButton = document.getElementById('apply-filters');
if (applyFiltersButton) {
  applyFiltersButton.addEventListener('click', () => {
    currentCategory = document.getElementById('category-filter').value || null;
    currentPriceRange = {
      min: document.getElementById('min-price').value ? parseFloat(document.getElementById('min-price').value) : null,
      max: document.getElementById('max-price').value ? parseFloat(document.getElementById('max-price').value) : null
    };
    currentPage = 1;
    loadProductsWithFilters(currentPage, currentCategory, currentPriceRange, currentSearchQuery);
  });
}


loadProductsWithFilters(currentPage);

export default loadProductsWithFilters;
