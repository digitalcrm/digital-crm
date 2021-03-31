<?php

use Illuminate\Http\Request;


/*Old routes
//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
// Route::post('register', 'Api\AuthController@register');

// Route::middleware('auth:api')->group(function () {
//     Route::resource('accounts', 'Api\AccountsController');
//     Route::resource('leads', 'Api\LeadsController');
//     Route::resource('contacts', 'Api\ContactsController');
//     Route::resource('deals', 'Api\DealsController');
// });
*/


/*Below route is defined by me*/
/*Admin Login/Register*/

Route::post('admin/login', 'Api\apiController@adminlogin');
Route::post('admin/register', 'Api\apiController@adminregister');


/*User Login/Register*/
Route::post('login', 'Api\apiController@userlogin');
Route::post('register', 'Api\apiController@userregister');

/*User Forgot Password*/
Route::post('password/email', 'Api\ForgotPasswordController@sendResetLinkEmail');
Route::post('password/reset', 'Api\ResetPasswordController@reset');

Route::get('account/lists', 'Api\apiController@getaccountLists');

Route::get('lead/lists', 'Api\apiController@getleadLists');


/*User detail other Routes*/
Route::group(['middleware' => 'auth:api'], function () {

	Route::get('profile/{profile}', 'Api\apiController@profile');

	Route::get('all/users/', 'Api\apiController@details');

	Route::post('logout', 'Api\apiController@logout'); #logout user route

	Route::post('update/user/{user}', 'Api\apiController@updateuserdetails');

	Route::get('currency', 'Api\apiController@getcurrency');

	Route::get('accounttypes', 'Api\apiController@accounttypes');

	Route::get('leadtypes', 'Api\apiController@leadtypes');

	Route::get('industrytypes', 'Api\apiController@industrytypes');

	Route::get('account/details', 'Api\apiController@getaccountDetails');

	Route::get('lead/details', 'Api\apiController@getleadDetails');

	Route::apiResource('account', 'Api\apiAccountController');

	Route::apiResource('leads', 'Api\apiLeadController');
});

//	Shop Api's
Route::get('get/products/all/{skip}/{take}', 'Api\CrudController@getProductsList');

Route::get('get/products/list', 'Api\CrudController@getProducts');

Route::get('get/products/latest/featured/{skip}/{take}', 'Api\CrudController@getProductsLatestFeatured');

Route::get('get/products/args', 'Api\AjaxController@ajaxGetProducts');
//{skip}/{min_price}/{max_price}/{procatId}/{prosubcatId}/{keyword}/{sortby}

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

Route::get('get/country/list/', 'Api\CrudController@getCountryList');

Route::get('/search/product/vendor/{keyword}', 'Api\CrudController@searchProductByVendor');

Route::apiResource('companies', 'Api\CompanyApiController');
