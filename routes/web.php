<?php


	// Redirect Home Page to Login Page
	Route::get('/', function () {
	return redirect()->route('login');
	 });

	Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');



// Dashboard Routes

Route::middleware(['auth'])->group(function () {

	// Route::get('/dashboard', 'Dashboard@index')->name('dashboard');
	Route::get('/dashboard/{any?}', 'Dashboard@index')->name('dashboard');

	Route::get('/authuser', 'NavbarsController@authuser');
	Route::get('/navbar', 'NavbarsController@index');

	Route::get('/charges/checkUniqueCompany/{title}', 'ChargeController@checkUnique');
	Route::get('/charges/checkUniqueUser/{email}', 'RoleController@checkUnique');
	Route::get('/charges/charges', 'ChargeController@fetchall');
	Route::get('/charges/checkUniqueCompanySame/{id}/{title}', 'ChargeController@checkUniqueSame');


	/*
		USERS
	*/
	Route::get('/users', 'RoleController@getUsers');
	Route::get('/users/status/{id}', 'RoleController@updateStatus');
	Route::get('/users/role/{id}', 'RoleController@updateRole');
	Route::get('/users/remove/{id}', 'RoleController@removeUser');
	Route::post('/users/password', 'RoleController@updatePassword');

	/*
		CHARGE
	*/
	Route::post('/charges/editcharges/{id}', 'ChargeController@editcharges');
	Route::post('/role', 'RoleController@addUser');

	/*
		
	*/
	Route::resource('/charges', 'ChargeController');
	Route::post('/invoice/assign', 'InvoiceController@assign');
	Route::post('/membership/renewal/{id}', 'InvoiceController@makeRenewal');
	Route::resource('/invoice', 'InvoiceController');

	/*
		Accounts
	*/

	Route::get('/pettycash/category', 'AccountController@getCategory');
	Route::get('/cashbook/category', 'AccountController@getCashbookCategory');
	Route::post('/pettycash/category', 'AccountController@addCategory');
	Route::post('/cashbook/category', 'AccountController@addCashbookCategory');
	Route::get('/pettycash/checkUniqueCategory/{category}', 'AccountController@checkUnique');
	Route::get('/cashbook', 'AccountController@getCashbookIndex');
	Route::get('/pettycash', 'AccountController@getPettycashIndex');
	Route::delete('/pettycash/{id}', 'AccountController@removePettycash');
	Route::get('/cashbook/checkUniqueCategory/{category}', 'AccountController@cashbookCheckUnique');
	Route::get('/pettycash/expenses/{filter}', 'AccountController@getExpenses');
	Route::post('/pettycash/expense', 'AccountController@addExpenses');
	Route::post('/cashbook/update', 'AccountController@addCashbook');
	Route::get('/accounts/credit', 'AccountController@credit');
	Route::get('/accounts/debit', 'AccountController@debit');
	Route::get('/accounts/pettycredit', 'AccountController@pettycredit');
	Route::get('/accounts/pettydebit', 'AccountController@pettydebit');

	// For XLS Downoad
	Route::get('/cashbook/xls', 'AccountController@getxls');

	// For Ledger Report
	Route::get('/ledger', 'LedgerController@index');
	Route::get('/ledger/graph', 'LedgerController@getGraph');


	/*
		Businessmen ROUTES
	*/
	Route::middleware(['throttle:300,1'])->group(function () {

			Route::get('/businessmen/uniquecnic/{value}', 'BusinessmenController@checkCnic');
			Route::get('/businessmen/uniqueall/{value}', 'BusinessmenController@checkUniqueMobile');
			Route::get('/businessmen/unique/{value}/{field}', 'BusinessmenController@checkUnique');
 
	});

	Route::get('/businessmen/counthirarcy/{id}', 'BusinessmenController@countHirarcy');
	Route::get('/businessmen/fetchhirarcy/{id}', 'BusinessmenController@fetchHirarcy');
	Route::get('/businessmen/download/{id}', 'BusinessmenController@downloadDocs');
	Route::put('/businessmen/status/{id}', 'BusinessmenController@status');
	Route::get('/businessmen/customsearch', 'BusinessmenController@customData');
	Route::get('/businessmen/asyncsearch', 'BusinessmenController@asyncSearch');
	Route::get('/businessmen/business/{id}', 'BusinessmenController@fetchRelatedBusiness');
	Route::get('/businessmen/asyncsearchAdd', 'BusinessmenController@asyncSearchAdd');
	Route::get('/businessmen/groups', 'BusinessmenController@fetchGroups');
	Route::put('/businessmen/{id}', 'BusinessmenController@edit');
	Route::post('/businessmen/{id}', 'BusinessmenController@addHirarcy');
	Route::post('/businessmen', 'BusinessmenController@add');
	Route::get('/businessmen', 'BusinessmenController@index');
	Route::get('/payment/history/{id}', 'BusinessmenController@historyByBusinessmen');
	Route::get('/businessmen/certificate/{id}/{certificate}', 'BusinessmenController@printCertificate');


	/*
		Business ROUTES
	*/

	Route::middleware(['throttle:300,1'])->group(function () {

			Route::get('/business/unique/{value}/{field}', 'BusinessController@checkUnique');
			Route::get('/business/uniqueemails/{value}', 'BusinessController@checkUniqueEmails');
			Route::get('/business/editunique/{value}/{field}/{id}', 'BusinessController@editCheckUnique');
			Route::get('/business/group/{value}/{field}', 'BusinessController@uniqueGroup');

	});

	Route::put('/business/{id}/group', 'BusinessController@editGroup');
	Route::delete('/business/{id}/group', 'BusinessController@removeGroup');
	Route::post('/business/group', 'BusinessController@addGroup');
	Route::post('/business/email/{id}/{email}', 'BusinessController@addFoundation');
	Route::post('/business/email/add', 'BusinessController@addSingleFoundation');
	Route::post('/business/edit/{id}', 'BusinessController@editBusiness');
	Route::get('/business/partners/{id}', 'BusinessController@partners');
	Route::post('/business/partners/add', 'BusinessController@addNewpartner');
	Route::get('/business/hirarcy/{id}', 'BusinessController@fetchDocsOnly');
	Route::get('/business/docs/{id}', 'BusinessController@docs');
	Route::delete('/business/docs/{id}', 'BusinessController@Removedocs');
	Route::get('/business/download/{id}', 'BusinessController@downloadDocs');
	Route::post('/business/editDesignation', 'BusinessController@editDesignation');
	Route::delete('/business/hirarcy', 'BusinessController@removeHirarcyDoc');
	Route::post('/business', 'BusinessController@add');
	Route::get('/business', 'BusinessController@index');





});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
