<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();

Route::group(['prefix' => 'supplier'], function () {
    Route::get('/get-warehouses', 'SupplierController@get_warehouses')->name('supplier.get-warehouses');
    Route::get('/', 'SupplierController@index')->name('supplier.index');
    Route::post('store', 'SupplierController@store')->name('supplier.store');
    Route::get('create', 'SupplierController@create')->name('supplier.create');
    Route::get('edit/{id}', 'SupplierController@edit')
        ->name('supplier.edit')
        ->where(['id' => '[0-9]+']);
    Route::get('delete', 'SupplierController@destroy')->name('supplier.delete');
});

Route::group(['prefix' => 'warehouse'], function () {
    Route::get('/', 'WarehouseController@index')->name('warehouse.index');
    Route::get('import-places-delivery', 'WarehouseController@import_places_delivery')->name('warehouse.import');
    Route::post('import-places-delivery', 'WarehouseController@import_places_delivery_store');
    Route::get('/get-option', 'WarehouseController@getOption')->name('warehouse.get-option');
    Route::post('store', 'WarehouseController@store')->name('warehouse.store');
    Route::get('create', 'WarehouseController@create')->name('warehouse.create');
    Route::get('edit/{id}', 'WarehouseController@edit')
        ->name('warehouse.edit')
        ->where(['id' => '[0-9]+']);
    Route::get('delete', 'WarehouseController@destroy')->name('warehouse.delete');
});

Route::group(['prefix' => 'surcharge'], function () {
    Route::get('/', 'SurchargeController@index')->name('surcharge.index');
    // Route::get('/get-option', 'SurchargeController@getOption')->name('surcharge.get-option');
    Route::post('store', 'SurchargeController@store')->name('surcharge.store');
    Route::get('create', 'SurchargeController@create')->name('surcharge.create');
    Route::get('edit/{id}', 'SurchargeController@edit')
        ->name('surcharge.edit')
        ->where(['id' => '[0-9]+']);
    Route::get('delete', 'SurchargeController@destroy')->name('surcharge.delete');
});


Route::group(['prefix' => 'report'], function () {
    Route::get('/', 'ReportController@product')->name('report.product');
    Route::get('order', 'ReportController@order')->name('report.order');
    Route::get('category', 'ReportController@category')->name('report.category');
    Route::get('coupon', 'ReportController@coupon')->name('report.coupon');
    Route::get('export', 'ReportController@export')->name('report.export');

    Route::get('download', 'ReportController@download')->name('report.download');
    
});

Route::group(['prefix' => 'sale-hot'], function () {
    Route::get('/', 'SaleHotController@index')->name('sale-hot.index');
    Route::post('add', 'SaleHotController@store')->name('sale-hot.add');
    Route::post('update-status', 'SaleHotController@update_status')->name('sale-hot.update-status');
    Route::post('delete', 'SaleHotController@destroy')->name('sale-hot.delete');
});

Route::group(['prefix' => 'coupons'], function () {
    Route::get('/', 'CouponsController@index')->name('coupons.index');
    Route::post('add', 'CouponsController@store')->name('coupons.add');
    Route::get('check-coupon', 'CouponsController@check_coupon')->name('coupons.check-coupon');
    Route::post('get-product-code', 'CouponsController@getProductCode')->name('coupons.get-product-code');
    Route::get('download', 'CouponsController@Download')->name('coupons.download');
     Route::post('get-brand', 'CouponsController@getBrand')->name('coupons.get-brand');

    Route::post('update-status', 'CouponsController@update_status')->name('coupons.update-status');
});

Route::get('/', 'IndexController@dashboard');
Route::get('/dashboard', 'IndexController@dashboard')->name('dashboard');
Route::get('/total-revenue', 'IndexController@total_revenue');

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['admin']
], function() {
    // your CRUD resources and other admin routes here
    CRUD::resource('faq','FaqCrudController');
});

Route::group(['prefix' => 'convert'], function () {
    Route::get('/product-files', 'ConvertController@product_files');
    Route::get('/update-sku', 'ConvertController@update_sku');
});
Route::group(['prefix' => 'tools'], function () {
    Route::get('/', 'ToolsController@index');
    Route::get('/sync-filter-ranges', 'ToolsController@sync_product_filter_ranges');
});

Route::group(['prefix' => 'chain-store'], function () {
    Route::get('/', 'ChainStoreController@index')->name('chain-store.index');
    Route::post('chain-store-setting-update', 'ChainStoreController@chainStoreSettingUpdate')->name('chain-store.setting-update');
    Route::post('setting-update-banner-description', 'ChainStoreController@settingUpdateBannerDescription')->name('chain-store.setting-update-banner-description');
    Route::post('add', 'ChainStoreController@add')->name('chain-store.add');
    Route::post('delete', 'ChainStoreController@delete')->name('chain-store.delete');
});

Route::group(['prefix' => 'introduction'], function () {
    Route::get('/', 'IntroductionController@index')->name('introduction.index');
    Route::post('update-image', 'IntroductionController@update_image')->name('introduction.update-image');
    Route::post('update-content', 'IntroductionController@update_content')->name('introduction.update-content');
});

Route::group(['prefix' => 'sale-b2b'], function () {
    Route::get('/', 'SaleB2BController@index')->name('sale-b2b.index');
    Route::post('update-logo', 'SaleB2BController@update_logo')->name('sale-b2b.update-logo');
});

Route::group(['prefix' => 'amortization'], function () {
    Route::get('/', 'AmortizationController@index');
    Route::post('update-bank-partner', 'AmortizationController@update_bank_partner')->name('amortization.update-bank-partner');
});

Route::group(['prefix' => 'shopping-guide'], function () {
    Route::get('/', 'ShoppingGuideController@index');
});

Route::group(['prefix' => 'delivery'], function () {
    Route::get('/', 'DeliveryController@index');
});

Route::group(['prefix' => 'warranty'], function () {
    Route::get('/', 'WarrantyController@index');
});

Route::group(['prefix' => 'service-center'], function () {
    Route::get('/', 'ServiceCenterController@index')->name('service-center.index');
    Route::post('add', 'ServiceCenterController@add')->name('service-center.add');
    Route::post('delete', 'ServiceCenterController@delete')->name('service-center.delete');
});

Route::group(['prefix' => 'page-business-line'], function () {
    Route::post('delete', 'PageBusinessLineController@delete')->name('page-business-line.delete');
});

Route::group(['prefix' => 'location'], function () {
    Route::get('get-provinces', 'LocationController@get_provinces');
    Route::get('get-districts', 'LocationController@get_districts');
    Route::get('get-wards', 'LocationController@get_wards');
});

Route::group(['prefix' => 'setting'], function () {
    Route::get('/', 'SettingController@index')->name('setting.index');
    Route::post('add', 'SettingController@store')->name('setting.add');
});

Route::group(['prefix' => 'menu-item'], function () {
    Route::get('/get-menus-parent', 'MenuItemController@get_menus_parent')->name('menu-item.get-menus-parent');
    Route::get('/', 'MenuItemController@index')->name('menu-item.index');
    Route::post('/', 'MenuItemController@store')->name('menu-item.store');
    Route::post('/destroy', 'MenuItemController@destroy')->name('menu-item.delete');
});

Route::group(['prefix' => 'supplier-email'], function () {
    Route::get('/', 'SupplierEmailController@index')->name('supplier-email.index');
    Route::get('create', 'SupplierEmailController@create')->name('supplier-email.add');
    Route::post('store', 'SupplierEmailController@store')->name('supplier-email.store');
    Route::post('/destroy', 'SupplierEmailController@destroy')->name('supplier-email.delete');
});

Route::post('upload', 'UploaderController@upload')->name('upload');

Route::group(['prefix' => 'news-categories'], function () {
    Route::get('/', 'NewsCategoriesController@index')->name('news-categories.index');
    Route::post('add', 'NewsCategoriesController@store')->name('news-categories.add');
    Route::post('delete', 'NewsCategoriesController@destroy')->name('news-categories.delete');
});

Route::group(['prefix' => 'news'], function () {
    Route::get('/', 'NewController@index')->name('news.index');
    Route::post('add', 'NewController@store')->name('news.add');
    Route::post('delete', 'NewController@destroy')->name('news.delete');
});

Route::group(['prefix' => 'advice-categories'], function () {
    Route::get('/', 'AdviceCategoriesController@index')->name('advice-categories.index');
    Route::post('add', 'AdviceCategoriesController@store')->name('advice-categories.add');
    Route::post('delete', 'AdviceCategoriesController@destroy')->name('advice-categories.delete');
});

Route::group(['prefix' => 'advices'], function () {
    Route::get('/', 'AdviceController@index')->name('advice.index');
    Route::post('add', 'AdviceController@store')->name('advice.add');
    Route::post('delete', 'AdviceController@destroy')->name('advice.delete');
});

Route::group(['prefix' => 'job-category'], function () {
    Route::get('/', 'JobCategoryController@index')->name('job-categories.index');
    Route::post('add', 'JobCategoryController@store')->name('job-categories.add');
    Route::post('delete', 'JobCategoryController@destroy')->name('job-categories.delete');
});

Route::group(['prefix' => 'job-opening'], function () {
    Route::get('/', 'JobOpeningController@index')->name('job-opening.index');
    Route::get('/apply-cv', 'ApplyCVController@index')->name('job-opening.apply-cv');
    Route::post('add', 'JobOpeningController@store')->name('job-opening.add');
    Route::post('delete', 'JobOpeningController@destroy')->name('job-opening.delete');
    Route::post('change-status', 'JobOpeningController@change_status')->name('job-opening.change-status');
});

Route::group(['prefix' => 'banner'], function () {
    Route::get('/about', 'BannerController@about')->name('banners.about');
    Route::get('/{type}', 'BannerController@index')->name('banners.type');
    Route::get('/', 'BannerController@index')->name('banners.index');
    Route::post('add', 'BannerController@store')->name('banners.add');
    Route::post('delete', 'BannerController@destroy')->name('banners.delete');
});

Route::group(['prefix' => 'promotion'], function () {
    Route::post('/get-list-products-by-ids', 'PromotionController@get_list_products_by_ids')->name('promotions.get_list_products_by_ids');
    Route::get('/get-list-products', 'PromotionController@get_list_products')->name('promotions.get_list_products');
    Route::post('/get-list-brands', 'PromotionController@get_list_brands')->name('promotions.get_list_brands');
    Route::get('/order', 'PromotionController@order')->name('promotions.order');
    Route::get('/', 'PromotionController@index')->name('promotions.index');
    Route::post('add', 'PromotionController@store')->name('promotions.add');
    Route::post('delete', 'PromotionController@destroy')->name('promotions.delete');
    Route::post('stop', 'PromotionController@stop')->name('promotions.stop');
    Route::post('start', 'PromotionController@start')->name('promotions.start');
});

Route::group(['prefix' => 'microsite'], function () {
    Route::get('/', 'MicrositeController@index')->name('microsites.index');
    Route::get('/pre-order', 'MicrositeController@pre_order')->name('microsites.pre-order');
    Route::get('/normal', 'MicrositeController@normal')->name('microsites.normal');
    Route::get('/gold-time', 'MicrositeController@gold_time')->name('microsites.gold-time');
    Route::get('/exchange-points', 'MicrositeController@exchange_points')->name('microsites.exchange-points');
    Route::post('add', 'MicrositeController@store')->name('microsites.add');
    Route::post('add-pre-order', 'MicrositeController@store_pre_order')->name('microsites.add-pre-order');
    Route::post('add-normal', 'MicrositeController@store_normal')->name('microsites.add-normal');
    Route::post('add-gold-time', 'MicrositeController@store_gold_time')->name('microsites.add-gold-time');
    Route::post('add-exchange-points', 'MicrositeController@store_exchange_points')->name('microsites.add-exchange-points');
    Route::post('delete', 'MicrositeController@destroy')->name('microsites.delete');

    Route::post('sale-off/update-status', 'MicrositeController@update_status_sale_off')->name('microsites.update_status_sale_off');
    Route::post('exchange-points/update-status', 'MicrositeController@update_status_exchange_points')->name('microsites.update_status_exchange_points');
    Route::post('gold-time/update-status', 'MicrositeController@update_status_gold_time')->name('microsites.update_status_gold_time');
    Route::post('normal/update-status', 'MicrositeController@update_status_normal')->name('microsites.update_status_normal');
    Route::post('pre-order/update-status', 'MicrositeController@update_status_pre_order')->name('microsites.update_status_pre_order');
});

Route::group(['prefix' => 'admin-users'], function () {
    Route::get('/', 'AdminUsersController@index')->name('admin-users.index');
    Route::post('get-combogrid-data', 'AdminUsersController@getCombogridData')->name('admin-users.get-combogrid-data');
    Route::post('add', 'AdminUsersController@store')->name('admin-users.add');
    Route::post('delete', 'AdminUsersController@destroy')->name('admin-users.delete');
    Route::post('change-status', 'AdminUsersController@change_status')->name('admin-users.change-status');
    Route::post('get-role', 'AdminUsersController@getRole')->name('admin-users.get-role');
});

Route::group(['prefix' => 'users'], function () {    
    Route::get('/', 'UsersController@index')->name('users.index');
    Route::post('add', 'UsersController@store')->name('users.add');
    Route::post('delete', 'UsersController@destroy')->name('users.delete');
    Route::post('change-status', 'UsersController@change_status')->name('users.change-status');
});

Route::group(['prefix' => 'home'], function () {
    Route::get('/', 'HomeController@index')->name('home.index');
    Route::get('/block-product/{sort}', 'HomeController@block_product')->name('home.block-product');
    Route::get('/shock-more', 'HomeController@shock_more')->name('home.shock-more');
    Route::get('/brand-hot', 'HomeController@brand_hot')->name('home.brand-hot');
    Route::get('/only-at-dmth', 'HomeController@only_at_dmth')->name('home.only-at-dmth');
    Route::get('/brand-popular', 'HomeController@brand_popular')->name('home.brand-popular');
    Route::post('add', 'HomeController@store')->name('home.add');
    Route::post('delete', 'HomeController@destroy')->name('home.delete');
});

Route::group(['prefix' => 'product-comments'], function () {    
    Route::get('/', 'ProductCommentsController@index')->name('product-comments.index');
    Route::post('change-status', 'ProductCommentsController@change_status')->name('product-comments.change-status');
    Route::post('delete', 'ProductCommentsController@destroy')->name('product-comments.delete');
});

Route::group(['prefix' => 'support-requests'], function () {    
    Route::get('/', 'SupportRequestsController@index')->name('support-requests.index');
    Route::post('add', 'SupportRequestsController@store')->name('support-requests.add');
    Route::post('delete', 'SupportRequestsController@destroy')->name('support-requests.delete');
    Route::post('change-status', 'SupportRequestsController@change_status')->name('support-requests.change-status');
});

Route::group(['prefix' => 'cache'], function () {    
    Route::get('/update-js', 'CacheController@update_js')->name('cache.update-js');
    Route::get('/update-css', 'CacheController@update_css')->name('cache.update-css');
    Route::get('/update-all', 'CacheController@update_all')->name('cache.update-all');
});

Route::group(['prefix' => 'profile'], function () {    
    Route::get('info', 'ProfileController@index')->name('profile.info');
    Route::get('change-password', 'ProfileController@change_password')->name('profile.change-password');
    Route::post('ajax-change-password', 'ProfileController@ajax_change_password')->name('profile.ajax-change-password');
});

Route::group(['prefix' => 'categories'], function () {
    Route::get('/', 'CategoriesController@index')->name('categories.index');
    Route::post('delete', 'CategoriesController@destroy')->name('categories.delete');
    Route::post('store', 'CategoriesController@store')->name('categories.store');
    Route::get('create', 'CategoriesController@create')->name('categories.create');
    Route::get('edit/{id}', 'CategoriesController@edit')
        ->name('categories.edit')
        ->where(['id' => '[0-9]+']);
});

Route::group(['prefix' => 'order-status'], function () {    
    Route::get('/', 'OrderStatusController@index')->name('order-status.index');
    Route::post('check-id', 'OrderStatusController@check_id')->name('order-status.check-id');
    Route::post('add', 'OrderStatusController@store')->name('order-status.add');
    Route::post('delete', 'OrderStatusController@destroy')->name('order-status.delete');
});

Route::group(['prefix' => 'faqs'], function () {    
    Route::get('/', 'FaqsController@index')->name('faqs.index');
    Route::post('add', 'FaqsController@store')->name('faqs.add');
    Route::post('delete', 'FaqsController@destroy')->name('faqs.delete');   
});

Route::group(['prefix' => 'pages'], function () {    
    Route::get('/', 'PagesController@index')->name('pages.index');
    Route::post('add', 'PagesController@store')->name('pages.add');
    Route::post('delete', 'PagesController@destroy')->name('pages.delete');   
});

Route::group(['prefix' => 'static-pages'], function () {
    Route::get('/', 'StaticPagesController@index')->name('static-pages.index');
    Route::post('update-image', 'StaticPagesController@update_image')->name('static-pages.update-image');
    Route::post('update-content', 'StaticPagesController@update_content')->name('static-pages.update-content');
});

Route::group(['prefix' => 'contact'], function () {    
    Route::get('/', 'ContactController@index')->name('contact.index');
    Route::post('add', 'ContactController@store')->name('contact.add');
    Route::post('delete', 'ContactController@destroy')->name('contact.delete');   
});

Route::group(['prefix' => 'brands'], function () {
    Route::get('/', 'BrandController@index')->name('brands.index');
    Route::get('/get-options', 'BrandController@get_options')->name('brands.options');
    Route::post('store', 'BrandController@store')->name('brands.store');
    Route::get('create', 'BrandController@create')->name('brands.create');
    Route::get('edit/{id}', 'BrandController@edit')
        ->name('brands.edit')
        ->where(['id' => '[0-9]+']);
});

Route::group(['prefix' => 'units'], function () {
    Route::get('/', 'UnitsController@index')->name('units.index');
    Route::get('/get-options', 'UnitsController@get_options')->name('units.options');
    Route::post('store', 'UnitsController@store')->name('units.store');
    Route::get('create', 'UnitsController@create')->name('units.create');
    Route::get('edit/{id}', 'UnitsController@edit')
        ->name('units.edit')
        ->where(['id' => '[0-9]+']);
    Route::get('delete', 'UnitsController@destroy')->name('units.delete');
});

Route::group(['prefix' => 'products'], function () {
    Route::get('/', 'ProductController@index')->name('products.index');
    Route::post('store', 'ProductController@store')->name('products.store');
    Route::post('change-status', 'ProductController@change_status')->name('products.change-status');
    Route::post('update-count', 'ProductController@update_count')->name('products.update-count');
    Route::post('change-has-gift', 'ProductController@change_has_gift')->name('products.change-has-gift');
    Route::post('change-value-field', 'ProductController@change_value_field')->name('products.change-value-field');
    Route::post('update-seo', 'ProductController@update_seo')->name('products.update_seo');
    Route::get('create', 'ProductController@create')->name('products.create');
    Route::get('edit/{id}', 'ProductController@edit')
        ->name('products.edit')
        ->where(['id' => '[0-9]+']);

    Route::post('search-product', 'ProductController@search_product')->name('products.search-product');
    Route::post('store-products-sold', 'ProductController@store_products_sold')->name('products.store-products-sold');
    Route::post('get-list-products-sold', 'ProductController@get_list_products_sold')->name('products.get-list-products-sold');
    Route::post('delete-products-sold', 'ProductController@destroy_products_sold')->name('products.delete-products-sold');

});

Route::group(['prefix' => 'product-service'], function () {
    Route::get('/', 'ProductServiceController@index')->name('product-service.index');
    Route::post('store', 'ProductServiceController@store')->name('product-service.store');
    Route::get('create', 'ProductServiceController@create')->name('product-service.create');
    Route::get('edit/{id}', 'ProductServiceController@edit')
        ->name('product-service.edit')
        ->where(['id' => '[0-9]+']);
});

Route::group(['prefix' => 'filters'], function () {
    Route::get('/', 'FilterController@index')->name('filters.index');
    Route::get('/create', 'FilterController@create')->name('filters.create');
    Route::get('/create-filter', 'FilterController@create_filter')->name('filters.create-filter');
    Route::post('store', 'FilterController@store')->name('filters.store');
    Route::get('edit/{id}', 'FilterController@edit')
        ->name('filters.edit')
        ->where(['id' => '[0-9]+']);
    Route::post('delete', 'FilterController@destroy')->name('filters.delete');
    Route::post('delete-filter', 'FilterController@destroy_filter')->name('filters.delete-filter');
    Route::get('{id}/list', 'FilterController@list_filter')
        ->name('filters.list')
        ->where(['id' => '[0-9]+']);

    Route::get('edit-filter/{id}', 'FilterController@edit_filter')
        ->name('filters.edit-filter')
        ->where(['id' => '[0-9]+']);
    Route::post('store_filter', 'FilterController@store_filter')->name('filters.store-filter');

    Route::get('/get-feature-options', 'FilterController@get_feature_options')->name('features.get-feature-options');
    Route::get('/get-feature-variants', 'FilterController@get_feature_variants')->name('features.get-feature-variants');
});

Route::group(['prefix' => 'imports'], function () {
    Route::get('/', 'ImportController@index')->name('imports.index');
    Route::post('/products', 'ImportController@products')->name('imports.products');
    Route::post('/products-count', 'ImportController@products_count')->name('imports.products-count');
    Route::post('/products-price', 'ImportController@products_price')->name('imports.products-price');
});

Route::group(['prefix' => 'exports'], function () {
    Route::get('/', 'ExportController@index')->name('exports.index');
});

Route::group(['prefix' => 'order'], function () {
    Route::get('/', 'OrderController@index')->name('order.index');
    Route::post('change-status', 'OrderController@change_status')->name('order.change-status');
    Route::get('show/{id}', 'OrderController@show')
        ->name('order.show')
        ->where(['id' => '[0-9]+']);

    Route::get('print/{id}', 'OrderController@print_order')
        ->name('order.print')
        ->where(['id' => '[0-9]+']);

    Route::get('edit/{id}', 'OrderController@edit')
        ->name('order.edit')
        ->where(['id' => '[0-9]+']);
    Route::post('search-product', 'OrderController@search_product')->name('order.search-product');    
    Route::post('update-address', 'OrderController@update_address')->name('order.update-address');
    Route::post('update-note', 'OrderController@update_note')->name('order.update-note');
    Route::post('add-product', 'OrderController@add_product')->name('order.add-product'); 
    Route::post('remove-product', 'OrderController@remove_product')->name('order.remove-product');
    Route::post('store', 'OrderController@store')->name('order.store');
    Route::post('change-lock-status', 'OrderController@change_lock_status')->name('order.change-lock-status');
    Route::get('create', 'OrderController@create')->name('order.create');

    Route::get('get-service','OrderController@getService')->name('order.get-service');
    Route::get('get-warehouse', 'OrderController@get_warehouse')->name('cart.get-warehouse');
});

Route::group(['prefix' => 'permissions', 'middleware' => ['check.permissions']], function () {
    Route::get('show-all', 'PermissionsController@getShowAll')->name('permissions.index');
    Route::get('ajax-data', 'PermissionsController@getAjaxData');
    Route::get('add', 'PermissionsController@getAdd');
    Route::post('add', 'PermissionsController@postAdd');
    Route::get('edit/{id}', 'PermissionsController@getEdit');
    Route::post('edit/{id}', 'PermissionsController@postEdit');
});

Route::group(['prefix' => 'roles', 'middleware' => ['check.permissions']], function () {
    Route::post('add-users', 'RolesController@add_users')->name('roles.add-users');
    Route::delete('/{role_id}/remove-user/{user_id}', 'RolesController@remove_user')->name('roles.remove-user');
    Route::get('/', 'RolesController@getShowAll')->name('roles.index');
    Route::get('ajax-data', 'RolesController@getAjaxData');
    Route::get('add', 'RolesController@getAdd');
    Route::get('detail/{id}', 'RolesController@detail');
    Route::post('add', 'RolesController@postAdd');
    Route::get('edit/{id}', 'RolesController@getEdit');
    Route::post('edit/{id}', 'RolesController@postEdit');
    Route::delete('delete/{id}', 'RolesController@destroy')->name('roles.delete');
});

Route::namespace('Fujiyama')->group(function () {

    Route::group(['prefix' => 'fujiyama'], function () {
        Route::get('/', 'BannerController@index')->name('fujiyama.banners.index');

        Route::group(['prefix' => 'home'], function () {
            Route::get('/', 'HomeController@block_product')->name('fujiyama.home.index');
            Route::get('/block-product/{sort}', 'HomeController@block_product')->name('fujiyama.home.block-product');
            Route::post('add', 'HomeController@store')->name('fujiyama.home.add');
            Route::post('delete', 'HomeController@destroy')->name('fujiyama.home.delete');
        });

        Route::group(['prefix' => 'banner'], function () {
            Route::get('/{type}', 'BannerController@index')->name('fujiyama.banners.type');
            Route::get('/', 'BannerController@index')->name('fujiyama.banners.index');
            Route::post('add', 'BannerController@store')->name('fujiyama.banners.add');
            Route::post('delete', 'BannerController@destroy')->name('fujiyama.banners.delete');
        });

        Route::group(['prefix' => 'introduction'], function () {
            Route::get('/', 'IntroductionController@index')->name('fujiyama.introduction');
        });

        Route::group(['prefix' => 'chain-store'], function () {
            Route::get('/', 'ChainStoreController@index')->name('fujiyama.chain-store');
        });

        Route::group(['prefix' => 'sale-b2b'], function () {
            Route::get('/', 'SaleB2BController@index')->name('fujiyama.sale-b2b');
        });

        Route::group(['prefix' => 'contact'], function () {
            Route::get('/', 'ContactController@index')->name('fujiyama.contact');
            Route::post('add', 'ContactController@store')->name('fujiyama.contact.add');
            Route::post('delete', 'ContactController@destroy')->name('fujiyama.contact.delete');
        });
        Route::group(['prefix' => 'shopping-guide'], function () {
            Route::get('/', 'ShoppingGuideController@index')->name('fujiyama.shopping-guide');
        });
        Route::group(['prefix' => 'warranty'], function () {
            Route::get('/', 'WarrantyController@index')->name('fujiyama.warranty');
        });
        Route::group(['prefix' => 'service-center'], function () {
            Route::get('/', 'ServiceCenterController@index')->name('fujiyama.service-center');
            Route::post('add', 'ServiceCenterController@add')->name('fujiyama.service-center.add');
            Route::post('delete', 'ServiceCenterController@delete')->name('fujiyama.service-center.delete');
        });        
    });
});