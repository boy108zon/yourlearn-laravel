<?php
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\MenusController;
use App\Http\Controllers\LogActivityController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\PromoCodesController;
use App\Http\Controllers\RatingController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Auth::routes();

// Home Route
Route::get('/', [SiteController::class, 'index'])->name('site');
Route::post('/get-products', [SiteController::class, 'getProducts']);
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('{slug}/{id}/buy', [SiteController::class, 'show'])->name('site.show');
Route::get('/cart', [CartController::class, 'getCart'])->name('cart.index');
Route::get('/cart/add/{productId}', [CartController::class, 'addProductToCart'])->name('cart.add');
Route::post('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');
Route::put('/cart/update/{productId}', [CartController::class, 'updateCart'])->name('cart.update');
Route::get('/checkout/index', [CartController::class, 'clearCart'])->name('checkout.index');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'processCheckout'])->name('checkout.process');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::delete('/cart/remove/{cartId}/{productId}', [CartController::class, 'removeProductFromCart'])->name('cart.remove');
Route::post('checkout/applyPromoCode', [CheckoutController::class, 'applyPromoCode'])->name('checkout.applyPromoCode');
Route::post('/products/{id}/rate', [RatingController::class, 'store'])->name('products.rate');

/*Route::middleware(['auth'])->group(function () {
    Route::post('/ratings', [RatingController::class, 'store'])->name('ratings.store');
});

Route::get('/products/{product}/ratings', [RatingController::class, 'getRatings'])->name('ratings.get');
*/

// User Routes with Permissions
Route::prefix('users')->name('users.')->middleware('permission:list-users')->group(function() {
    Route::get('/', [UsersController::class, 'index'])->name('index');
    Route::get('create', [UsersController::class, 'create'])->name('create')->middleware('permission:create-user');
    Route::put('store', [UsersController::class, 'store'])->name('store')->middleware('permission:create-user');
    Route::get('{user}/edit', [UsersController::class, 'edit'])->name('edit')->middleware('permission:edit-user');
    Route::put('{user}', [UsersController::class, 'update'])->name('update')->middleware('permission:edit-user');
    Route::delete('{user}', [UsersController::class, 'destroy'])->name('destroy')->middleware('permission:remove-user');
    Route::get('{user}/reset-password', [UsersController::class, 'resetPassword'])->name('resetPassword')->middleware('permission:reset-password-for-users');
    Route::put('{user}/reset-password', [UsersController::class, 'resetPasswordUpdate'])->name('reset-password')->middleware('permission:reset-password-for-users');
});

// Role Routes with Permissions
Route::prefix('roles')->name('roles.')->middleware('permission:list-roles')->group(function() {
    Route::get('/', [RolesController::class, 'index'])->name('index');
    Route::get('create', [RolesController::class, 'create'])->name('create')->middleware('permission:create-role');
    Route::put('store', [RolesController::class, 'store'])->name('store')->middleware('permission:create-role');
    Route::get('{role}/edit', [RolesController::class, 'edit'])->name('edit')->middleware('permission:edit-role');
    Route::put('{role}', [RolesController::class, 'update'])->name('update')->middleware('permission:edit-role');
    Route::delete('{role}', [RolesController::class, 'destroy'])->name('destroy')->middleware('permission:remove-role');
    Route::get('{role}/permissions', [RolePermissionController::class, 'permissions'])->name('permissions')->middleware('permission:assign-permissions');

});

// Role Permission Routes
Route::prefix('roles/permissions')->name('permissions.')->middleware('permission:list-permission')->group(function() {
    Route::get('/', [RolePermissionController::class, 'index'])->name('index');
    Route::get('create', [RolePermissionController::class, 'create'])->name('create')->middleware('permission:create-permissions');
    Route::put('store', [RolePermissionController::class, 'store'])->name('store')->middleware('permission:create-permissions');
    Route::get('{role}/permissions', [RolePermissionController::class, 'permissions'])->name('permissions')->middleware('permission:assign-permissions');
    Route::put('{roleId}/update-menus', [RolePermissionController::class, 'updateMenus'])->name('updateMenus')->middleware('permission:assign-permissions');
    
    Route::get('{permission}', [RolePermissionController::class, 'edit'])->name('edit')->middleware('permission:edit-permission');
    Route::get('{permission}/edit', [RolePermissionController::class, 'edit'])->name('edit')->middleware('permission:edit-permission');
    
    Route::put('{permissionId}/changename', [RolePermissionController::class, 'changeName'])->name('changename')->middleware('permission:edit-permission');
    Route::delete('{permission}', [RolePermissionController::class, 'destroy'])->name('destroy')->middleware('permission:remove-permission');
    
});

Route::prefix('menus')->name('menus.')->middleware('permission:list-menu')->group(function () {
    Route::get('/', [MenusController::class, 'index'])->name('index');
    Route::get('create', [MenusController::class, 'create'])->name('create')->middleware('permission:create-menu');
    Route::post('store', [MenusController::class, 'store'])->name('store')->middleware('permission:create-menu');
    Route::get('{menu}/edit', [MenusController::class, 'edit'])->name('edit')->middleware('permission:edit-menu');
    Route::put('{menu}', [MenusController::class, 'update'])->name('update')->middleware('permission:edit-menu');
    Route::delete('{menu}', [MenusController::class, 'destroy'])->name('destroy')->middleware('permission:remove-menu');
});


Route::prefix('categories')->name('categories.')->middleware('permission:categories')->group(function () {
    Route::get('/', [CategoriesController::class, 'index'])->name('index');
    Route::get('create', [CategoriesController::class, 'create'])->name('create')->middleware('permission:create-category');
    Route::post('store', [CategoriesController::class, 'store'])->name('store')->middleware('permission:create-category');
    Route::get('{category}/edit', [CategoriesController::class, 'edit'])->name('edit')->middleware('permission:edit-category');
    Route::post('{category}', [CategoriesController::class, 'update'])->name('update')->middleware('permission:edit-category');
    Route::put('{category}', [CategoriesController::class, 'update'])->name('update')->middleware('permission:edit-category');
    Route::delete('{category}', [CategoriesController::class, 'destroy'])->name('destroy')->middleware('permission:remove-category');
    
    Route::get('productsIndex', [CategoriesController::class, 'productsIndex'])->name('productsIndex');
    Route::prefix('{category}/products')->name('products.')->group(function () {
            
            Route::get('/', [CategoriesController::class, 'productsIndex'])->name('productsIndex'); 
            Route::get('create', [ProductsController::class, 'create'])->name('create'); 
            Route::post('store', [ProductsController::class, 'store'])->name('store'); 
    });
});

Route::prefix('products')->name('products.')->middleware('permission:list-products')->group(function () {
    Route::get('/', [ProductsController::class, 'index'])->name('index');
    Route::get('create', [ProductsController::class, 'create'])->name('create')->middleware('permission:create-product');
    Route::post('store', [ProductsController::class, 'store'])->name('store')->middleware('permission:create-product');
    Route::get('{product}/edit', [ProductsController::class, 'edit'])->name('edit')->middleware('permission:edit-product');
    Route::post('{product}', [ProductsController::class, 'update'])->name('update')->middleware('permission:edit-product');
    Route::put('{product}', [ProductsController::class, 'update'])->name('update')->middleware('permission:edit-product');
    Route::delete('{product}', [ProductsController::class, 'destroy'])->name('destroy')->middleware('permission:remove-product');

    Route::get('{product}/product-images', [ProductsController::class, 'editproductImages'])->name('editproductImages')->middleware('permission:edit-main-product-images');
    Route::post('{product}/store-product-images', [ProductsController::class, 'storeProductImages'])->name('storeProductImages')->middleware('permission:edit-main-product-images');
    Route::post('{product}/remove-product-image', [ProductsController::class, 'removeProductImage'])->name('removeProductImage')->middleware('permission:edit-main-product-images');
    Route::post('{product}/primary-image-status', [ProductsController::class, 'primaryImageStatus'])->name('primaryImageStatus')->middleware('permission:edit-main-product-images');

    Route::get('{product}/product-thumnails', [ProductsController::class, 'editproductThumnails'])->name('editproductThumnails')->middleware('permission:edit-thumbnails-product');
    Route::post('{product}/store-product-thumnails', [ProductsController::class, 'storeProductThumnails'])->name('storeProductThumnails')->middleware('permission:edit-thumbnails-product');
    Route::post('{product}/remove-product-thumnails', [ProductsController::class, 'removeProductThumnails'])->name('removeProductThumnails')->middleware('permission:edit-thumbnails-product');
    Route::post('{product}/primary-thumnails-status', [ProductsController::class, 'primaryThumnailsStatus'])->name('primaryThumnailsStatus')->middleware('permission:edit-thumbnails-product');

});

Route::prefix('orders')->name('orders.')->middleware('permission:list-orders')->group(function () {
    Route::get('/', [OrdersController::class, 'index'])->name('index');
    Route::get('create', [OrdersController::class, 'create'])->name('create')->middleware('permission:create-order');
    Route::get('{order}/show', [OrdersController::class, 'show'])->name('show')->middleware('permission:show-order');
    Route::post('store', [OrdersController::class, 'store'])->name('store')->middleware('permission:create-order');
    Route::get('{order}/edit', [OrdersController::class, 'edit'])->name('edit')->middleware('permission:edit-order');
    Route::post('{order}', [OrdersController::class, 'update'])->name('update')->middleware('permission:edit-order');
    Route::put('{order}', [OrdersController::class, 'update'])->name('update')->middleware('permission:edit-order');
    Route::delete('{order}', [OrdersController::class, 'destroy'])->name('destroy')->middleware('permission:remove-order');

});


Route::prefix('promocodes')->name('promocodes.')->middleware('permission:list-promo-codes')->group(function () {
    Route::get('/', [PromoCodesController::class, 'index'])->name('index');
    Route::get('create', [PromoCodesController::class, 'create'])->name('create')->middleware('permission:create-promo-code');
    Route::post('store', [PromoCodesController::class, 'store'])->name('store')->middleware('permission:create-promo-code');

    // Edit and update routes
    Route::get('{promoCode}/edit', [PromoCodesController::class, 'edit'])->name('edit')->middleware('permission:edit-promo-code');
    Route::put('{promoCode}/update', [PromoCodesController::class, 'update'])->name('update')->middleware('permission:edit-promo-code');

    Route::delete('{promoCode}', [PromoCodesController::class, 'destroy'])->name('destroy')->middleware('permission:remove-promo-code');
});

Route::prefix('log-activity')->name('log-activity.')->middleware('permission:log-activity')->group(function () {
    Route::get('/', [LogActivityController::class, 'index'])->name('index');
});


