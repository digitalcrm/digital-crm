<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
    |--------------------------------------------------------------------------
    | Authenticated API
    |--------------------------------------------------------------------------
    |
    |These api are for admin login/register and user login register 
    |
*/

Route::post('admin/login', 'Api\apiController@adminlogin');
Route::post('admin/register', 'Api\apiController@adminregister');

/*User Login/Register*/
Route::post('login', 'Api\apiController@userlogin');
Route::post('register', 'Api\apiController@userregister');
Route::post('logout', 'Api\apiController@logout');
/*User Forgot Password*/
Route::post('password/email', 'Api\ForgotPasswordController@sendResetLinkEmail');
Route::post('password/reset', 'Api\ResetPasswordController@reset');

//User profile
Route::get('profile/{profile}', 'Api\apiController@profile');

/*
    |--------------------------------------------------------------------------
    | Shop API
    |--------------------------------------------------------------------------
    |
*/

Route::get('get/products/all/{skip}/{take}', 'Api\CrudController@getProductsList');

Route::get('get/products/list', 'Api\CrudController@getProducts');

Route::get('get/products/latest/featured/{skip}/{take}', 'Api\CrudController@getProductsLatestFeatured');

Route::get('get/products/args', 'Api\AjaxController@ajaxGetProducts');

Route::get('get/products/category/list/{skip}/{take}', 'Api\CrudController@getProductCategoryList');

Route::get('get/products/subcategory/list/{id}/{skip}/{take}', 'Api\CrudController@getProductSubCategoryList');

// Route::get('get/product/details/{id}', 'Api\CrudController@getProductDetails');

Route::get('get/product/details/{slug}', 'Api\CrudController@getProductDetails');

Route::get('get/product/brands/list', 'Api\CrudController@getProductBrandslist');

Route::get('get/products/category/{slug}', 'Api\CrudController@getProductsByCategory');

Route::get('get/products/subcategory/{slug}', 'Api\CrudController@getProductsBySubCategory');

Route::get('get/user/currency/{uid}/{user_type}', 'Api\CrudController@getUserCurrency');

// Route::get('search/products/category/{slug}', 'Api\CrudController@getSearchProductCategory');

// Route::get('search/products/subcategory/{slug}', 'Api\CrudController@getSearchProductSubCategory');

Route::get('/search/product/{keyword}', 'Api\CrudController@searchProductKeyword');

Route::post('action/product/buynow/{slug}', 'Api\CrudController@StoreProductByNow');

Route::get('/search/product/vendor/{keyword}', 'Api\CrudController@searchProductByVendor');

Route::get('get/products/category/options/list', 'Api\CrudController@getProductCategoryOptionList');

Route::get('get/products/subcategory/options/list/{id}', 'Api\CrudController@getProductSubCategoryOptionList');

############################### Need changes ##################################################

Route::get('account/lists', 'Api\apiController@getaccountLists');

Route::get('lead/lists', 'Api\apiController@getleadLists');

Route::group(['middleware' => 'auth:api'], function () {

    Route::get('all/users/', 'Api\apiController@details');

    Route::post('update/user/{user}', 'Api\apiController@updateuserdetails');

    Route::get('account/details', 'Api\apiController@getaccountDetails');

    Route::get('lead/details', 'Api\apiController@getleadDetails');
    
    Route::apiResource('account', 'Api\apiAccountController');
    
    Route::apiResource('leads', 'Api\apiLeadController')->names([
        'index' => 'apilead.index',
        'show' => 'apilead.show',
        'store' => 'apilead.store',
        'update' => 'apilead.update',
        'destroy' => 'apilead.delete',
        ]);
        
    // Route::get('get/productleads/list', 'Api\apiLeadController@getProductLeads');

    // Route::get('get/productlead/details/{id}', 'Api\apiLeadController@getProductLeadDetails');

    // Route::apiResource('products', 'Api\ProductController')->names([
    //     'index' => 'prod.index',
    //     'show' => 'prod.show',
    //     'store' => 'prod.store',
    //     'update' => 'prod.update',
    //     'destroy' => 'prod.delete',
    // ]);

    // Route::get('get/companies/options/list', 'Api\CompanyApiController@getCompaniesOptionList');
});

/*
    |--------------------------------------------------------------------------
    | Version 0 & 1 APi's
    |--------------------------------------------------------------------------
    |
*/

Route::group(['namespace' => 'Api'], function () {
    
    /*
        |--------------------------------------------------------------------------
        | RFQ APi's
        |--------------------------------------------------------------------------
        |
    */
    Route::apiResource('rfq', 'RfqApiController')->only([
        'index', 'show', 'store'
    ]);
    
    Route::apiResource('rfq/leads', 'RfqLeadApiController')->names(['store' => 'rfqlead.store'])->only(['store']);    
});


Route::get('downloads/{company:slug}', function (App\Company $company) {
    return $company->downloadCatalog();
});

Route::group(['prefix' => 'v1', 'namespace' => 'Api\V1'], function () {

    /*
        |--------------------------------------------------------------------------
        | Company APi's
        |--------------------------------------------------------------------------
        |
    */
    Route::get('list-company', 'CompanyV1Controller@byCity');
    Route::apiResource('companies', 'CompanyApiController')->names([
        'index' => 'api.companies.index',
        'show' => 'api.companies.show',
        'store' => 'api.companies.store',
        'update' => 'api.companies.update',
        'destroy' => 'api.companies.delete',
    ]);

    /*
        |--------------------------------------------------------------------------
        | Products APi's
        |--------------------------------------------------------------------------
        |
    */
    Route::apiResource('products', 'ProductV1Controller')->names([
        'index' => 'v1products.index',
        'show' => 'v1products.show',
        'store' => 'v1products.store',
        'update' => 'v1products.update',
        'destroy' => 'v1products.delete',
    ])->parameters([
        'products' => 'product:slug'
    ]);
    Route::get('product/leads', 'ProductV1Controller@leads')->name('v1products.leads');

    /*
        |--------------------------------------------------------------------------
        | Settings Lists Data APi's
        |--------------------------------------------------------------------------
        |
    */
    Route::get('currencies', 'CommonListController@getcurrency');

    Route::get('account-type', 'CommonListController@accounttypes');

    Route::get('lead-type', 'CommonListController@leadtypes');

    Route::get('lead-source', 'CommonListController@leadSource');

    Route::get('industry-type', 'CommonListController@industrytypes');

    Route::get('units', 'CommonListController@units');

    Route::get('deal-type', 'CommonListController@dealType');

    Route::get('countries/{sortname?}', 'CommonListController@country');

    Route::get('states/{country_id?}', 'CommonListController@state');

    Route::get('list-category', 'CommonListController@listCategory');

    Route::get('list-subcategory/{catgory_id?}', 'CommonListController@listSubCategory');
});


