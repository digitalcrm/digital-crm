<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/clear-cache', function () {
        Artisan::call('config:cache');
        return redirect('/admin/dashboard');
    });
});

Auth::routes();


Route::middleware(['isActive'])->group(function () {

    // Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/home', 'HomeController@index')->name('home');

    // ------------------------------------------------------------------------

    // bootstrap 4



    Route::get('bootstrap/profile', 'HomeController@bootstrap');

    Route::get('adminlte4', 'HomeController@adminlte');

    Route::get('admin/adminlte4', 'Admin\AdminController@adminlte');



    // ------------------------------------------------------------------------

    // today events



    Route::get('today/events', 'CalendarController@getTodayEvents');









    //----------------------------Accounts Controller----------------------

    Route::resource('accounts', 'AccountController');

    Route::get('accounts/delete/{id}', 'AccountController@deleteAccount');

    Route::get('accounts/deleteAll/{id}', 'AccountController@deleteAll');

    Route::get('accounts/export/{type}', 'AccountController@exportData');

    Route::get('accounts/import/{type}', 'AccountController@import');

    Route::post('accounts/importData', 'AccountController@importData');

    Route::get('accounts/restoreAll/{id}', 'AccountController@restoreAll');



    Route::get('accounts/ajax/getlist', 'AccountController@getAccountslistAjax');



    //----------------------------Contacts Controller----------------------

    Route::resource('contacts', 'ContactController');

    Route::get('contacts/delete/{id}', 'ContactController@deleteContact');

    Route::get('contacts/export/{type}', 'ContactController@exportData');

    Route::get('contacts/import/{type}', 'ContactController@import');

    Route::post('contacts/importData', 'ContactController@importData');

    Route::get('contacts/addtolead/{id}', 'ContactController@addtoLead');

    Route::get('contacts/deleteAll/{id}', 'ContactController@deleteAll');

    Route::get('contacts/restoreAll/{id}', 'ContactController@restoreAll');

    //----------------------------Lead Controller----------------------

    Route::resource('leads', 'LeadController');

    Route::get('leads/delete/{id}', 'LeadController@deleteLead');



    Route::get('leads/get/product/{id}', 'LeadController@getLeadProduct');



    Route::get('leads/deleteAll/{id}', 'LeadController@deleteAll');

    Route::get('leads/adddeal/{id}', 'DealController@createDeal');

    Route::get('leads/addcustomer/{id}', 'DealController@createCustomer');

    Route::get('leads/changeleadstatus/{id}/{status}/{deal}', 'LeadController@changeLeadStatus');

    Route::get('leads/assign/{ld_id}', 'LeadController@assignUser');

    Route::post('leads/assign/assigntoUser', 'LeadController@assigntoUser');



    Route::get('leads/leadStatusfilter/{id}/{start}/{end}', 'LeadController@leadStatusfilter');    //day

    Route::get('leads/proleadsfilter/{id}/{start}/{end}', 'LeadController@proleadStatusfilter');    //day


    //  Lead Create Event

    Route::post('leads/storeevent', 'LeadController@storeEvent');

    Route::get('leads/restoreAll/{id}', 'LeadController@restoreAll');

    Route::get('leads/restoreallproductleads/{id}', 'LeadController@productLeadsRestoreAll');

    Route::get('leads/export/{type}', 'LeadController@exportData');

    Route::get('leads/import/{type}', 'LeadController@import');

    Route::post('leads/importData', 'LeadController@importData');

    // Route::get('leads/getproductleads/shop', 'LeadController@getProductLeads');

    Route::get('leads/getproductleads/list', 'LeadController@getProductLeads');

    Route::get('leads/order/{id}', 'LeadController@showConsumer');

    Route::get('leads/product/deleteAll/{id}', 'LeadController@productLeadsDeleteAll');

    Route::get('leads/product/{id}', 'LeadController@showProductLead');

    Route::get('leads/product/edit/{id}', 'LeadController@editProductLead');

    Route::put('leads/product/update/{id}', 'LeadController@updateProductLead');

    Route::get('leads/product/delete/{id}', 'LeadController@deleteProductLead');

    Route::get('leads/pro/import/{type}', 'LeadController@importProductLeads');

    Route::post('leads/pro/importdata', 'LeadController@importProductLeadsData');

    Route::get('leads/pro/export/{type}', 'LeadController@exportProductLeads');

    Route::post('leads/pro/exportdata', 'LeadController@exportProductLeadsData');
    //----------------------------Customer Controller----------------------

    Route::resource('customers', 'CustomersController');

    Route::get('customers/delete/{id}', 'CustomersController@delete');

    Route::get('customers/export/{type}', 'CustomersController@exportData');

    Route::get('customers/import/{type}', 'CustomersController@import');

    Route::post('customers/importData', 'CustomersController@importData');

    Route::get('customers/profile/{id}', 'CustomersController@customerProfile');

    Route::get('customers/editcustomer/{id}', 'CustomersController@editCustomer');

    Route::put('customers/updatecustomer/{id}', 'CustomersController@updateCustomer');

    Route::get('customers/filter/getresults', 'CustomersController@getFilterResults');

    Route::get('customers/print/{id}', 'CustomersController@printCustomers');



    //----------------------------Sales Controller----------------------

    Route::resource('sales', 'SalesController');

    Route::get('sales/delete/{id}', 'SalesController@delete');

    Route::get('sales/export/{type}', 'SalesController@exportData');

    Route::get('sales/import/{type}', 'SalesController@import');

    Route::post('sales/importData', 'SalesController@importData');

    Route::get('sales/filter/{start}/{end}/{status}', 'SalesController@getSalesFilter');

    Route::get('sales/paystatus/{id}/{status}', 'SalesController@updateDealPayStatus');



    //----------------------------Orders Controller----------------------

    Route::resource('orders', 'OrderController');

    Route::get('orders/getorderdealdetails/{id}', 'OrderController@getOrderDealDetails');



    //----------------------------Document Controller----------------------

    Route::resource('documents', 'DocumentController');

    Route::get('documents/delete/{id}', 'DocumentController@delete');

    Route::get('documents/deleteAll/{id}', 'DocumentController@deleteAll');

    Route::get('documents/restoreAll/{id}', 'DocumentController@restoreAll');



    //----------------------------Products Controller----------------------

    Route::resource('products', 'ProductController');

    Route::get('products/delete/{id}', 'ProductController@delete');

    Route::get('products/export/{type}', 'ProductController@exportData');

    Route::get('products/import/{type}', 'ProductController@import');

    Route::post('products/importData', 'ProductController@importData');

    Route::get('products/deleteAll/{id}', 'ProductController@deleteAll');

    Route::get('products/restoreAll/{id}', 'ProductController@restoreAll');

    Route::get('products/ajaxgetproductdetails/{id}', 'ProductController@ajaxGetProductDetails');

    Route::get('products/ajaxgetproductsubcategory/{id}', 'ProductController@ajaxGetProductSubCategory');

    Route::get('products/getproductsubcategory/autocompleteoptions/{id}/{keyword}', 'ProductController@getProductSubCategoryAutoCompleteOptions');

    Route::get('products/inventory/list', 'ProductController@getInventory');

    Route::get('products/company/list', 'ProductController@getCompanyList');

    Route::get('products/update/currentstock/status', 'ProductController@updateCurrentStockStatus');

    Route::get('products/inventory/create', 'ProductController@createInventory');

    Route::post('products/inventory/store', 'ProductController@storeInventory');

    Route::get('products/inventory/edit/{id}', 'ProductController@editInventory');

    Route::put('products/inventory/update/{id}', 'ProductController@updateInventory');

    //----------------------------Mailer Controller----------------------

    Route::resource('mailers', 'MailerController');

    Route::post('mailers/mailsendAction', 'MailerController@mailSendAction');



    Route::get('mailers/restore/{id}', 'MailerController@restoreMail');

    Route::get('mailers/restoreAll/{id}', 'MailerController@restoreAll');



    Route::get('mailers/trash/deletedmails', 'MailerController@deletedMails');

    Route::get('mailers/trash/delete/{id}', 'MailerController@deleteMail');

    Route::get('mailers/deleteAll/{id}', 'MailerController@deleteAll');



    //----------------------------Mail Controller----------------------

    Route::resource('mails', 'MailController');

    Route::get('mails/delete/{id}', 'MailController@delete');

    Route::get('mails/mailsend/{type}/{id}', 'MailController@mailSend');

    Route::post('mails/mailsendAction', 'MailController@mailSendAction');

    Route::get('inboundmails', 'MailController@InboundEmails');

    Route::get('mails/inbound/{id}', 'MailController@getUserInboundMails');

    Route::get('mails/print/{id}', 'MailController@printMail');

    Route::get('mails/trash/deletedmails', 'MailController@deletedMails');

    Route::get('mails/trash/delete/{id}', 'MailController@deleteMail');

    Route::get('mails/deleteAll/{id}', 'MailController@deleteAll');



    Route::post('mails/getemailsajax', 'MailController@getEmailsAjax');



    Route::get('mails/restore/{id}', 'MailController@restoreMail');

    Route::get('mails/restoreAll/{id}', 'MailController@restoreAll');

    Route::get('mails/test/cronjob', 'MailController@testCronjobMail');



    //----------------------------Campaigns Controller----------------------

    Route::resource('campaigns', 'CampaignController');

    Route::get('campaigns/deleteAll/{id}', 'CampaignController@deleteAll');

    Route::get('campaigns/deletecamp/{id}', 'CampaignController@deleteCamp');

    Route::get('campaigns/mails/sent/{id}', 'CampaignController@sentCampaignMails');

    Route::get('campaigns/mails/send/{id}', 'CampaignController@sendCampaignMail');

    Route::get('campaigns/mails/show/{id}', 'CampaignController@showCampaignMail');

    Route::get('campaigns/mails/create/{id}', 'CampaignController@createCampaignMails');

    Route::get('campaigns/mails/edit/{id}', 'CampaignController@editCampaignMail');

    Route::post('campaigns/mails/store/{id}', 'CampaignController@storeCampaignMails');

    Route::get('campaigns/mails/preview/{id}', 'CampaignController@previewCampaignMails');

    Route::put('campaigns/mails/update/{id}', 'CampaignController@updateCampaignMail');

    Route::get('campaigns/mails/deleteAll/{id}', 'CampaignController@deleteCampaignMails');

    Route::get('campaigns/mails/restoreAll/{id}', 'CampaignController@restoreCampaignMails');

    Route::get('campaigns/mails/trash/{id}', 'CampaignController@trashCampaignMails');



    Route::get('campaigns/getcampaignsendto/{type}/{form}', 'CampaignController@getCampaignSendto');

    Route::get('campaigns/getforms/{type}', 'CampaignController@getForms');

    //----------------------------Tax Controller----------------------

    Route::resource('tax', 'TaxController');

    Route::get('tax/deletetax/{id}', 'TaxController@deleteTax');

    Route::get('tax/deleteAll/{id}', 'TaxController@deleteAll');



    //----------------------------Trash Controller----------------------

    Route::get('trash', 'TrashController@index');

    Route::get('trash/accounts', 'TrashController@accounts');

    Route::get('trash/leads', 'TrashController@leads');

    Route::get('trash/productleads', 'TrashController@getProductLeads');

    Route::get('trash/contacts', 'TrashController@contacts');

    Route::get('trash/deals', 'TrashController@deals');

    Route::get('trash/products', 'TrashController@products');

    Route::get('trash/territory', 'TrashController@territory');

    Route::get('trash/subusers', 'TrashController@subusers');

    Route::get('trash/documents', 'TrashController@documents');

    Route::get('trash/forms', 'TrashController@forms');

    Route::get('trash/formleads', 'TrashController@formleads');

    Route::get('trash/events', 'TrashController@events');

    Route::get('trash/invoices', 'TrashController@invoices');

    Route::get('trash/restore/accounts/{id}', 'TrashController@restoreAccounts');

    Route::get('trash/restore/contacts/{id}', 'TrashController@restoreContacts');

    Route::get('trash/restore/leads/{id}', 'TrashController@restoreLeads');

    Route::get('trash/restore/deals/{id}', 'TrashController@restoreDeals');

    Route::get('trash/restore/forms/{id}', 'TrashController@restoreForms');

    Route::get('trash/restore/formleads/{id}/{form_id}', 'TrashController@restoreFormleads');

    Route::get('trash/restore/prolead/{id}', 'TrashController@restoreProductleads');

    Route::get('trash/restore/products/{id}', 'TrashController@restoreProducts');

    Route::get('trash/restore/documents/{id}', 'TrashController@restoreDocuments');

    Route::get('trash/restore/invoice/{id}', 'TrashController@restoreInvoice');

    Route::get('trash/restore/events/{id}', 'TrashController@restoreEvents');

    Route::get('trash/restore/territory/{id}', 'TrashController@restoreTerritory');

    //----------------------------Subusers Controller----------------------

    Route::resource('subusers', 'SubuserController');

    Route::get('subusers/view/{id}', 'SubuserController@view');

    Route::get('subusers/accounts/{id}', 'SubuserController@accounts');

    Route::get('subusers/contacts/{id}', 'SubuserController@contacts');

    Route::get('subusers/leads/{id}', 'SubuserController@leads');

    Route::get('subusers/deals/{id}', 'SubuserController@deals');

    Route::get('subusers/customers/{id}', 'SubuserController@customers');

    Route::get('subusers/sales/{id}', 'SubuserController@sales');

    Route::get('subusers/forms/{id}', 'SubuserController@forms');

    Route::get('subusers/formleads/{id}', 'SubuserController@formleads');

    Route::get('subusers/delete/{id}', 'SubuserController@delete');

    //

    //----------------------------Forecast Controller----------------------

    Route::resource('forecast', 'ForecastController');

    Route::get('forecastEdit/{id}/{year}', 'ForecastController@forecastEdit');

    Route::put('forecastUpdate/{id}', 'ForecastController@forecastUpdate');

    Route::get('forecast/delete/{id}', 'ForecastController@delete');



    //----------------------------Invoice Controller----------------------

    Route::resource('invoice', 'InvoiceController');

    Route::get('invoice/delete/{id}', 'InvoiceController@delete');

    Route::get('invoice/print/{id}', 'InvoiceController@printInvoice');

    Route::get('invoice/deleteAll/{id}', 'InvoiceController@deleteAll');

    Route::get('invoice/filter/{type}/{year}/{month}', 'InvoiceController@getInvoicesFilter');

    Route::get('invoice/email/{id}', 'InvoiceController@emailInvoice');

    Route::get('invoice/change/stage', 'InvoiceController@changeInvoiceStage');

    //----------------------------SMTP Controller----------------------

    Route::resource('smtp', 'SmtpController');



    //----------------------------Deal Controller----------------------

    Route::resource('deals', 'DealController');

    Route::get('deals/changestage/{deal_id}/{sfun_id}', 'DealController@changestage');

    Route::get('deals/delete/{id}', 'DealController@deleteDeal');

    Route::get('deals/export/{type}', 'DealController@exportData');

    Route::get('deals/import/{type}', 'DealController@import');

    Route::post('deals/importData', 'DealController@importData');

    Route::get('deals/status/won', 'DealController@won');

    Route::get('deals/status/lost', 'DealController@lost');

    Route::get('deals/deleteAll/{id}', 'DealController@deleteAll');

    Route::get('deals/filter/{type}/{start}/{end}', 'DealController@dealsFilter'); //  {type}

    Route::get('deals/restoreAll/{id}', 'DealController@restoreAll');

    Route::get('deals/kanban/demo', 'DealController@kanban');
    Route::get('deals/table/view', 'DealController@tableView');



    Route::get('deals/kanban/changedealstagedragndrop/{deal_id}/{sfun_id}/{from_id}', 'DealController@changeDealStageDragnDrop');



    //----------------------------Dashboard Controller----------------------

    Route::get('/dashboard', 'HomeController@dashboard');



    Route::get('dashboard/viewsreport', 'DashboardController@getViewsreport');

    Route::post('dashboard/viewsreport', 'DashboardController@getViewsreport');



    Route::get('dashboard/getSalesFunnel', 'DashboardController@getSalesFunnel');

    Route::post('dashboard/getSalesFunnel', 'DashboardController@getSalesFunnel');



    Route::get('dashboard/getSalesFunnelD3', 'DashboardController@getSalesFunnelD3');

    Route::post('dashboard/getSalesFunnelD3', 'DashboardController@getSalesFunnelD3');



    Route::get('dashboard/getDealsStage', 'DashboardController@getDealsStage');

    Route::post('dashboard/getDealsStage', 'DashboardController@getDealsStage');



    Route::get('dashboard/getLeadsData', 'DashboardController@getLeadsData');

    Route::post('dashboard/getLeadsData', 'DashboardController@getLeadsData');



    Route::get('dashboard/getAccountsData', 'DashboardController@getAccountsData');

    Route::post('dashboard/getAccountsData', 'DashboardController@getAccountsData');



    Route::get('dashboard/getContactsData', 'DashboardController@getContactsData');

    Route::post('dashboard/getContactsData', 'DashboardController@getContactsData');



    Route::get('dashboard/getCustomersData', 'DashboardController@getCustomersData');

    Route::post('dashboard/getCustomersData', 'DashboardController@getCustomersData');



    Route::get('dashboard/getSalesData', 'DashboardController@getSalesData');

    Route::post('dashboard/getSalesData', 'DashboardController@getSalesData');



    //----------------------bootstrap4-----------------------------

    Route::get('/bootstrap4', 'HomeController@bootstrapfour');



    //----------------------------Home Controller----------------------

    Route::get('/user/profile', 'HomeController@index');

    Route::get('/profile/update', 'HomeController@update');

    Route::get('/profile/userUpdate/{id}', 'HomeController@userUpdate');

    Route::put('/profile/userUpdate/{id}', 'HomeController@userUpdate');

    Route::get('/testmail', 'HomeController@testMail');

    Route::get('/inbox', 'MailController@inbox');

    Route::get('/user/verify/{token}', 'VerifyUserController@verifyUser');

    Route::get('/user/currency/{id}', 'HomeController@userCurrency');

    Route::put('/user/currency/{id}', 'HomeController@userCurrencyUpdate');

    //----------------------------Web to lead----------------------

    Route::resource('webtolead', 'WebtoleadController');

    Route::get('webtolead/deleteform/{id}', 'WebtoleadController@formDelete');

    Route::get('webtolead/formleads/{id}', 'WebtoleadController@formleads');

    Route::get('webtolead/viewformlead/{id}', 'WebtoleadController@formleadView');

    Route::get('webtolead/deleteformlead/{id}/{form_id}', 'WebtoleadController@formleadDelete');

    Route::get('webtolead/addtolead/{id}/{form_id}', 'WebtoleadController@addtoLead');



    Route::get('webtolead/deleteAllformleads/{id}', 'WebtoleadController@deleteAllformleads');

    Route::get('webtolead/deleteAllforms/{id}', 'WebtoleadController@deleteAllforms');





    Route::get('webtolead/restoreAllformleads/{id}', 'WebtoleadController@restoreAllformleads');

    Route::get('webtolead/restoreAllforms/{id}', 'WebtoleadController@restoreAllforms');



    Route::get('webtolead/latestleads/{id}', 'WebtoleadController@formleadsLatest');

    Route::get('webtolead/import/{type}', 'WebtoleadController@import');

    Route::post('webtolead/importData', 'WebtoleadController@importData');

    Route::get('webtolead/export/{type}', 'WebtoleadController@exportData');



    Route::get('webtolead/import/formleads/{type}/{form_id}', 'WebtoleadController@importFormleads');

    Route::post('webtolead/import/formleadsData', 'WebtoleadController@importFormleadsData');

    Route::get('webtolead/export/formleads/{type}/{form_id}', 'WebtoleadController@exportFormleads');





    // Route::get('webtolead/embedcode/{form_id}/{key}', 'WebtoleadController@getEmbedCode');

    // -----------------------------Leadgenerate Controller-------------------------------------------

    Route::get('leadgenerate/formviews/{id}', 'LeadgenerateController@formViews');

    Route::post('leadgenerate/submitcontact', 'LeadgenerateController@submitContact');

    Route::get('webtolead/embedcode/{form_id}/{key}', 'LeadgenerateController@getEmbedCode');

    //----------------------------Template Controller----------------------

    Route::resource('mailtemplates', 'MailTemplateController');

    Route::get('mailtemplates/deletetemplate/{id}', 'MailTemplateController@deleteTemplate');

    Route::get('mailtemplates/gettemplatedetails/{id}', 'MailTemplateController@getTemplateDetails');



    //----------------------------Widget Controller----------------------

    Route::resource('widgets', 'WidgetsController');

    Route::get('widgets/delete/{id}', 'WidgetsController@delete');


    // Ecommerce

    Route::resource('ecommerce', 'EcommerceController');
    Route::get('ecommerce/orders/list', 'EcommerceController@getOrders');

    // -----------------------------Reports Controller-------------------------------------------

    Route::get('reports/webtolead', 'ReportsController@webtolead');



    Route::get('reports/getDayFormleads/{time}/{form_id}', 'ReportsController@getDayFormleads');

    Route::post('reports/getDayFormleads/{time}/{form_id}', 'ReportsController@getDayFormleads');



    Route::get('reports/getWeekFormleads/{time}/{form_id}', 'ReportsController@getWeekFormleads');

    Route::post('reports/getWeekFormleads/{time}/{form_id}', 'ReportsController@getWeekFormleads');



    Route::get('reports/getMonthFormleads/{time}/{form_id}', 'ReportsController@getMonthFormleads');

    Route::post('reports/getMonthFormleads/{time}/{form_id}', 'ReportsController@getMonthFormleads');



    Route::get('reports/getYearFormleads/{time}/{form_id}', 'ReportsController@getYearFormleads');

    Route::post('reports/getYearFormleads/{time}/{form_id}', 'ReportsController@getYearFormleads');



    Route::get('reports/leads', 'ReportsController@leads');

    Route::get('reports/getDayLeads', 'ReportsController@getDayLeads');

    Route::post('reports/getDayLeads', 'ReportsController@getDayLeads');



    Route::get('reports/getMonthLeads', 'ReportsController@getMonthLeads');

    Route::post('reports/getMonthLeads', 'ReportsController@getMonthLeads');



    Route::get('reports/getWeekLeads', 'ReportsController@getWeekLeads');

    Route::post('reports/getWeekLeads', 'ReportsController@getWeekLeads');



    Route::get('reports/accounts', 'ReportsController@accounts');

    Route::get('reports/getDayAccounts', 'ReportsController@getDayAccounts');

    Route::post('reports/getDayAccounts', 'ReportsController@getDayAccounts');



    Route::get('reports/getMonthAccounts', 'ReportsController@getMonthAccounts');

    Route::post('reports/getMonthAccounts', 'ReportsController@getMonthAccounts');



    Route::get('reports/getWeekAccounts', 'ReportsController@getWeekAccounts');

    Route::post('reports/getWeekAccounts', 'ReportsController@getWeekAccounts');



    Route::get('reports/contacts', 'ReportsController@contacts');

    Route::get('reports/getDayContacts', 'ReportsController@getDayContacts');

    Route::post('reports/getDayContacts', 'ReportsController@getDayContacts');



    Route::get('reports/getMonthContacts', 'ReportsController@getMonthContacts');

    Route::post('reports/getMonthContacts', 'ReportsController@getMonthContacts');



    Route::get('reports/getWeekContacts', 'ReportsController@getWeekContacts');

    Route::post('reports/getWeekContacts', 'ReportsController@getWeekContacts');



    Route::get('reports/deals', 'ReportsController@deals');

    Route::get('reports/getDayDeals/{time}/{form_id}', 'ReportsController@getDayDeals');

    Route::post('reports/getDayDeals/{time}/{form_id}', 'ReportsController@getDayDeals');



    Route::get('reports/getMonthDeals/{time}/{form_id}', 'ReportsController@getMonthDeals');

    Route::post('reports/getMonthDeals/{time}/{form_id}', 'ReportsController@getMonthDeals');



    Route::get('reports/getWeekDeals/{time}/{form_id}', 'ReportsController@getWeekDeals');

    Route::post('reports/getWeekDeals/{time}/{form_id}', 'ReportsController@getWeekDeals');



    Route::get('reports/getYearDeals/{time}/{form_id}', 'ReportsController@getYearDeals');

    Route::post('reports/getYearDeals/{time}/{form_id}', 'ReportsController@getYearDeals');



    Route::get('reports/customers', 'ReportsController@customers');

    Route::get('reports/getDayCustomers', 'ReportsController@getDayCustomers');

    Route::post('reports/getDayCustomers', 'ReportsController@getDayCustomers');



    Route::get('reports/getMonthCustomers', 'ReportsController@getMonthCustomers');

    Route::post('reports/getMonthCustomers', 'ReportsController@getMonthCustomers');



    Route::get('reports/getWeekCustomers', 'ReportsController@getWeekCustomers');

    Route::post('reports/getWeekCustomers', 'ReportsController@getWeekCustomers');



    Route::get('reports/sales', 'ReportsController@sales');

    Route::get('reports/getDaySales', 'ReportsController@getDaySales');

    Route::post('reports/getDaySales', 'ReportsController@getDaySales');



    Route::get('reports/getMonthSales', 'ReportsController@getMonthSales');

    Route::post('reports/getMonthSales', 'ReportsController@getMonthSales');



    Route::get('reports/getWeekSales', 'ReportsController@getWeekSales');

    Route::post('reports/getWeekSales', 'ReportsController@getWeekSales');

    Route::get('reports/productleads', 'ReportsController@productleads');
    Route::get('reports/getdayproductleads', 'ReportsController@getDayProductLeads');
    Route::get('reports/getmonthproductleads', 'ReportsController@getMonthProductLeads');
    Route::get('reports/getweekproductleads', 'ReportsController@getWeekProductLeads');
    Route::get('reports/getyearproductleads', 'ReportsController@getYearProductLeads');


    Route::get('reports/products', 'ReportsController@products');
    Route::get('reports/companies', 'ReportsController@companies');

    Route::get('reports/getdayproducts', 'ReportsController@getDayProducts');
    Route::get('reports/getmonthproducts', 'ReportsController@getMonthProducts');
    Route::get('reports/getweekproducts', 'ReportsController@getWeekProducts');
    Route::get('reports/getyearproducts', 'ReportsController@getYearProducts');

    Route::get('reports/getdaycompanies', 'ReportsController@getDayCompanies');
    Route::get('reports/getmonthcompanies', 'ReportsController@getMonthCompanies');
    Route::get('reports/getweekcompanies', 'ReportsController@getWeekCompanies');
    Route::get('reports/getyearcompanies', 'ReportsController@getYearCompanies');

    //----------------------------Notifications Controller----------------------

    Route::resource('notifications', 'NotificationController');

    Route::get('notifications/delete/{id}', 'NotificationController@delete');

    Route::get('notifications/deleteAll/{id}', 'NotificationController@deleteAll');



    //----------------------------Territory Controller----------------------

    Route::resource('territory', 'TerritoryController');

    Route::get('territory/delete/{id}', 'TerritoryController@delete');

    Route::get('territory/deleteAll/{id}', 'TerritoryController@deleteAll');

    Route::get('territory/restoreAll/{id}', 'TerritoryController@restoreAll');



    //----------------------------Newsletter Controller----------------------

    Route::resource('newsletter', 'NewsletterController');

    Route::get('newsletter/delete/{id}', 'NewsletterController@delete');



    //----------------------------Calendar Controller----------------------

    Route::resource('calendar', 'CalendarController');

    Route::get('calendar/delete/{id}', 'CalendarController@delete');



    //----------------------------Settings Controller----------------------

    Route::resource('settings', 'SettingsController');



    //----------------------------Projects Controller----------------------

    Route::resource('projects', 'ProjectController');

    Route::get('projects/getprojectsbystatus/{id}', 'ProjectController@getProjectsByStatus');

    Route::get('projects/filter/{manager}/{auser}', 'ProjectController@ajaxGetProjectsList');

    Route::get('projects/getSubmissionDate/{startDate}/{days}', 'ProjectController@getSubmissionDate');

    Route::get('projects/deleteAll/{id}', 'ProjectController@deleteAllProjects');

    Route::get('projects/delete/{id}', 'ProjectController@delete');

    Route::get('projects/sidebar/demo', 'ProjectController@sidebar');

    Route::get('projects/demo/getolddate', 'ProjectController@getOldDate');

    Route::get('projects/demo/getlatestdate', 'ProjectController@getLatestDate');



    //----------------------------Rd's Controller----------------------

    Route::resource('rds', 'RdController');

    Route::get('rds/delete/{id}', 'RdController@delete');

    Route::get('rds/deleteAll/{id}', 'RdController@deleteAll');

    // Route::get('rds/ajaxgetrdlist/{type}/{intype_id}/{rdpr_id}/{pro_id}/{status}/{create_date}/{submit_date}/{upload_date}/{procat_id}', 'RdController@AjaxGetRdList');

    Route::get('rds/ajaxgetrdlist/{type}/{intype_id}/{rdpr_id}/{pro_id}/{status}/{create_date_start}/{create_date_end}/{submit_date_start}/{submit_date_end}/{upload_date_start}/{upload_date_end}/{procat_id}', 'RdController@AjaxGetRdList');

    Route::get('rds/ajaxchangerdstatus/{id}/{status}', 'RdController@AjaxchangeRdStatus');



    // Route::get('rds/getprojectsbystatus/{id}', 'RdController@getProjectsByStatus');



    //----------------------------File Manager Controller----------------------

    Route::resource('files', 'FileManagerController');

    //Route::get('admin/files/delete/{id}', 'Admin\FileManagerController@delete');



    // -----------------------------Ajax Controller-------------------------------------------

    Route::get('ajaxwebtolead/previewForm', 'AjaxController@previewForm');

    Route::post('ajaxwebtolead/previewForm', 'AjaxController@previewForm');



    Route::get('ajaxwebtolead/getStateoptions', 'AjaxController@getStateoptions');

    Route::post('ajaxwebtolead/getStateoptions', 'AjaxController@getStateoptions');



    Route::get('ajax/getUserleads', 'AjaxController@getUserleads');

    Route::post('ajax/getUserleads', 'AjaxController@getUserleads');



    Route::get('ajax/getLeadselect', 'AjaxController@getUserLeadselect');

    Route::post('ajax/getLeadselect', 'AjaxController@getUserLeadselect');



    Route::get('ajax/getAccountselect', 'AjaxController@getUserAccountselect');

    Route::post('ajax/getAccountselect', 'AjaxController@getUserAccountselect');



    Route::get('ajax/getContactselect', 'AjaxController@getUserContactselect');

    Route::post('ajax/getContactselect', 'AjaxController@getUserContactselect');



    Route::get('ajax/getUserwebtoleads', 'AjaxController@getUserwebtoleads');

    Route::post('ajax/getUserwebtoleads', 'AjaxController@getUserwebtoleads');



    Route::get('ajax/getlatestnotifications', 'AjaxController@getLatestnotifications');

    Route::post('ajax/getlatestnotifications', 'AjaxController@getLatestnotifications');



    Route::get('ajax/markallasread', 'AjaxController@markAllasread');

    Route::post('ajax/markallasread', 'AjaxController@markAllasread');



    Route::get('ajax/markasread', 'AjaxController@markAsRead');

    Route::post('ajax/markasread', 'AjaxController@markAsRead');



    Route::get('ajax/getUserEvents', 'AjaxController@getUserEvents');

    Route::post('ajax/getUserEvents', 'AjaxController@getUserEvents');



    Route::get('ajax/getUserForecast', 'AjaxController@getUserForecast');

    Route::post('ajax/getUserForecast', 'AjaxController@getUserForecast');



    //------------------------------Search Controller------------------------

    //Route::post('search/getsearchresults/', 'SearchController@getSearchResults')->name('search.getsearchresults');

    Route::get('search/getsearchresults/', 'SearchController@getSearchResults')->name('search.getsearchresults');
});


// -----------------------------Ajax Controller - Admin -------------------------------------------



Route::get('admin/ajax/getUserAccounts', 'Admin\AjaxController@getUserAccounts');

Route::post('admin/ajax/getUserAccounts', 'Admin\AjaxController@getUserAccounts');



Route::get('admin/ajax/getUserContacts', 'Admin\AjaxController@getUserContacts');

Route::post('admin/ajax/getUserContacts', 'Admin\AjaxController@getUserContacts');



Route::get('admin/ajax/getUserForms', 'Admin\AjaxController@getUserForms');

Route::post('admin/ajax/getUserForms', 'Admin\AjaxController@getUserForms');



Route::get('admin/ajax/getUserLeads', 'Admin\AjaxController@getUserLeads');

Route::post('admin/ajax/getUserLeads', 'Admin\AjaxController@getUserLeads');



Route::get('admin/ajax/getUserDeals/{uid}/{stage}/{start}/{end}', 'Admin\AjaxController@getUserDeals');

Route::post('admin/ajax/getUserDeals', 'Admin\AjaxController@getUserDeals');



Route::get('admin/ajax/getUserCustomers', 'Admin\AjaxController@getUserCustomers');

Route::post('admin/ajax/getUserCustomers', 'Admin\AjaxController@getUserCustomers');



Route::get('admin/ajax/getUserSales', 'Admin\AjaxController@getUserSales');

Route::post('admin/ajax/getUserSales', 'Admin\AjaxController@getUserSales');



Route::get('admin/ajax/getUserTerritory', 'Admin\AjaxController@getUserTerritory');

Route::post('admin/ajax/getUserTerritory', 'Admin\AjaxController@getUserTerritory');



Route::get('admin/ajax/getUserProducts/{uid}', 'Admin\AjaxController@getUserProducts');

//

// Route::post('admin/ajax/getUserProducts', 'Admin\AjaxController@getUserProducts');



Route::get('admin/ajax/getUserEvents', 'Admin\AjaxController@getUserEvents');

Route::post('admin/ajax/getUserEvents', 'Admin\AjaxController@getUserEvents');



Route::get('admin/ajax/getUserDocuments', 'Admin\AjaxController@getUserDocuments');

Route::post('admin/ajax/getUserDocuments', 'Admin\AjaxController@getUserDocuments');



Route::get('admin/ajax/getStateoptions', 'Admin\AjaxController@getStateoptions');

Route::post('admin/ajax/getStateoptions', 'Admin\AjaxController@getStateoptions');



Route::get('admin/ajax/getSubuseroptions', 'Admin\AjaxController@getSubuseroptions');

Route::post('admin/ajax/getSubuseroptions', 'Admin\AjaxController@getSubuseroptions');



Route::get('admin/ajax/getUserCurrency', 'Admin\AjaxController@getUserCurrency');

Route::post('admin/ajax/getUserCurrency', 'Admin\AjaxController@getUserCurrency');



Route::get('admin/ajax/getLeadselect', 'Admin\AjaxController@getUserLeadselect');

Route::post('admin/ajax/getLeadselect', 'Admin\AjaxController@getUserLeadselect');



Route::get('admin/ajax/getAccountselect', 'Admin\AjaxController@getUserAccountselect');

Route::post('admin/ajax/getAccountselect', 'Admin\AjaxController@getUserAccountselect');



Route::get('admin/ajax/getContactselect', 'Admin\AjaxController@getUserContactselect');

Route::post('admin/ajax/getContactselect', 'Admin\AjaxController@getUserContactselect');



Route::get('admin/ajax/getUserForecast', 'Admin\AjaxController@getUserForecast');

Route::post('admin/ajax/getUserForecast', 'Admin\AjaxController@getUserForecast');



Route::get('admin/ajax/getUserInvoices', 'Admin\AjaxController@getUserInvoices');

Route::post('admin/ajax/getUserInvoices', 'Admin\AjaxController@getUserInvoices');



Route::get('admin/dashboard/getReportsYearData', 'Admin\AdminController@getReportsYearData');

Route::post('admin/dashboard/getReportsYearData', 'Admin\AdminController@getReportsYearData');





Route::get('admin/dashboard/viewsreport', 'Admin\AdminController@getViewsreport');

Route::post('admin/dashboard/viewsreport', 'Admin\AdminController@getViewsreport');



Route::get('admin/dashboard/getSalesFunnel', 'Admin\AdminController@getSalesFunnel');

Route::post('admin/dashboard/getSalesFunnel', 'Admin\AdminController@getSalesFunnel');



Route::get('admin/dashboard/getSalesFunnelD3', 'Admin\AdminController@getSalesFunnelD3');

Route::post('admin/dashboard/getSalesFunnelD3', 'Admin\AdminController@getSalesFunnelD3');



Route::get('admin/dashboard/getDealsStage', 'Admin\AdminController@getDealsStage');

Route::post('admin/dashboard/getDealsStage', 'Admin\AdminController@getDealsStage');



Route::get('admin/dashboard/getLeadsData', 'Admin\AdminController@getLeadsData');

Route::post('admin/dashboard/getLeadsData', 'Admin\AdminController@getLeadsData');



Route::get('admin/dashboard/getAccountsData', 'Admin\AdminController@getAccountsData');

Route::post('admin/dashboard/getAccountsData', 'Admin\AdminController@getAccountsData');



Route::get('admin/dashboard/getContactsData', 'Admin\AdminController@getContactsData');

Route::post('admin/dashboard/getContactsData', 'Admin\AdminController@getContactsData');



Route::get('admin/dashboard/getCustomersData', 'Admin\AdminController@getCustomersData');

Route::post('admin/dashboard/getCustomersData', 'Admin\AdminController@getCustomersData');



Route::get('admin/dashboard/getSalesData', 'Admin\AdminController@getSalesData');

Route::post('admin/dashboard/getSalesData', 'Admin\AdminController@getSalesData');







//------------------------------------------------------------------------------

// Admin routes

// Route::prefix('admin')->group(function(){

//     Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');

//     Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');

//     Route::post('/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');

//     Route::get('/', 'AdminController@index')->name('admin.dashboard');

// });

// -----------------------------Admin-------------------------------------------



Route::get('admin/dashboard', 'Admin\AdminController@index')->name('admin.dashboard');



Route::get('admin/profile', 'Admin\AdminController@editprofile')->name('admin.profile');

Route::get('admin/updateprofile', 'Admin\AdminController@update');

Route::get('admin/profile/{id}', 'Admin\AdminController@adminUpdate');

Route::put('admin/profile/{id}', 'Admin\AdminController@adminUpdate');



Route::get('admin', 'Admin\LoginController@showLoginForm')->name('admin.login');

Route::post('admin', 'Admin\LoginController@login');

Route::post('admin/password/email', 'Admin\ForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');

Route::get('admin/password/reset', 'Admin\ForgotPasswordController@showLinkRequestForm')->name('admin.password.request');

Route::post('admin/password/reset', 'Admin\ResetPasswordController@reset');

Route::get('admin/password/reset/{token}', 'Admin\ResetPasswordController@showResetForm')->name('admin.password.reset');

Route::post('adminlogout', 'Admin\AdminController@logout')->name('admin.logout');



Route::get('/admin/verify/{token}', 'VerifyUserController@verifyAdmin');



Route::get('admin/admins', 'Admin\AdminController@indexAdmin');

Route::get('admin/admins/create', 'Admin\AdminController@createAdmin');

Route::post('admin/admins/store', 'Admin\AdminController@storeAdmin');

Route::get('admin/admins/block/{id}/{block}', 'Admin\AdminController@block');



Route::get('admin/admins/setpermit/{id}', 'Admin\AdminController@setPermission');

Route::put('admin/admins/storepermit/{id}', 'Admin\AdminController@storePermission');

Route::get('admin/admins/show/{id}', 'Admin\AdminController@showAdmin');

Route::get('admin/admins/edit/{id}', 'Admin\AdminController@showAdmin');

Route::put('admin/admins/update/{id}', 'Admin\AdminController@updateAdmin');


Route::get('admin/getmails', 'Admin\AdminController@getMails');

Route::get('admin/getmaildata/{id}', 'Admin\AdminController@getMailData');


Route::get('admin/app/edit/{id}', 'Admin\AdminController@editApp');
Route::put('admin/app/update/{id}', 'Admin\AdminController@updateApp');



//----------------------------User Controller----------------------

Route::resource('admin/users', 'Admin\UserController');

Route::get('admin/users/block/{id}/{block}', 'Admin\UserController@block');

Route::get('admin/users/delete/{id}', 'Admin\UserController@delete');

Route::get('admin/users/subusers/{id}', 'Admin\UserController@subusers');

Route::get('admin/users/role/add/{id}', 'Admin\UserController@addRole');

Route::get('admin/users/setfeatures/{id}', 'Admin\UserController@setFeatures');

Route::put('admin/users/updatefeatures/{id}', 'Admin\UserController@updateFeatures');


//----------------------------Clients Controller----------------------
Route::resource('admin/clients', 'Admin\ClientController');

Route::get('admin/clients/block/{id}/{block}', 'Admin\ClientController@block');
Route::get('admin/clients/delete/{id}', 'Admin\ClientController@delete');


//----------------------------Web to lead----------------------

Route::resource('admin/webtolead', 'Admin\WebtoleadController')->names([
    'index' => 'webtoleadadmin.index',
    'show' => 'webtoleadadmin.show',
    'create' => 'webtoleadadmin.create',
    'edit' => 'webtoleadadmin.edit',
    'store' => 'webtoleadadmin.store',
    'update' => 'webtoleadadmin.update',
    'destroy' => 'webtoleadadmin.delete',
]);

Route::get('admin/webtolead/formdelete/{id}', 'Admin\WebtoleadController@formdelete');



Route::get('admin/webtolead/formleads/{id}', 'Admin\WebtoleadController@formleads');

Route::get('admin/webtolead/viewformlead/{id}', 'Admin\WebtoleadController@formleadView');

Route::get('admin/webtolead/deleteformlead/{id}/{form_id}', 'Admin\WebtoleadController@formleadDelete');

Route::get('admin/webtolead/addtolead/{id}/{form_id}', 'Admin\WebtoleadController@addtoLead');

Route::get('admin/webtolead/deleteAllforms/{id}', 'Admin\WebtoleadController@deleteAllforms');

Route::get('admin/webtolead/deleteAllformleads/{id}', 'Admin\WebtoleadController@deleteAllformleads');

Route::get('admin/webtolead/previewForm/{form_id}/{type}', 'Admin\WebtoleadController@previewForm');

//----------------------------Lead Controller----------------------

Route::resource('admin/leads', 'Admin\LeadController')->names([
    'index' => 'adminlead.index',
    'show' => 'adminlead.show',
    'create' => 'adminlead.create',
    'edit' => 'adminlead.edit',
    'store' => 'adminlead.store',
    'update' => 'adminlead.update',
    'destroy' => 'adminlead.delete',
]);

Route::get('admin/leads/delete/{id}', 'Admin\LeadController@deleteLead');

Route::get('admin/leads/deleteAll/{id}', 'Admin\LeadController@deleteAll');

Route::get('admin/leads/product/deleteAll/{id}', 'Admin\LeadController@productLeadsDeleteAll');

Route::get('admin/getleadsbyassignment', 'Admin\LeadController@getLeadsbyAssignment');

Route::get('admin/zpleads', 'Admin\LeadController@getLeadsZapier');

Route::get('admin/zpleads/ajax/{date}', 'Admin\LeadController@getLeadsZapierAjax');

//Route::get('admin/fbleads', 'Admin\LeadController@getLeadsFbCsv');

Route::get('admin/webtoleadformleads', 'Admin\LeadController@webFormleads');

Route::get('admin/getformleads', 'Admin\LeadController@getFormleads');



Route::get('admin/leads/get/product/{id}', 'Admin\LeadController@getLeadProduct');



Route::get('admin/getleadsofuser/{id}/{status}/{start}/{end}', 'Admin\LeadController@getLeadsofUser');  //admin/getleadsofuser/{id}/{status}/{start}/{end}



Route::get('admin/getleadsdummy/{uid}/{status}/{formid}', 'Admin\LeadController@getleadsdummy');

//  Facebook Leads

Route::get('admin/fbleads/{id}', 'Admin\LeadController@facebookLeadscsv');

Route::get('admin/getfbleads', 'Admin\LeadController@getFacebookLeadscsv');

//Route::get('admin/fbleads/{id}/{type}', 'Admin\LeadController@facebookLeads');

Route::get('admin/facebookleads/{id}/{type}', 'Admin\LeadController@getfbleads');

Route::get('admin/assignleadtouser/{user}/{lead}', 'Admin\LeadController@assignLeadtoUser');

Route::get('admin/unassignedleads', 'Admin\LeadController@getUnassignedLeads');

Route::get('admin/allocateleadsquota', 'Admin\LeadController@allocateLeadsQuota');

Route::put('admin/allocateleadsquotatouser/{id}', 'Admin\LeadController@allocateLeadsQuotatoUser');

Route::get('admin/assignleadstouser', 'Admin\LeadController@assignLeadstoUser');

Route::post('admin/assignleadstouserbyquota', 'Admin\LeadController@assignLeadstoUserbyQuota');

Route::get('admin/formfacebookleads/{id}', 'Admin\LeadController@getFbleadsbyForm');

//  Facebook Leads From Zapier

Route::get('admin/storefblead', 'Admin\LeadApiController@storeFbLead');

//  Round Robin Cron Job

Route::get('admin/setroundrobin', 'Admin\LeadController@setRoundRobin');

Route::get('admin/setroundrobintogroupusers', 'Admin\LeadController@setRoundRobintoGroupUsers');



Route::get('admin/leads/import/{type}', 'Admin\LeadController@import');

Route::post('admin/leads/importData', 'Admin\LeadController@importData');

Route::get('admin/leads/export/{type}', 'Admin\LeadController@export');

Route::post('admin/leads/exportData', 'Admin\LeadController@exportData');

// Route::get('admin/leads/getproductleads/shop', 'Admin\LeadController@getProductLeads');

Route::get('admin/leads/getproductleads/list', 'Admin\LeadController@getProductLeads');

Route::get('admin/leads/order/{id}', 'Admin\LeadController@showConsumer');

Route::get('admin/ajax/getproductleads/{uid}', 'Admin\LeadController@getProductLeadsAjax');

Route::get('admin/leads/product/{id}', 'Admin\LeadController@showProductLead');

Route::get('admin/leads/product/edit/{id}', 'Admin\LeadController@editProductLead');

Route::put('admin/leads/product/update/{id}', 'Admin\LeadController@updateProductLead');

Route::get('admin/leads/product/delete/{id}', 'Admin\LeadController@deleteProductLead');

//----------------------------Import leads from CSV----------------------

Route::get('admin/leads/import/{id}', 'Admin\LeadController@importLeadFromCSV');

Route::post('admin/leads/importleaddata', 'Admin\LeadController@importLeadData');



//----------------------------Lead Controller----------------------

Route::resource('admin/leadcsv', 'Admin\LeadCsvController');

Route::get('admin/leadcsv/delete/{id}', 'Admin\LeadCsvController@deleteLead');

Route::get('admin/fbadmanager', 'Admin\LeadCsvController@getFbAdManager');



//----------------------Import leads from CSV----------------------

Route::post('admin/leadcsv/importleaddata', 'Admin\LeadCsvController@importLeadData');

//----------------------------Account Controller----------------------

// Route::group(['middleware' => ['auth:admin']], function () {

Route::resource('admin/accounts', 'Admin\AccountController')->names([
    'index' => 'adminaccounts.index',
    'show' => 'adminaccounts.show',
    'create' => 'adminaccounts.create',
    'edit' => 'adminaccounts.edit',
    'store' => 'adminaccounts.store',
    'update' => 'adminaccounts.update',
    'destroy' => 'adminaccounts.delete',
]);

Route::get('admin/accounts/delete/{id}', 'Admin\AccountController@deleteAccount');

Route::get('admin/accounts/deleteAll/{id}', 'Admin\AccountController@deleteAll');



Route::get('admin/accounts/import/{type}', 'Admin\AccountController@import');

Route::post('admin/accounts/importData', 'Admin\AccountController@importData');

Route::get('admin/accounts/export/{type}', 'Admin\AccountController@export');

Route::post('admin/accounts/exportData', 'Admin\AccountController@exportData');

// });

// Company Controller

Route::resource('admin/companies', 'Admin\CompanyController')->names([
    'index' => 'admincompanies.index',
    'show' => 'admincompanies.show',
    'create' => 'admincompanies.create',
    'edit' => 'admincompanies.edit',
    'store' => 'admincompanies.store',
    'update' => 'admincompanies.update',
    'destroy' => 'admincompanies.delete',
]);

Route::get('admin/ajax/getUserCompanies', 'Admin\CompanyController@getUserCompanies');

Route::get('admin/companies/delete/{id}', 'Admin\CompanyController@deleteCompany');

Route::get('admin/companies/deleteAll/{id}', 'Admin\CompanyController@deleteAll');
//----------------------------Contact Controller----------------------

Route::resource('admin/contacts', 'Admin\ContactController')->names([
    'index' => 'admincontacts.index',
    'show' => 'admincontacts.show',
    'create' => 'admincontacts.create',
    'edit' => 'admincontacts.edit',
    'store' => 'admincontacts.store',
    'update' => 'admincontacts.update',
    'destroy' => 'admincontacts.delete',
]);

Route::get('admin/contacts/delete/{id}', 'Admin\ContactController@deleteContact');

Route::get('admin/contacts/deleteAll/{id}', 'Admin\ContactController@deleteAll');



Route::get('admin/contacts/import/{type}', 'Admin\ContactController@import');

Route::post('admin/contacts/importData', 'Admin\ContactController@importData');

Route::get('admin/contacts/export/{type}', 'Admin\ContactController@export');

Route::post('admin/contacts/exportData', 'Admin\ContactController@exportData');



//----------------------------Deal Controller----------------------

Route::resource('admin/deals', 'Admin\DealController')->names([
    'index' => 'admindeals.index',
    'show' => 'admindeals.show',
    'create' => 'admindeals.create',
    'edit' => 'admindeals.edit',
    'store' => 'admindeals.store',
    'update' => 'admindeals.update',
    'destroy' => 'admindeals.delete',
]);

Route::get('admin/deals/delete/{id}', 'Admin\DealController@deleteDeal');

Route::get('admin/deals/deleteAll/{id}', 'Admin\DealController@deleteAll');



Route::get('admin/deals/import/{type}', 'Admin\DealController@import');

Route::post('admin/deals/importData', 'Admin\DealController@importData');

Route::get('admin/deals/export/{type}', 'Admin\DealController@export');

Route::post('admin/deals/exportData', 'Admin\DealController@exportData');



//----------------------------Customers Controller----------------------

Route::resource('admin/customers', 'Admin\CustomersController')->names([
    'index' => 'admincustomers.index',
    'show' => 'admincustomers.show',
    'create' => 'admincustomers.create',
    'edit' => 'admincustomers.edit',
    'store' => 'admincustomers.store',
    'update' => 'admincustomers.update',
    'destroy' => 'admincustomers.delete',
]);

Route::get('admin/customers/delete/{id}', 'Admin\CustomersController@delete');



//----------------------------Sales Controller----------------------

Route::resource('admin/sales', 'Admin\SalesController')->names([
    'index' => 'adminsales.index',
    'show' => 'adminsales.show',
    'create' => 'adminsales.create',
    'edit' => 'adminsales.edit',
    'store' => 'adminsales.store',
    'update' => 'adminsales.update',
    'destroy' => 'adminsales.delete',
]);

Route::get('admin/sales/filter/{start}/{end}/{status}/{user}', 'Admin\SalesController@getSalesFilter');

Route::get('admin/sales/paystatus/{id}/{status}', 'Admin\SalesController@updateDealPayStatus');

Route::get('admin/sales/delete/{id}', 'Admin\SalesController@delete');



//----------------------------Orders Controller----------------------

Route::resource('admin/orders', 'Admin\OrderController')->names([
    'index' => 'adminorders.index',
    'show' => 'adminorders.show',
    'create' => 'adminorders.create',
    'edit' => 'adminorders.edit',
    'store' => 'adminorders.store',
    'update' => 'adminorders.update',
    'destroy' => 'adminorders.delete',
]);

Route::get('admin/orders/getuserorders/{id}', 'Admin\OrderController@getUserOrders');

Route::get('admin/orders/delete/{id}', 'Admin\OrderController@delete');



//----------------------------Sales Controller----------------------

Route::resource('admin/subusers', 'Admin\ForecastController')->names([
    'index' => 'adminsubusers.index',
    'show' => 'adminsubusers.show',
    'create' => 'adminsubusers.create',
    'edit' => 'adminsubusers.edit',
    'store' => 'adminsubusers.store',
    'update' => 'adminsubusers.update',
    'destroy' => 'adminsubusers.delete',
]);

Route::get('admin/subusers/delete/{id}', 'Admin\ForecastController@delete');



//----------------------------Sales Controller----------------------

Route::resource('admin/territory', 'Admin\TerritoryController')->names([
    'index' => 'adminterritory.index',
    'show' => 'adminterritory.show',
    'create' => 'adminterritory.create',
    'edit' => 'adminterritory.edit',
    'store' => 'adminterritory.store',
    'update' => 'adminterritory.update',
    'destroy' => 'adminterritory.delete',
]);

Route::get('admin/territory/delete/{id}', 'Admin\TerritoryController@delete');



//----------------------------Product Controller----------------------

Route::resource('admin/products', 'Admin\ProductController')->names([
    'index' => 'adminproducts.index',
    'show' => 'adminproducts.show',
    'create' => 'adminproducts.create',
    'edit' => 'adminproducts.edit',
    'store' => 'adminproducts.store',
    'update' => 'adminproducts.update',
    'destroy' => 'adminproducts.delete',
]);

Route::get('admin/products/delete/{id}', 'Admin\ProductController@delete');

Route::get('admin/products/ajaxgetproductdetails/{id}', 'Admin\ProductController@ajaxGetProductDetails');

Route::get('admin/products/ajaxgetproductsubcategory/{id}', 'Admin\ProductController@ajaxGetProductSubCategory');

Route::get('admin/products/export/{type}', 'Admin\ProductController@export');

Route::post('admin/products/exportData', 'Admin\ProductController@exportData');

Route::get('admin/products/import/{type}', 'Admin\ProductController@import');

Route::post('admin/products/importData', 'Admin\ProductController@importData');

Route::get('admin/products/ajaxupdatefeaturestatus/{id}/{status}', 'Admin\ProductController@ajaxUpdateFeatureStatus');

Route::get('admin/products/inventory/list', 'Admin\ProductController@getInventory');

Route::get('admin/products/company/list', 'Admin\ProductController@getCompanyList');

Route::get('admin/products/update/currentstock/status', 'Admin\ProductController@updateCurrentStockStatus');

Route::get('admin/products/inventory/edit/{id}', 'Admin\ProductController@editInventory');

Route::put('admin/products/inventory/update/{id}', 'Admin\ProductController@updateInventory');

Route::get('admin/products/inventory/show/{id}', 'Admin\ProductController@showInventory');

Route::get('admin/products/getuserinventory/{uid}', 'Admin\ProductController@getUserInventory');

Route::get('admin/products/search/analytics', 'Admin\ProductController@getSearchKeywordAnalytics');

//----------------------------Product Controller----------------------

Route::resource('admin/calendar', 'Admin\CalendarController')->names([
    'index' => 'admincalendar.index',
    'show' => 'admincalendar.show',
    'create' => 'admincalendar.create',
    'edit' => 'admincalendar.edit',
    'store' => 'admincalendar.store',
    'update' => 'admincalendar.update',
    'destroy' => 'admincalendar.delete',
]);

Route::get('admin/calendar/delete/{id}', 'Admin\CalendarController@delete');



//----------------------------Rd's Controller----------------------

Route::resource('admin/rds', 'Admin\RdController')->names([
    'index' => 'adminrds.index',
    'show' => 'adminrds.show',
    'create' => 'adminrds.create',
    'edit' => 'adminrds.edit',
    'store' => 'adminrds.store',
    'update' => 'adminrds.update',
    'destroy' => 'adminrds.delete',
]);

Route::get('admin/rds/delete/{id}', 'Admin\RdController@delete');

Route::get('admin/rds/ajaxchangerdstatus/{id}/{status}', 'Admin\RdController@AjaxchangeRdStatus');

Route::get('admin/rds/ajaxgetrdlist/{id}/{type}/{user_type}/{intype_id}/{rdpr_id}/{pro_id}/{status}/{create_date_start}/{create_date_end}/{submit_date_start}/{submit_date_end}/{upload_date_start}/{upload_date_end}/{procat_id}', 'Admin\RdController@AjaxGetRdList');

//  {create_date}/{submit_date}/{upload_date}

//----------------------------Projects Controller----------------------

Route::resource('admin/projects', 'Admin\ProjectController')->names([
    'index' => 'adminprojects.index',
    'show' => 'adminprojects.show',
    'create' => 'adminprojects.create',
    'edit' => 'adminprojects.edit',
    'store' => 'adminprojects.store',
    'update' => 'adminprojects.update',
    'destroy' => 'adminprojects.delete',
]);

Route::get('admin/projects/getprojectsbystatus/{id}', 'Admin\ProjectController@getProjectsByStatus');

Route::get('admin/projects/filter/{user}/{manager}/{auser}', 'Admin\ProjectController@ajaxGetProjectsList');

Route::get('admin/projects/getSubmissionDate/{startDate}/{days}', 'Admin\ProjectController@getSubmissionDate');

Route::get('admin/projects/deleteAll/{id}', 'Admin\ProjectController@deleteAllProjects');

Route::get('admin/projects/delete/{id}', 'Admin\ProjectController@delete');



//----------------------------Settings Controller----------------------

Route::resource('admin/settings', 'Admin\SettingsController')->names([
    'index' => 'adminsettings.index',
    'show' => 'adminsettings.show',
    'create' => 'adminsettings.create',
    'edit' => 'adminsettings.edit',
    'store' => 'adminsettings.store',
    'update' => 'adminsettings.update',
    'destroy' => 'adminsettings.delete',
]);

Route::get('admin/settings/delete/{id}', 'Admin\SettingsController@delete');



//----------------------------Documents Controller----------------------

Route::resource('admin/documents', 'Admin\DocumentController')->names([
    'index' => 'admindocuments.index',
    'show' => 'admindocuments.show',
    'create' => 'admindocuments.create',
    'edit' => 'admindocuments.edit',
    'store' => 'admindocuments.store',
    'update' => 'admindocuments.update',
    'destroy' => 'admindocuments.delete',
]);

Route::get('admin/documents/delete/{id}', 'Admin\DocumentController@delete');



//----------------------------Invoice Controller----------------------

Route::resource('admin/invoices', 'Admin\InvoiceController')->names([
    'index' => 'admininvoices.index',
    'show' => 'admininvoices.show',
    'create' => 'admininvoices.create',
    'edit' => 'admininvoices.edit',
    'store' => 'admininvoices.store',
    'update' => 'admininvoices.update',
    'destroy' => 'admininvoices.delete',
]);

Route::get('admin/invoices/delete/{id}', 'Admin\InvoiceController@delete');

Route::get('admin/invoices/print/{id}', 'Admin\InvoiceController@printInvoice');



//----------------------------Forecast Controller----------------------

Route::resource('admin/forecast', 'Admin\ForecastController')->names([
    'index' => 'adminforecast.index',
    'show' => 'adminforecast.show',
    'create' => 'adminforecast.create',
    'edit' => 'adminforecast.edit',
    'store' => 'adminforecast.store',
    'update' => 'adminforecast.update',
    'destroy' => 'adminforecast.delete',
]);

Route::get('admin/forecast/delete/{id}', 'Admin\ForecastController@delete');

Route::get('admin/forecastEdit/{id}/{year}', 'Admin\ForecastController@forecastEdit');

Route::put('admin/forecastUpdate/{id}', 'Admin\ForecastController@forecastUpdate');



//----------------------------Mail Controller----------------------

Route::resource('admin/mails', 'Admin\MailController')->names([
    'index' => 'adminmails.index',
    'show' => 'adminmails.show',
    'create' => 'adminmails.create',
    'edit' => 'adminmails.edit',
    'store' => 'adminmails.store',
    'update' => 'adminmails.update',
    'destroy' => 'adminmails.delete',
]);

Route::get('admin/sentmails', 'Admin\MailController@sent');

Route::get('admin/mails/delete/{id}', 'Admin\MailController@delete');

Route::get('admin/mails/mailsend/{type}/{id}', 'Admin\MailController@mailSend');

Route::post('admin/mails/mailsendAction', 'Admin\MailController@mailSendAction');

Route::get('admin/mails/delete/{id}', 'Admin\MailController@deleteMail');

Route::get('admin/mails/deleteAll/{id}', 'Admin\MailController@deleteAll');

Route::get('admin/mails/trash/deletedmails', 'Admin\MailController@deletedMails');

Route::get('admin/mails/restore/{id}', 'Admin\MailController@restoreMail');

Route::get('admin/mails/restoreAll/{id}', 'Admin\MailController@restoreAll');

Route::get('admin/mails/inbox/get', 'Admin\AdminController@getAdminInbox');


//----------------------------Groups Controller----------------------

Route::resource('admin/groups', 'Admin\GroupController')->names([
    'index' => 'admingroups.index',
    'show' => 'admingroups.show',
    'create' => 'admingroups.create',
    'edit' => 'admingroups.edit',
    'store' => 'admingroups.store',
    'update' => 'admingroups.update',
    'destroy' => 'admingroups.delete',
]);

Route::get('admin/groups/assignusers/{gid}/{users}', 'Admin\GroupController@assignUsers');

Route::get('admin/groups/delete/{id}', 'Admin\GroupController@delete');

Route::get('admin/groups/allocate/{id}', 'Admin\GroupController@allocate');

Route::get('admin/groups/assign/{id}', 'Admin\GroupController@assign');



Route::get('admin/groups/users/{id}', 'Admin\GroupController@groupUsers');

Route::get('admin/groups/userleads/{gid}/{id}', 'Admin\GroupController@getGroupUserLeads');

Route::get('admin/groups/user/leads/{gid}/{id}', 'Admin\GroupController@getUserLeads');

Route::get('admin/groups/lead/{id}', 'Admin\GroupController@getLeadProfile');

// Allocate lead quota to group users -     allocateLeadsQuotatoGroupUser

Route::put('admin/allocateleadsquotatogroupusers/{id}', 'Admin\GroupController@allocateLeadsQuotatoGroupUsers');

Route::post('admin/assignleadstogroupusersbyquota', 'Admin\GroupController@assignLeadstoGroupUsersbyQuota');



//Route::get('admin/assignleadstogroupusers/{id}', 'Admin\GroupController@assignLeadstoGroupUsers');

//Route::post('admin/assignleadstouserbyquota', 'Admin\GroupController@assignLeadstoUserbyQuota');

//----------------------------Account Type Controller----------------------

Route::resource('admin/accounttypes', 'Admin\AccountTypeController')->names([
    'index' => 'adminaccounttypes.index',
    'show' => 'adminaccounttypes.show',
    'create' => 'adminaccounttypes.create',
    'edit' => 'adminaccounttypes.edit',
    'store' => 'adminaccounttypes.store',
    'update' => 'adminaccounttypes.update',
    'destroy' => 'adminaccounttypes.delete',
]);

Route::get('admin/accounttypes/delete/{id}', 'Admin\AccountTypeController@delete');



//----------------------------User Types Controller----------------------

Route::resource('admin/usertypes', 'Admin\UsertypeController')->names([
    'index' => 'adminusertypes.index',
    'show' => 'adminusertypes.show',
    'create' => 'adminusertypes.create',
    'edit' => 'adminusertypes.edit',
    'store' => 'adminusertypes.store',
    'update' => 'adminusertypes.update',
    'destroy' => 'adminusertypes.delete',
]);

Route::get('admin/usertypes/delete/{id}', 'Admin\UsertypeController@delete');



//----------------------------Industry Type Controller----------------------

Route::resource('admin/industrytypes', 'Admin\IndustryTypeController')->names([
    'index' => 'adminindustrytypes.index',
    'show' => 'adminindustrytypes.show',
    'create' => 'adminindustrytypes.create',
    'edit' => 'adminindustrytypes.edit',
    'store' => 'adminindustrytypes.store',
    'update' => 'adminindustrytypes.update',
    'destroy' => 'adminindustrytypes.delete',
]);

Route::get('admin/industrytypes/delete/{id}', 'Admin\IndustryTypeController@delete');



//----------------------------Lead Status Controller----------------------

Route::resource('admin/leadstatus', 'Admin\LeadStatusController')->names([
    'index' => 'adminleadstatus.index',
    'show' => 'adminleadstatus.show',
    'create' => 'adminleadstatus.create',
    'edit' => 'adminleadstatus.edit',
    'store' => 'adminleadstatus.store',
    'update' => 'adminleadstatus.update',
    'destroy' => 'adminleadstatus.delete',
]);

Route::get('admin/leadstatus/delete/{id}', 'Admin\LeadStatusController@delete');



//----------------------------Lead Source Controller----------------------

Route::resource('admin/leadsource', 'Admin\LeadSourceController')->names([
    'index' => 'adminleadsource.index',
    'show' => 'adminleadsource.show',
    'create' => 'adminleadsource.create',
    'edit' => 'adminleadsource.edit',
    'store' => 'adminleadsource.store',
    'update' => 'adminleadsource.update',
    'destroy' => 'adminleadsource.delete',
]);

Route::get('admin/leadsource/delete/{id}', 'Admin\LeadSourceController@delete');



//----------------------------Deal Stage Controller----------------------

Route::resource('admin/dealstage', 'Admin\DealStageController')->names([
    'index' => 'admindealstage.index',
    'show' => 'admindealstage.show',
    'create' => 'admindealstage.create',
    'edit' => 'admindealstage.edit',
    'store' => 'admindealstage.store',
    'update' => 'admindealstage.update',
    'destroy' => 'admindealstage.delete',
]);

Route::get('admin/dealstage/delete/{id}', 'Admin\DealStageController@delete');



//----------------------------RD Types Controller----------------------

Route::resource('admin/rdtypes', 'Admin\RdtypeController')->names([
    'index' => 'adminrdtypes.index',
    'show' => 'adminrdtypes.show',
    'create' => 'adminrdtypes.create',
    'edit' => 'adminrdtypes.edit',
    'store' => 'adminrdtypes.store',
    'update' => 'adminrdtypes.update',
    'destroy' => 'adminrdtypes.delete',
]);

Route::get('admin/rdtypes/delete/{id}', 'Admin\RdtypeController@delete');



//----------------------------RD Priority Controller----------------------

Route::resource('admin/rdprioritys', 'Admin\RdpriorityController')->names([
    'index' => 'adminrdprioritys.index',
    'show' => 'adminrdprioritys.show',
    'create' => 'adminrdprioritys.create',
    'edit' => 'adminrdprioritys.edit',
    'store' => 'adminrdprioritys.store',
    'update' => 'adminrdprioritys.update',
    'destroy' => 'adminrdprioritys.delete',
]);

Route::get('admin/rdprioritys/delete/{id}', 'Admin\RdpriorityController@delete');



//----------------------------RD Trends Controller----------------------

Route::resource('admin/rdtrends', 'Admin\RdtrendController')->names([
    'index' => 'adminrdtrends.index',
    'show' => 'adminrdtrends.show',
    'create' => 'adminrdtrends.create',
    'edit' => 'adminrdtrends.edit',
    'store' => 'adminrdtrends.store',
    'update' => 'adminrdtrends.update',
    'destroy' => 'adminrdtrends.delete',
]);

Route::get('admin/rdtrends/delete/{id}', 'Admin\RdtrendController@delete');



//----------------------------Product Category Controller----------------------

Route::resource('admin/productcategorys', 'Admin\ProductCategoryController')->names([
    'index' => 'adminproductcategory.index',
    'show' => 'adminproductcategory.show',
    'create' => 'adminproductcategory.create',
    'edit' => 'adminproductcategory.edit',
    'store' => 'adminproductcategory.store',
    'update' => 'adminproductcategory.update',
    'destroy' => 'adminproductcategory.delete',
]);

Route::get('admin/productcategorys/delete/{id}', 'Admin\ProductCategoryController@delete');


//----------------------------Product Sub Category Controller----------------------

Route::resource('admin/productsubcategorys', 'Admin\ProductSubCategoryController');

Route::get('admin/productsubcategorys/delete/{id}', 'Admin\ProductSubCategoryController@delete');

Route::get('admin/productsubcategorys/import/{type}', 'Admin\ProductSubCategoryController@import');

Route::post('admin/productsubcategorys/importData', 'Admin\ProductSubCategoryController@importData');

//----------------------------Email Template Controller----------------------

Route::resource('admin/emailtemplates', 'Admin\EmailTemplateController');

Route::get('admin/emailtemplates/delete/{id}', 'Admin\EmailTemplateController@delete');



//----------------------------Email Template Controller----------------------

Route::resource('admin/emailcategory', 'Admin\EmailCategoryController');

Route::get('admin/emailcategory/delete/{id}', 'Admin\EmailCategoryController@delete');



//----------------------------Emails Controller----------------------

Route::resource('admin/emails', 'Admin\EmailController');

Route::get('admin/emails/delete/{id}', 'Admin\EmailController@delete');



//----------------------------Images Controller----------------------

Route::resource('admin/images', 'Admin\ImagesController');

Route::get('admin/images/delete/{id}', 'Admin\ImagesController@delete');



//----------------------------Country Controller----------------------

Route::resource('admin/country', 'Admin\CountryController');

Route::get('admin/country/delete/{id}', 'Admin\CountryController@delete');



//----------------------------State Controller----------------------

Route::resource('admin/states', 'Admin\StateController');

Route::get('admin/states/delete/{id}', 'Admin\StateController@delete');

Route::get('admin/states/getCountryStates/{country}', 'Admin\StateController@getCountryStates');



//----------------------------Currency Controller----------------------

Route::resource('admin/currency', 'Admin\CurrencyController');

Route::get('admin/currency/delete/{id}', 'Admin\CurrencyController@delete');



//----------------------------Department Controller----------------------

Route::resource('admin/department', 'Admin\DepartmentController');

Route::get('admin/department/delete/{id}', 'Admin\DepartmentController@delete');



//----------------------------Unit Controller----------------------

Route::resource('admin/units', 'Admin\UnitController');

Route::get('admin/units/delete/{id}', 'Admin\UnitController@delete');



//----------------------------Deal Types Controller----------------------

Route::resource('admin/dealtypes', 'Admin\DealTypeController');

Route::get('admin/dealtypes/delete/{id}', 'Admin\DealTypeController@delete');



//----------------------------Payment Status Controller----------------------

Route::resource('admin/paymentstatus', 'Admin\PaymentStatusController');

Route::get('admin/paymentstatus/delete/{id}', 'Admin\PaymentStatusController@delete');



//----------------------------Project Status Controller----------------------

Route::resource('admin/projectstatus', 'Admin\ProjectStatusController');

Route::get('admin/projectstatus/delete/{id}', 'Admin\ProjectStatusController@delete');



//----------------------------Roles Controller----------------------

Route::resource('admin/roles', 'Admin\RoleController');

Route::get('admin/roles/delete/{id}', 'Admin\RoleController@delete');



//----------------------------Permissions Controller----------------------

Route::resource('admin/permissions', 'Admin\PermissionController');

Route::get('admin/permissions/delete/{id}', 'Admin\PermissionController@delete');



//----------------------------File Category----------------------

Route::resource('admin/filecategory', 'Admin\FileCategoryController');

Route::get('admin/filecategory/delete/{id}', 'Admin\FileCategoryController@delete');



//----------------------------File Manager Controller----------------------

Route::resource('admin/files', 'Admin\FileManagerController')->names([
    'index' => 'adminfiles.index',
    'show' => 'adminfiles.show',
    'create' => 'adminfiles.create',
    'edit' => 'adminfiles.edit',
    'store' => 'adminfiles.store',
    'update' => 'adminfiles.update',
    'destroy' => 'adminfiles.delete',
]);

Route::get('admin/files/delete/{id}', 'Admin\FileManagerController@delete');



// -----------------------------Admin Reports Controller-------------------------------------------

Route::get('admin/reports/webtolead', 'Admin\ReportsController@webtolead');



Route::get('admin/reports/getDayFormleads', 'Admin\ReportsController@getDayFormleads');

Route::post('admin/reports/getDayFormleads', 'Admin\ReportsController@getDayFormleads');



Route::get('admin/reports/getWeekFormleads', 'Admin\ReportsController@getWeekFormleads');

Route::post('admin/reports/getWeekFormleads', 'Admin\ReportsController@getWeekFormleads');



Route::get('admin/reports/getMonthFormleads', 'Admin\ReportsController@getMonthFormleads');

Route::post('admin/reports/getMonthFormleads', 'Admin\ReportsController@getMonthFormleads');



Route::get('admin/reports/getUserFormOptions', 'Admin\ReportsController@getUserFormOptions');

Route::post('admin/reports/getUserFormOptions', 'Admin\ReportsController@getUserFormOptions');



Route::get('admin/reports/leads', 'Admin\ReportsController@leads');

Route::get('admin/reports/getDayLeads', 'Admin\ReportsController@getDayLeads');

Route::post('admin/reports/getDayLeads', 'Admin\ReportsController@getDayLeads');



Route::get('admin/reports/getMonthLeads', 'Admin\ReportsController@getMonthLeads');

Route::post('admin/reports/getMonthLeads', 'Admin\ReportsController@getMonthLeads');



Route::get('admin/reports/getWeekLeads', 'Admin\ReportsController@getWeekLeads');

Route::post('admin/reports/getWeekLeads', 'Admin\ReportsController@getWeekLeads');



Route::get('admin/reports/getYearLeads', 'Admin\ReportsController@getYearLeads');

Route::post('admin/reports/getYearLeads', 'Admin\ReportsController@getYearLeads');



Route::get('admin/reports/accounts', 'Admin\ReportsController@accounts');

Route::get('admin/reports/getDayAccounts', 'Admin\ReportsController@getDayAccounts');

Route::post('admin/reports/getDayAccounts', 'Admin\ReportsController@getDayAccounts');



Route::get('admin/reports/getMonthAccounts', 'Admin\ReportsController@getMonthAccounts');

Route::post('admin/reports/getMonthAccounts', 'Admin\ReportsController@getMonthAccounts');



Route::get('admin/reports/getWeekAccounts', 'Admin\ReportsController@getWeekAccounts');

Route::post('admin/reports/getWeekAccounts', 'Admin\ReportsController@getWeekAccounts');



Route::get('admin/reports/getYearAccounts', 'Admin\ReportsController@getYearAccounts');

Route::post('admin/reports/getYearAccounts', 'Admin\ReportsController@getYearAccounts');



Route::get('admin/reports/contacts', 'Admin\ReportsController@contacts');

Route::get('admin/reports/getDayContacts', 'Admin\ReportsController@getDayContacts');

Route::post('admin/reports/getDayContacts', 'Admin\ReportsController@getDayContacts');



Route::get('admin/reports/getMonthContacts', 'Admin\ReportsController@getMonthContacts');

Route::post('admin/reports/getMonthContacts', 'Admin\ReportsController@getMonthContacts');



Route::get('admin/reports/getWeekContacts', 'Admin\ReportsController@getWeekContacts');

Route::post('admin/reports/getWeekContacts', 'Admin\ReportsController@getWeekContacts');



Route::get('admin/reports/getYearContacts', 'Admin\ReportsController@getYearContacts');

Route::post('admin/reports/getYearContacts', 'Admin\ReportsController@getYearContacts');



Route::get('admin/reports/deals', 'Admin\ReportsController@deals');

Route::get('admin/reports/getDayDeals/{time}/{form_id}/{uid}', 'Admin\ReportsController@getDayDeals');

Route::post('admin/reports/getDayDeals/{time}/{form_id}/{uid}', 'Admin\ReportsController@getDayDeals');



Route::get('admin/reports/getMonthDeals/{time}/{form_id}/{uid}', 'Admin\ReportsController@getMonthDeals');

Route::post('admin/reports/getMonthDeals/{time}/{form_id}/{uid}', 'Admin\ReportsController@getMonthDeals');



Route::get('admin/reports/getWeekDeals/{time}/{form_id}/{uid}', 'Admin\ReportsController@getWeekDeals');

Route::post('admin/reports/getWeekDeals/{time}/{form_id}/{uid}', 'Admin\ReportsController@getWeekDeals');



Route::get('admin/reports/getYearDeals/{time}/{form_id}/{uid}', 'Admin\ReportsController@getYearDeals');

Route::post('admin/reports/getYearDeals/{time}/{form_id}/{uid}', 'Admin\ReportsController@getYearDeals');



Route::get('admin/reports/customers', 'Admin\ReportsController@customers');

Route::get('admin/reports/getDayCustomers', 'Admin\ReportsController@getDayCustomers');

Route::post('admin/reports/getDayCustomers', 'Admin\ReportsController@getDayCustomers');



Route::get('admin/reports/getMonthCustomers', 'Admin\ReportsController@getMonthCustomers');

Route::post('admin/reports/getMonthCustomers', 'Admin\ReportsController@getMonthCustomers');



Route::get('admin/reports/getWeekCustomers', 'Admin\ReportsController@getWeekCustomers');

Route::post('admin/reports/getWeekCustomers', 'Admin\ReportsController@getWeekCustomers');



Route::get('admin/reports/getYearCustomers', 'Admin\ReportsController@getYearCustomers');

Route::post('admin/reports/getYearCustomers', 'Admin\ReportsController@getYearCustomers');



Route::get('admin/reports/sales', 'Admin\ReportsController@sales');

Route::get('admin/reports/getDaySales', 'Admin\ReportsController@getDaySales');

Route::post('admin/reports/getDaySales', 'Admin\ReportsController@getDaySales');



Route::get('admin/reports/getMonthSales', 'Admin\ReportsController@getMonthSales');

Route::post('admin/reports/getMonthSales', 'Admin\ReportsController@getMonthSales');



Route::get('admin/reports/getWeekSales', 'Admin\ReportsController@getWeekSales');

Route::post('admin/reports/getWeekSales', 'Admin\ReportsController@getWeekSales');



Route::get('admin/reports/users', 'Admin\ReportsController@users');

Route::get('admin/reports/getDayUsers', 'Admin\ReportsController@getDayUsers');

Route::post('admin/reports/getDayUsers', 'Admin\ReportsController@getDayUsers');



Route::get('admin/reports/getMonthUsers', 'Admin\ReportsController@getMonthUsers');

Route::post('admin/reports/getMonthUsers', 'Admin\ReportsController@getMonthUsers');



Route::get('admin/reports/getWeekUsers', 'Admin\ReportsController@getWeekUsers');

Route::post('admin/reports/getWeekUsers', 'Admin\ReportsController@getWeekUsers');



Route::get('admin/reports/getDaySubUsers', 'Admin\ReportsController@getDaySubUsers');

Route::post('admin/reports/getDaySubUsers', 'Admin\ReportsController@getDaySubUsers');



Route::get('admin/reports/getMonthSubUsers', 'Admin\ReportsController@getMonthSubUsers');

Route::post('admin/reports/getMonthSubUsers', 'Admin\ReportsController@getMonthSubUsers');



Route::get('admin/reports/getWeekSubUsers', 'Admin\ReportsController@getWeekSubUsers');

Route::post('admin/reports/getWeekSubUsers', 'Admin\ReportsController@getWeekSubUsers');


Route::get('admin/reports/productleads', 'Admin\ReportsController@productleads');
Route::get('admin/reports/products', 'Admin\ReportsController@products');
Route::get('admin/reports/companies', 'Admin\ReportsController@companies');

Route::get('admin/reports/getdayproductleads', 'Admin\ReportsController@getDayProductLeads');
Route::get('admin/reports/getmonthproductleads', 'Admin\ReportsController@getMonthProductLeads');
Route::get('admin/reports/getweekproductleads', 'Admin\ReportsController@getWeekProductLeads');
Route::get('admin/reports/getyearproductleads', 'Admin\ReportsController@getYearProductLeads');

Route::get('admin/reports/getdayproducts', 'Admin\ReportsController@getDayProducts');
Route::get('admin/reports/getmonthproducts', 'Admin\ReportsController@getMonthProducts');
Route::get('admin/reports/getweekproducts', 'Admin\ReportsController@getWeekProducts');
Route::get('admin/reports/getyearproducts', 'Admin\ReportsController@getYearProducts');

Route::get('admin/reports/getdaycompanies', 'Admin\ReportsController@getDayCompanies');
Route::get('admin/reports/getmonthcompanies', 'Admin\ReportsController@getMonthCompanies');
Route::get('admin/reports/getweekcompanies', 'Admin\ReportsController@getWeekCompanies');
Route::get('admin/reports/getyearcompanies', 'Admin\ReportsController@getYearCompanies');

//----------------------------Admin Trash Controller----------------------

Route::get('admin/trash', 'Admin\TrashController@index');

Route::get('admin/trash/accounts', 'Admin\TrashController@accounts');

Route::get('admin/trash/leads', 'Admin\TrashController@leads');

Route::get('admin/trash/contacts', 'Admin\TrashController@contacts');

Route::get('admin/trash/deals', 'Admin\TrashController@deals');

Route::get('admin/trash/products', 'Admin\TrashController@products');

Route::get('admin/trash/territory', 'Admin\TrashController@territory');

Route::get('admin/trash/subusers', 'Admin\TrashController@subusers');

Route::get('admin/trash/documents', 'Admin\TrashController@documents');

Route::get('admin/trash/forms', 'Admin\TrashController@forms');

Route::get('admin/trash/formleads', 'Admin\TrashController@formleads');

Route::get('admin/trash/events', 'Admin\TrashController@events');

Route::get('admin/trash/invoices', 'Admin\TrashController@invoices');

Route::get('admin/trash/currency', 'Admin\TrashController@currencys');

Route::get('admin/trash/restore/accounts/{id}', 'Admin\TrashController@restoreAccounts');

Route::get('admin/trash/restore/contacts/{id}', 'Admin\TrashController@restoreContacts');

Route::get('admin/trash/restore/leads/{id}', 'Admin\TrashController@restoreLeads');

Route::get('admin/trash/restore/prolead/{id}', 'Admin\TrashController@restoreProductleads');

Route::get('admin/trash/restore/deals/{id}', 'Admin\TrashController@restoreDeals');

Route::get('admin/trash/restore/forms/{id}', 'Admin\TrashController@restoreForms');

Route::get('admin/trash/restore/formleads/{id}/{form_id}', 'Admin\TrashController@restoreFormleads');

Route::get('admin/trash/restore/products/{id}', 'Admin\TrashController@restoreProducts');

Route::get('admin/trash/restore/documents/{id}', 'Admin\TrashController@restoreDocuments');

Route::get('admin/trash/restore/invoice/{id}', 'Admin\TrashController@restoreInvoice');

Route::get('admin/trash/restore/events/{id}', 'Admin\TrashController@restoreEvents');

Route::get('admin/trash/restore/territory/{id}', 'Admin\TrashController@restoreTerritory');

Route::get('admin/trash/restore/currency/{id}', 'Admin\TrashController@restoreCurrency');


Route::get('admin/trash/getuseraccounts', 'Admin\TrashController@getUserAccounts');

Route::get('admin/trash/getuserleads', 'Admin\TrashController@getUserLeads');

Route::get('admin/trash/productleads', 'Admin\TrashController@getProductLeads');


Route::get('admin/trash/getusercontacts', 'Admin\TrashController@getUserContacts');

Route::get('admin/trash/getuserdeals', 'Admin\TrashController@getUserDeals');

Route::get('admin/trash/getuserforms', 'Admin\TrashController@getUserFroms');

Route::get('admin/trash/getuserformleads', 'Admin\TrashController@getUserFromleads');

Route::get('admin/trash/getuserproducts', 'Admin\TrashController@getUserProducts');

Route::get('admin/trash/getuserdocuments', 'Admin\TrashController@getUserDocuments');

Route::get('admin/trash/getuserevents', 'Admin\TrashController@getUserEvents');

Route::get('admin/trash/getuserterritory', 'Admin\TrashController@getUserTerritory');

Route::get('admin/trash/getuserinvoices', 'Admin\TrashController@getUserInvoices');

Route::get('admin/trash/getuserproleads/{uid}', 'Admin\TrashController@getUserProductLeadsAjax');


//  Extentioins

Route::get('admin/extentions', 'Admin\AdminController@getExtentions');



//  Cron Jobs

Route::get('admin/dailyreports', 'Admin\DailyReportsController@index');

// Notifications

Route::resource('admin/notifications', 'Admin\NotificationController')->names([
    'index' => 'adminnotifications.index',
    'show' => 'adminnotifications.show',
    'create' => 'adminnotifications.create',
    'edit' => 'adminnotifications.edit',
    'store' => 'adminnotifications.store',
    'update' => 'adminnotifications.update',
    'destroy' => 'adminnotifications.delete',
]);

Route::get('admin/notifications/read/{id}', 'Admin\NotificationController@readNotification');

Route::get('admin/notifications/delete/{id}', 'Admin\NotificationController@delete');

Route::get('admin/notifications/unread/{aid}', 'Admin\NotificationController@getUnreadNotificationsList');

Route::get('admin/ajax/getlatestnotifications', 'Admin\AjaxController@getLatestnotifications');


// Ecommerce

Route::resource('admin/ecommerce', 'Admin\EcommerceController')->names([
    'index' => 'adminecommerce.index',
    'show' => 'adminecommerce.show',
    'create' => 'adminecommerce.create',
    'edit' => 'adminecommerce.edit',
    'store' => 'adminecommerce.store',
    'update' => 'adminecommerce.update',
    'destroy' => 'adminecommerce.delete',
]);
Route::get('admin/ecommerce/orders/{cid}', 'Admin\EcommerceController@getOrders');
Route::get('admin/ecommerce/ajax/orders/{cid}', 'Admin\EcommerceController@ajaxGetUserOrders');
Route::get('admin/ecommerce/consumers/list', 'Admin\EcommerceController@getConsumersList');
Route::get('admin/ecommerce/consumers/show/{cid}', 'Admin\EcommerceController@showConsumer');

//----------------------------Cron Job ----------------------

Route::get('admin/cronjob', 'Admin\AdminController@cronjob');


//  Search
Route::get('admin/search/getsearchresults', 'Admin\SearchController@getSearchData')->name('admin.search.getsearchresults');



//=====================================Task Management Routes--------------//

Route::namespace('Task')->group(function () {



    Route::resource('admin/tasks', 'TaskController');



    Route::resource('admin/outcomes', 'OutcomeController')->except([

        'show',

    ]);



    Route::resource('admin/tasktypes', 'TasktypeController')->except([

        'show',

    ]);;



    Route::put('admin/completed/{id}', 'TaskController@taskcompleted')->name('completed.update');



    #================================================UserSide Task Management===============



    Route::resource('taskmanagement', 'User\TaskController');
    Route::put('task/completed/{id}', 'User\TaskController@taskcompleted')->name('taskcompleted.update');
    Route::get('/delete-all-task', 'User\TaskController@deletealltask')->name('delete-all-task.task');

    Route::get('tasks/kanban/demo', 'User\TaskController@kanban')->name('tasks.kanban');
    Route::get('tasks/kanban/changedoutcomes/{todo_id}/{outcome_id}/{from_id}', 'User\TaskController@changedoutcomes');
});

#=================================================Report Section Task User Panel================#

Route::namespace('Task')->group(function () {
    Route::get('reports/tasks', 'User\ReportsController@tasks')->name('reports.tasks');
    Route::get('reports/getDaytasks/{time}/{form_id}', 'User\ReportsController@getDaytasks');
    Route::post('reports/getDaytasks/{time}/{form_id}', 'User\ReportsController@getDaytasks');
    Route::get('reports/getMonthtasks/{time}/{form_id}', 'User\ReportsController@getMonthtasks');
    Route::post('reports/getMonthtasks/{time}/{form_id}', 'User\ReportsController@getMonthtasks');
    Route::get('reports/getWeektasks/{time}/{form_id}', 'User\ReportsController@getWeektasks');
    Route::post('reports/getWeektasks/{time}/{form_id}', 'User\ReportsController@getWeektasks');
    #=================================================Report Section Task Admin Panel================#
    Route::get('admin/reports/tasks', 'ReportsController@tasks')->name('admin.tasks.reports');
    Route::get('admin/reports/getDaytasks/{time}/{form_id}/{user_id}', 'ReportsController@getDaytasks');
    Route::post('admin/reports/getDaytasks/{time}/{form_id}/{user_id}', 'ReportsController@getDaytasks');
    Route::get('admin/reports/getMonthtasks/{time}/{form_id}/{user_id}', 'ReportsController@getMonthtasks');
    Route::post('admin/reports/getMonthtasks/{time}/{form_id}/{user_id}', 'ReportsController@getMonthtasks');
    Route::get('admin/reports/getWeektasks/{time}/{form_id}/{user_id}', 'ReportsController@getWeektasks');
    Route::post('admin/reports/getWeektasks/{time}/{form_id}/{user_id}', 'ReportsController@getWeektasks');
});


#=============================================Ticketing==================#
Route::namespace('Ticket')->group(function () {
    Route::resource('tickets', 'user\TicketController');
    Route::get('/delete-all-tickets', 'user\TicketController@deletealltickets')->name('delete-all-tickets.tickets');
    Route::get('kanban/ticket/', 'user\TicketController@kanban')->name('tickets.kanban');
    Route::get('kanban/ticket/changedstatus/{ticket_id}/{status_id}/{from_id}', 'user\TicketController@changedstatus');

    #=================================================Report Section Tickets User Panel================#

    Route::get('reports/tickets', 'user\ReportsController@tickets')->name('reports.tickets');
    Route::get('reports/getDaytickets/{time}/{form_id}', 'user\ReportsController@getDaytickets');
    Route::post('reports/getDaytickets/{time}/{form_id}', 'user\ReportsController@getDaytickets');
    Route::get('reports/getMonthtickets/{time}/{form_id}', 'user\ReportsController@getMonthtickets');
    Route::post('reports/getMonthtickets/{time}/{form_id}', 'user\ReportsController@getMonthtickets');
    Route::get('reports/getWeektickets/{time}/{form_id}', 'user\ReportsController@getWeektickets');
    Route::post('reports/getWeektickets/{time}/{form_id}', 'user\ReportsController@getWeektickets');
    # close Tickets
    Route::put('tickets/closed/{ticketid}', 'user\TicketController@closed')->name('tickets.closed');
    // Admin tickets routes
    Route::prefix('admin')->group(function () {
        Route::get('ticket', 'admin\TicketController@index')->name('admin.ticket');
        Route::get('ticket/{ticket}', 'admin\TicketController@show')->name('admin.ticket.show');

        #=================================================Report Section Tickets Admin Panel================#
        Route::get('reports/tickets', 'admin\ReportsController@tickets')->name('admin.ticket.reports');
        Route::get('reports/getDaytickets/{time}/{form_id}/{user_id}', 'admin\ReportsController@getDaytickets');
        Route::post('reports/getDaytickets/{time}/{form_id}/{user_id}', 'admin\ReportsController@getDaytickets');
        Route::get('reports/getMonthtickets/{time}/{form_id}/{user_id}', 'admin\ReportsController@getMonthtickets');
        Route::post('reports/getMonthtickets/{time}/{form_id}/{user_id}', 'admin\ReportsController@getMonthtickets');
        Route::get('reports/getWeektickets/{time}/{form_id}/{user_id}', 'admin\ReportsController@getWeektickets');
        Route::post('reports/getWeektickets/{time}/{form_id}/{user_id}', 'admin\ReportsController@getWeektickets');

        #===================================settings Routes=======================#
        Route::resource('tickettype', 'settings\TickettypeController')->except(['show']);
        Route::resource('ticketstatus', 'settings\TicketstatusController')->except(['show']);
        Route::resource('priorities', 'settings\PriorityController')->except(['show']);
    });
});
Route::resource('comment', 'Comment\CommentController');
Route::get('admin/ticketsettings', 'Admin\SettingsController@ticketsettings')->name('ticket.settings');
Route::get('form-contacts', 'Ticket\GuestQueryTicketController@createticket')->name('createticket');
Route::post('openticket', 'Ticket\GuestQueryTicketController@openticket')->name('openticket.store');
Route::get('ticket/assign/{ticketid}/{userid}', 'Ticket\GuestQueryTicketController@assginticket');


#=======================Ecommerce==================#
//  consumers
Route::prefix('shop')->group(function () {

    Route::get('/', function () {
        return redirect('/');
    });

    // return redirect('crm');

    // Route::get('/', 'Consumer\CrudController@getProducts');

    // Route::get('/login', 'Consumer\LoginController@showLoginForm')->name('consumer.login');
    // Route::post('/login', 'Consumer\LoginController@login');

    // Route::get('/register', 'Consumer\RegisterController@showRegistrationForm')->name('consumer.register');
    // Route::post('/register', 'Consumer\RegisterController@register');

    // Route::post('/password/email', 'Consumer\ForgotPasswordController@sendResetLinkEmail')->name('consumer.password.email');
    // Route::get('/password/reset', 'Consumer\ForgotPasswordController@showLinkRequestForm')->name('consumer.password.request');
    // Route::post('/password/reset', 'Consumer\ResetPasswordController@reset');
    // Route::get('/password/reset/{token}', 'Consumer\ResetPasswordController@showResetForm')->name('consumer.password.reset');
    // Route::post('/logout', 'Consumer\ConsumerController@logout')->name('consumer.logout');
    // Route::get('/dashboard', 'Consumer\ConsumerController@index')->name('consumer.dashboard');
    // Route::get('/profile', 'Consumer\ConsumerController@profileView')->name('consumer.profile');
    // Route::get('/updateprofile', 'Consumer\ConsumerController@update');
    // Route::get('/profile/{id}', 'Consumer\ConsumerController@adminUpdate');
    // Route::put('/profile/{id}', 'Consumer\ConsumerController@adminUpdate');

    // Route::get('/ajax/getproducts/{skip}/{min_price}/{max_price}/{procatId}/{prosubcatId}/{keyword}/{sortby}', 'Consumer\AjaxController@ajaxGetProducts');

    // Route::get('/form/getproducts/', 'Consumer\AjaxController@formGetProducts');

    // // Route::get('/ajax/getproductdetails/{proid}', 'Consumer\AjaxController@ajaxGetProductDetails');
    // Route::get('/product/{slug}', 'Consumer\AjaxController@slugGetProductDetails');

    // Route::get('/product/feed/{slug}', 'Consumer\AjaxController@slugGetProductDetailsRssFeed');

    // // Route::get('/ajax/ajaxbuynow/{proId}', 'Consumer\AjaxController@ajaxBuyNow');
    // // Route::post('/ajax/ajaxbuynow/{proId}', 'Consumer\AjaxController@buyNowAction');
    // Route::get('/product/buynow/{slug}', 'Consumer\AjaxController@slugBuyNow');
    // Route::post('/product/buynow/{proId}', 'Consumer\AjaxController@buyNowAction');

    // Route::get('/ajax/ajaxaddtocart/{proId}', 'Consumer\AjaxController@ajaxAddtoCart');

    // Route::get('/ajax/buynow/{proId}', 'Consumer\ConsumerController@buyNowProduct');
    // Route::post('/ajax/buynow/{proId}', 'Consumer\ConsumerController@buyNowAction');

    // Route::get('/ajax/addtocart/{proId}', 'Consumer\ConsumerController@ajaxAddtoCart');

    // Route::get('/ajax/usercartproductscount/{userId}', 'Consumer\ConsumerController@ajaxUserCartProducts');

    // Route::get('/cart', 'Consumer\ConsumerController@getUserCartProductsList');

    // Route::get('/ajax/getstateoptions', 'Consumer\AjaxController@getStateoptions');

    // Route::get('/ajax/checkauth', 'Consumer\ConsumerController@checkAuth');

    // Route::post('/ajax/buynow/product/{proId}', 'Consumer\AjaxController@buyNowProduct');

    // //sitemap
    // // /ajax/sitemap/get

    // Route::get('/search/products/category/list', 'Consumer\AjaxController@getSearchProductCategoryList');
    // Route::get('/search/products/subcategory/list', 'Consumer\AjaxController@getSearchProductSubCategoryList');
    // Route::get('/search/products/tags/list', 'Consumer\AjaxController@getSearchProductTagsList');

    // Route::get('/search/products/category/{slug}', 'Consumer\AjaxController@getSearchProductCategory');
    // Route::get('/search/products/subcategory/{slug}', 'Consumer\AjaxController@getSearchProductSubCategory');
    // Route::get('/search/products/tags/{slug}', 'Consumer\AjaxController@getSearchProductTags');
    // Route::get('/search/products/company/{slug}', 'Consumer\AjaxController@getSearchProductCompany');

    // Route::get('/sitemap/products/category/{slug}', 'Consumer\AjaxController@getSiteMapProductCategory');
    // Route::get('/sitemap/products/subcategory/{slug}', 'Consumer\AjaxController@getSiteMapProductSubCategory');
    // Route::get('/sitemap/products', 'Consumer\AjaxController@getSiteMap');

    // Route::get('/ajax/getprosubcatoptions/{procatId}', 'Consumer\AjaxController@getProductSubCategoryOptions');
    // Route::get('/opengraph/fetch', 'Consumer\AjaxController@fetchOpenGraphMetadata');

    // Route::get('/sitemap/products/feed', 'Consumer\AjaxController@getRssfeedProducts');
    // Route::get('/sitemap/product/feed/{slug}', 'Consumer\AjaxController@getRssfeedProduct');
    // Route::get('/sitemap/productcategories/feed', 'Consumer\AjaxController@getRssfeedProductCategories');
    // Route::get('/sitemap/productcategory/feed/{slug}', 'Consumer\AjaxController@getRssfeedProductCategory');
    // Route::get('/sitemap/productsubcategory/feed/{slug}', 'Consumer\AjaxController@getRssfeedProductSubCategory');

    // Route::any('/ajax/searchproduct', 'Consumer\AjaxController@searchProduct')->name('shop.searchproduct');

    // Route::get('/search/product/{keyword}', 'Consumer\AjaxController@searchProductKeyword');

    // Route::get('/product/brands/list', 'Consumer\AjaxController@getProductBrandslist');

    // Route::get('/product/company/list', 'Consumer\AjaxController@getProductCompanylist');


    // // Route::get('sitemap.xml/article', 'SitemapController@articles');
    // // Route::get('sitemap.xml/category', 'SitemapController@categories');
});

// Route::feeds();
#=======================Client==================#

Route::prefix('clients')->group(function () {
    //  Log In
    Route::get('/', 'Client\LoginController@showLoginForm')->name('client.login');
    Route::post('/', 'Client\LoginController@login');

    //  Register
    Route::get('/register', 'Client\RegisterController@showRegistrationForm')->name('client.register');
    Route::post('/register', 'Client\RegisterController@register');
    Route::get('/verify/{token}', 'VerifyUserController@verifyClient');

    // Profile
    Route::get('/show/{id}', 'Client\ClientController@show');
    Route::get('/edit/{id}', 'Client\ClientController@clientEdit');
    Route::put('/update/{id}', 'Client\ClientController@clientUpdate');

    //  Log Out
    Route::post('/logout', 'Client\ClientController@logout')->name('client.logout');

    //  Forget and Reset Password
    Route::post('/password/email', 'Client\ForgotPasswordController@sendResetLinkEmail')->name('client.password.email');
    Route::get('/password/reset', 'Client\ForgotPasswordController@showLinkRequestForm')->name('client.password.request');
    Route::post('/password/reset', 'Client\ResetPasswordController@reset');
    Route::get('/password/reset/{token}', 'Client\ResetPasswordController@showResetForm')->name('client.password.reset');

    //  Dashboard
    Route::get('/dashboard', 'Client\ClientController@index')->name('client.dashboard');

    //  State Options
    Route::get('/ajax/getstateoptions', 'Client\ClientController@getStateoptions');
});

Route::group(['namespace' => 'Bookings'], function () {

    /* Booking Services routes */
    Route::resource('bookservices', 'BookingServiceController')->except(['show',]);

    Route::resource('activity_type', 'BookingActivityTypeController')->except(['show']);

    /* Booking Create Events routes*/
    Route::resource('bookevents', 'BookingEventController');
    Route::get('eventStatus', 'BookingEventController@eventStatus')->name('eventStatus');

    /* list of services at front side */
    Route::get('bookings', 'BookingController')->name('bookings');

    /**
     * These two below calendar route are not in used
     * calendar list
     */
    // Route::get('event_calendar', 'BookingEventController@allBookingEvent')->name('allBookingEvent');
    // Route::get('bookingeventdata', 'BookingEventController@calendarlist')->name('eventcalendardata');

    /* services having list of events show */
    Route::get('bookings/{bookservice:service_name}', 'BookingHomePageController@service_has_list_of_events')->name('service.events');

    /* guest user Booking form route */
    Route::get('bookings/{bookservice:service_name}/event/{bookevent}', 'BookingHomePageController@create')->name('event.create');
    Route::post('bookings/{bookservice:service_name}/event/{bookevent}', 'BookingHomePageController@store')->name('event.store');

    /** Booked Customer */
    Route::resource('confirmed_users', 'BookedCustomerController');

    /** Booking Reports */
    Route::get('reports/appointments', 'BookingReportController@appointment')->name('reports.appointments');
    Route::get('reports/getDayAppointment/{time}/{form_id}', 'BookingReportController@getDayAppointment');
    Route::post('reports/getDayAppointment/{time}/{form_id}', 'BookingReportController@getDayAppointment');
    Route::get('reports/getMonthAppointment/{time}/{form_id}', 'BookingReportController@getMonthAppointment');
    Route::post('reports/getMonthAppointment/{time}/{form_id}', 'BookingReportController@getMonthAppointment');
    Route::get('reports/getWeekAppointment/{time}/{form_id}', 'BookingReportController@getWeekAppointment');
    Route::post('reports/getWeekAppointment/{time}/{form_id}', 'BookingReportController@getWeekAppointment');

    /** send booking appointment link to friend */
    Route::resource('booking_shares', 'BookingShareController')->only([
        'store'
    ]);

    /** Admin Routes For appointment */
    Route::resource('admin/appointments', 'Admin\AdminBookingController')->only([
        'index', 'show'
    ]);

    /** admin panel report section */
    Route::get('admin/reports/appointments', 'Admin\BookingReportController@appointment')->name('admin.appointments');
    Route::get('admin/reports/getDayAppointment/{time}/{form_id}/{user_id}', 'Admin\BookingReportController@getDayAppointment');
    Route::post('admin/reports/getDayAppointment/{time}/{form_id}/{user_id}', 'Admin\BookingReportController@getDayAppointment');
    Route::get('admin/reports/getMonthAppointment/{time}/{form_id}/{user_id}', 'Admin\BookingReportController@getMonthAppointment');
    Route::post('admin/reports/getMonthAppointment/{time}/{form_id}/{user_id}', 'Admin\BookingReportController@getMonthAppointment');
    Route::get('admin/reports/getWeekAppointment/{time}/{form_id}/{user_id}', 'Admin\BookingReportController@getWeekAppointment');
    Route::post('admin/reports/getWeekAppointment/{time}/{form_id}/{user_id}', 'Admin\BookingReportController@getWeekAppointment');
});

#=======================Open Graph==================#
// Route::get('/opengraph/fetch', function () {
//     return OpenGraph::fetch('https://digitalcrm.com');
// });


#=======================Client==================#

// Route::prefix('cart')->group(function () {
//     //  Log In
//     Route::get('/', 'Cart\AuthController@loginForm')->name('cart.login');
//     Route::post('/', 'Cart\AuthController@loginAction');

//     Route::post('/logout', 'Cart\AuthController@logout')->name('cart.logout');


//     Route::get('/home', 'Cart\HomeController@index');
//     Route::get('/dashboard', 'Cart\HomeController@getDashboard');
//     Route::get('/products', 'Cart\ProductController@index');
// });

#=============================Companies===========================#
Route::resource('companies', 'Company\User\CompanyController');

#=============================RFQs user routes===========================#
Route::resource('rfq-forms', 'RFQ\user\RfqController');
Route::get('rfq/lists', 'RFQ\user\RfqController@lists')->name('lists.rfq');
Route::get('rfq/details/{details}', 'RFQ\user\RfqController@details')->name('details.rfq');
Route::resource('rfq-leads', 'RFQ\user\RfqLeadController');
#=============================Product Of Interest pois===========================#
Route::resource('pois', 'RFQ\user\PoiController');
Route::get('lists-product', 'RFQ\user\PoiController@listproducts')->name('product.interested');
#=============================RFQs admin routes===========================#
Route::resource('admin/rfqs', 'RFQ\admin\RfqController');
