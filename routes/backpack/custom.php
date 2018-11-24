<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes

    CRUD::resource('services-extra', 'ServicesExtraCrudController');
    CRUD::resource('service', 'ServiceCrudController');
    CRUD::resource('freezer', 'FreezerCrudController');
    CRUD::resource('freezer-capacity', 'FreezerCapacityCrudController');
    CRUD::resource('services-units', 'ServicesUnitsCrudController');
    CRUD::resource('services-freezer-units', 'ServicesFreezerUnitsCrudController');
    CRUD::resource('booking', 'BookingCrudController');
    CRUD::resource('contact', 'ContactCrudController');
    CRUD::resource('customer', 'CustomerCrudController');
}); // this should be the absolute last line of this file
