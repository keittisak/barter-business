<?php

use Illuminate\Support\Facades\Route;

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
Route::get('policy',function(){
    return view('policy');
});

Route::get('test-line-notiry', 'TestController@testLineNotify');

Route::middleware(['access-log'])->group(function () {
    Route::domain(env('APP_URL', 'barteradvance.me'))->group(function () {
        Route::get('/', 'Front\HomeController@index')->name('front.home');
        Route::get('/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('logs');
        Route::get('about', 'Front\BranchController@about')->name('front.branch.about');
        Route::get('/telegram', 'Front\HomeController@telegram')->name('front.telegram');
    
        Route::get('category', 'Front\ShopController@category')->name('front.shops.category');
        Route::get('category/{id}/shops', 'Front\ShopController@index')->name('front.shops.category.show');
        Route::get('shops/{shop_id}', 'Front\ShopController@show')->name('front.shops.show');
    
        Route::get('users/sign_in', 'Front\LoginController@showLoginForm')->name('front.users.login.form');
        Route::post('users/sign_in', 'Front\LoginController@login')->name('front.users.login.process');
        Route::get('users/sing_out', 'Front\LoginController@logout')->name('front.users.logout.process');
    
        Route::get('users/sign_up', 'Front\RegisterController@form')->name('front.users.form');
        Route::post('users/sign_up', 'Front\RegisterController@store')->name('front.users.store');
    
        Route::post('users/search', 'Front\UserController@search')->name('front.users.search');
    
        Route::middleware(['auth'])->group(function () {
        // Route::middleware(['auth','is-customer','is-expire'])->group(function () {
            Route::get('users/profile', 'Front\UserController@profile')->name('front.users.profile');
            Route::get('users/profile/edit', 'Front\UserController@edit')->name('front.users.profile.edit');
            Route::put('users/profile/edit', 'Front\UserController@update')->name('front.users.profile.update');
            Route::put('users/profile/edit_password', 'Front\UserController@updatePassword')->name('front.users.profile.update.password');
            Route::put('users/profile/image_upload', 'Front\UserController@uploadImageProfile')->name('front.users.profile.image.upload');
    
            Route::get('users/shops', 'Front\UserController@shops')->name('front.users.shops.index');
            Route::get('users/shops/create', 'Front\ShopController@create')->name('front.users.shops.creaste');
            Route::post('users/shops/create', 'Front\ShopController@store')->name('front.users.shops.store');
            Route::get('users/shops/{id}/show', 'Front\UserController@shopShow')->name('front.users.shops.show');
            Route::get('users/shops/{id}/show', 'Front\UserController@shopShow')->name('front.users.shops.show');
            Route::get('users/shops/{id}/edit', 'Front\ShopController@edit')->name('front.users.shops.edit');
            Route::put('users/shops/{id}/update', 'Front\ShopController@update')->name('front.users.shops.update');
            Route::get('users/shops/search', 'Front\ShopController@search')->name('front.users.shops.search');
    
            // Route::get('users/shop', 'Front\UserController@shop')->name('front.users.shop');
            // Route::get('users/shops/create', 'Front\ShopController@create')->name('front.users.shops.creaste');
            // Route::post('users/shops/create', 'Front\ShopController@store')->name('front.users.shops.store');
            // Route::get('users/shops/edit', 'Front\ShopController@edit')->name('front.users.shops.edit');
            // Route::put('users/shops/edit', 'Front\ShopController@update')->name('front.users.shops.update');
            // Route::get('users/shops/search', 'Front\ShopController@search')->name('front.users.shops.search');
        
            // --PRODUCT-- //
            Route::get('users/shops/{shop_id}/products/create', 'Front\ProductController@create')->name('front.users.shops.products.create');
            Route::post('users/shops/{shop_id}/products/store', 'Front\ProductController@store')->name('front.users.shops.products.store');
            Route::get('users/shops/{shop_id}/products/{id}/edit', 'Front\ProductController@edit')->name('front.users.shops.products.edit');
            Route::put('users/shops/{shop_id}/products/{id}/update', 'Front\ProductController@update')->name('front.users.shops.products.update');
            Route::delete('users/shops/{shop_id}/products/{id}/delete', 'Front\ProductController@destroy')->name('front.users.products.delete');
    
            // Route::get('users/products', 'Front\ProductController@index')->name('front.users.products.index');
            // Route::get('users/products/create', 'Front\ProductController@create')->name('front.users.products.create');
            // Route::post('users/products/create', 'Front\ProductController@store')->name('front.users.products.store');
            // Route::get('users/products/{id}/edit', 'Front\ProductController@edit')->name('front.users.products.edit');
            // Route::put('users/products/{id}/edit', 'Front\ProductController@update')->name('front.users.products.update');
            // Route::delete('users/products/{id}/delete', 'Front\ProductController@destroy')->name('front.users.products.delete');
    
            // --REPORT-- //
            Route::get('users/reports/income', 'Front\ReportController@income')->name('front.users.reports.income');
            Route::get('users/reports/income/data', 'Front\ReportController@incomeData')->name('front.users.reports.income.data');
            Route::get('users/reports/sales', 'Front\ReportController@sales')->name('front.users.reports.sales');
            Route::get('users/reports/sales/data', 'Front\ReportController@salesData')->name('front.users.reports.sales.data');
            Route::get('users/reports/purchase', 'Front\ReportController@purchase')->name('front.users.reports.purchase');
            Route::get('users/reports/purchase/data', 'Front\ReportController@purchaseData')->name('front.users.reports.purchase.data');
            Route::get('users/reports/bbg', 'Front\ReportController@bbg')->name('front.users.reports.bbg');
    
            //--BENEFICAIRY--//
            Route::get('users/beneficiary/create', 'Front\BeneficiaryController@create')->name('front.users.beneficiary.create');
            Route::post('users/beneficiary/create', 'Front\BeneficiaryController@store')->name('front.users.beneficiary.store');
            Route::get('users/beneficiary/show', 'Front\BeneficiaryController@show')->name('front.users.beneficiary.show');
            Route::get('users/beneficiary/edit', 'Front\BeneficiaryController@edit')->name('front.users.beneficiary.edit');
            Route::put('users/beneficiary/update', 'Front\BeneficiaryController@update')->name('front.users.beneficiary.update');
    
            // --TRADE--//
            Route::get('users/trades', 'Front\TradeController@create')->name('front.users.trade.form');
            Route::post('users/trades', 'Front\TradeController@store')->name('front.users.trade.store');
            Route::get('users/trades/{id}/show/data', 'Front\TradeController@show')->name('front.users.trade.show');
            Route::get('users/trades/{id}/slip', 'Front\TradeController@slip')->name('front.users.trade.slip');
    
            // --INCOME--//
            Route::get('users/incomes/{id}', 'Front\TransactionController@incomeShow')->name('front.users.incomes.show');
    
            //--BILLING--//
            Route::get('billing', 'Front\BillingController@index')->name('front.billing.index');
            Route::get('billing/data', 'Front\BillingController@data')->name('front.billing.data');
            Route::get('billing/{id}/payment', 'Front\BillingController@paymentForm')->name('front.billing.payment.form');
            Route::put('billing/{id}/payment', 'Front\BillingController@update')->name('front.billing.update');

            //--AUCTIONS--//
            Route::get('auctions', 'Front\AuctionController@index')->name('front.auctions.index');
            Route::get('auctions/data', 'Front\AuctionController@data')->name('front.auctions.data');
            Route::get('auctions/winner-by-user', 'Front\AuctionController@winnerByUser')->name('front.auctions.winner-by-user');
            Route::get('auctions/{id}/show', 'Front\AuctionController@show')->name('front.auctions.show');
            Route::post('auctions/{id}/bidding', 'Front\AuctionController@bidding')->name('front.auctions.bidding');
            Route::get('auctions/{id}/get', 'Front\AuctionController@getAuction')->name('front.auctions.get');
    
            Route::get('recommended-members', 'Front\UserController@recommendedMember')->name('front.recommended-members');
    
        });
    
        Route::get('location/get_json', 'LocationController@genJson');
    });
    
    Route::domain(env('ADMIN_URL', 'admin.barteradvance.me'))->group(function () {
    // Route::group(['prefix' => 'admin'], function () {
        Route::get('/', function () {
            return redirect()->route('home');
        });
        Route::get('login', 'Admin\LoginController@form')->name('login.form');
        Route::post('login', 'Admin\LoginController@login')->name('login.process');
        Route::get('logout', 'Admin\LoginController@logout')->name('logout.process');
        
        Route::get('dashboard', 'Admin\DashboardController@index')->name('home')->middleware('check-permission:admin|sub_admin');

        Route::get('users', 'Admin\UserController@index')->name('users.index')->middleware('check-permission:admin|sub_admin');
        Route::get('users/data', 'Admin\UserController@data')->name('users.data')->middleware('check-permission:admin|sub_admin');
        Route::get('users/create', 'Admin\UserController@create')->name('users.create')->middleware('check-permission:admin|sub_admin');
        Route::post('users/create', 'Admin\UserController@store')->name('users.store')->middleware('check-permission:admin|sub_admin');
        Route::get('users/{id}/show', 'Admin\UserController@show')->name('users.show')->middleware('check-permission:admin|sub_admin');
        Route::get('users/{id}/edit', 'Admin\UserController@edit')->name('users.edit')->middleware('check-permission:admin|sub_admin');
        Route::put('users/{id}/edit', 'Admin\UserController@update')->name('users.update')->middleware('check-permission:admin|sub_admin');
        Route::put('users/{id}/update-password', 'Admin\UserController@updatePassword')->name('users.update-password')->middleware('check-permission:admin|sub_admin');
        Route::delete('users/{id}', 'Admin\UserController@destroy')->name('users.destroy')->middleware('check-permission:admin|sub_admin');
        Route::put('users/{id}/renew', 'Admin\UserController@renew')->name('users.renew')->middleware('check-permission:admin|sub_admin');

        Route::get('users/{user_id}/shops/create', 'Admin\ShopController@create')->name('users.shops.create')->middleware('check-permission:admin|sub_admin');
        Route::post('users/{user_id}/shops/store', 'Admin\ShopController@store')->name('users.shops.store')->middleware('check-permission:admin|sub_admin');
        Route::get('users/{user_id}/shops/{shop_id}/show', 'Admin\ShopController@show')->name('users.shops.show')->middleware('check-permission:admin|sub_admin');
        Route::get('users/{user_id}/shops/{shop_id}/edit', 'Admin\ShopController@edit')->name('users.shops.edit')->middleware('check-permission:admin|sub_admin');
        Route::put('users/{user_id}/shops/{shop_id}/update', 'Admin\ShopController@update')->name('users.shops.update')->middleware('check-permission:admin|sub_admin');

        Route::get('users/{user_id}/shops/{shop_id}/products/create', 'Admin\ProductController@create')->name('products.create')->middleware('check-permission:admin|sub_admin');
        Route::post('users/{user_id}/shops/{shop_id}/products/create', 'Admin\ProductController@store')->name('products.store')->middleware('check-permission:admin|sub_admin');
        Route::get('users/{user_id}/shops/{shop_id}/products/{id}/edit', 'Admin\ProductController@edit')->name('products.edit')->middleware('check-permission:admin|sub_admin');
        Route::put('users/{user_id}/shops/{shop_id}/products/{id}/edit', 'Admin\ProductController@update')->name('products.update')->middleware('check-permission:admin|sub_admin');
        Route::delete('users/{user_id}/shops/{shop_id}/products/{id}/delete', 'Admin\ProductController@destroy')->name('products.destroy')->middleware('check-permission:admin|sub_admin');

        // --MEMBER SHIP REQUEST-- //
        Route::get('membership-requests', 'Admin\MembershipRequestController@index')->name('membership-requests.index')->middleware('check-permission:admin|sub_admin');
        Route::get('membership-requests/create', 'Admin\MembershipRequestController@create')->name('membership-requests.create')->middleware('check-permission:admin|sub_admin');
        Route::post('membership-requests/create', 'Admin\MembershipRequestController@store')->name('membership-requests.store')->middleware('check-permission:admin|sub_admin');
        Route::get('membership-requests/data', 'Admin\MembershipRequestController@data')->name('membership-requests.data')->middleware('check-permission:admin|sub_admin');
        Route::post('membership-requests/{id}/process', 'Admin\MembershipRequestController@process')->name('membership-requests.process')->middleware('check-permission:admin|sub_admin');
        Route::delete('membership-requests/{id}/destroy', 'Admin\MembershipRequestController@destroy')->name('membership-requests.destroy')->middleware('check-permission:admin|sub_admin');

        // --TRADE-- //
        Route::get('trades', 'Admin\TradeController@index')->name('trades.index')->middleware('check-permission:admin|sub_admin');
        Route::get('trades/data', 'Admin\TradeController@data')->name('trades.data')->middleware('check-permission:admin|sub_admin');
        Route::get('trades/create', 'Admin\TradeController@create')->name('trades.create')->middleware('check-permission:admin|sub_admin');
        Route::post('trades/store', 'Admin\TradeController@store')->name('trades.store')->middleware('check-permission:admin|sub_admin');
        Route::get('trades/purchases/data', 'Admin\TradeController@purchaeData')->name('trades.purchases.data')->middleware('check-permission:admin|sub_admin');
        Route::get('trades/selling/data', 'Admin\TradeController@salesData')->name('trades.sales.data')->middleware('check-permission:admin|sub_admin');
        Route::get('trades/report', 'Admin\TradeController@report')->name('trades.report')->middleware('check-permission:admin|sub_admin');
        Route::get('trades/report/data', 'Admin\TradeController@reportData')->name('trades.report.data')->middleware('check-permission:admin|sub_admin');

        Route::get('trades/{id}/show/data', 'Admin\TradeController@show')->name('trades.show')->middleware('check-permission:admin|sub_admin');
        Route::put('trades/{id}/cancel', 'Admin\TradeController@cancel')->name('trades.cancel')->middleware('check-permission:admin|sub_admin');

        //--BILLING--//
        Route::get('billing', 'Admin\BillingController@index')->name('billing.index')->middleware('check-permission:admin|sub_admin');
        Route::get('billing/data', 'Admin\BillingController@data')->name('billing.data')->middleware('check-permission:admin|sub_admin');
        Route::get('billing/create', 'Admin\BillingController@create')->name('billing.form')->middleware('check-permission:admin|sub_admin');
        Route::post('billing/create', 'Admin\BillingController@store')->name('billing.store')->middleware('check-permission:admin|sub_admin');
        Route::get('billing/{id}/show', 'Admin\BillingController@show')->name('billing.show')->middleware('check-permission:admin|sub_admin');
        Route::get('billing/{id}/edit', 'Admin\BillingController@edit')->name('billing.edit')->middleware('check-permission:admin|sub_admin');
        Route::put('billing/{id}/edit', 'Admin\BillingController@update')->name('billing.update')->middleware('check-permission:admin|sub_admin');
        Route::put('billing/{id}/status', 'Admin\BillingController@changeStatus')->name('billing.change-status')->middleware('check-permission:admin|sub_admin');
        Route::put('billing/{id}/cancel', 'Admin\IncomeController@cancel')->name('billing.cancel')->middleware('check-permission:admin|sub_admin');

        //--Credit--//
        Route::get('credits', 'Admin\CreditController@form')->name('credits.form')->middleware('check-permission:admin');
        Route::post('credits', 'Admin\CreditController@process')->name('credits.process')->middleware('check-permission:admin');

        // --INCOME-- //
        Route::get('incomes', 'Admin\IncomeController@index')->name('incomes.index')->middleware('check-permission:admin');
        Route::get('incomes/data', 'Admin\IncomeController@data')->name('incomes.data')->middleware('check-permission:admin');
        Route::get('incomes/create', 'Admin\IncomeController@create')->name('incomes.create')->middleware('check-permission:admin');
        Route::post('incomes/store', 'Admin\IncomeController@store')->name('incomes.store')->middleware('check-permission:admin');
        Route::get('incomes/{id}/show/data', 'Admin\IncomeController@show')->name('incomes.show')->middleware('check-permission:admin');
        Route::put('incomes/{id}/cancel', 'Admin\IncomeController@cancel')->name('incomes.cancel')->middleware('check-permission:admin');

        //--BRANCH--//
        Route::get('setting', 'Admin\BranchController@index')->name('branchs.index')->middleware('check-permission:admin');
        Route::put('setting/update', 'Admin\BranchController@update')->name('branchs.update')->middleware('check-permission:admin');
        Route::put('description', 'Admin\BranchController@descriptionUpdate')->name('branchs.description.update')->middleware('check-permission:admin');
        Route::get('branches/{id}/about', 'Admin\BranchController@about')->name('branches.about.index')->middleware('check-permission:admin');
        Route::post('branches/{id}/about/store', 'Admin\BranchController@aboutStore')->name('branches.about.store')->middleware('check-permission:admin');
        Route::put('branches/{id}/about/{key}/update', 'Admin\BranchController@aboutUpdate')->name('branches.about.update')->middleware('check-permission:admin');
        Route::delete('branches/{id}/about/{key}/delete', 'Admin\BranchController@aboutDelete')->name('branches.about.delete')->middleware('check-permission:admin');

        

        Route::get('locations/provinces', 'Admin\LocationController@province')->name('locations.provinces');
        Route::post('upload-image', 'Admin\ImageController@upload')->name('images.upload');

        //--Auction--//
        Route::get('auctions', 'Admin\AuctionController@index')->name('auctions.index')->middleware('check-permission:admin|sub_admin');
        Route::get('auctions/data', 'Admin\AuctionController@data')->name('auctions.data')->middleware('check-permission:admin|sub_admin');
        Route::get('auctions/create', 'Admin\AuctionController@create')->name('auctions.create')->middleware('check-permission:admin|sub_admin');
        Route::post('auctions/store', 'Admin\AuctionController@store')->name('auctions.store')->middleware('check-permission:admin|sub_admin');
        Route::get('auctions/{id}/show', 'Admin\AuctionController@show')->name('auctions.show')->middleware('check-permission:admin|sub_admin');
        Route::get('auctions/{id}/get','Admin\AuctionController@getAuction')->name('auctions.get')->middleware('check-permission:admin|sub_admin');
        Route::get('auctions/{id}/edit', 'Admin\AuctionController@edit')->name('auctions.edit')->middleware('check-permission:admin|sub_admin');
        Route::put('auctions/{id}/update', 'Admin\AuctionController@update')->name('auctions.update')->middleware('check-permission:admin|sub_admin');
        Route::delete('auctions/{id}/destroy', 'Admin\AuctionController@destroy')->name('auctions.delete')->middleware('check-permission:admin|sub_admin');
        Route::post('auctions/{id}/bidding', 'Admin\AuctionController@bidding')->name('auctions.bidding')->middleware('check-permission:admin|sub_admin');

        //--Auction Detail--//
        Route::get('auctions/details/data', 'Admin\AuctionDetailController@data')->name('auctions.details.data')->middleware('check-permission:admin');
        Route::post('auctions/details/store', 'Admin\AuctionDetailController@store')->name('auctions.details.store')->middleware('check-permission:admin');

        //--Branner--//
        Route::get('branners', 'Admin\BrannerController@index')->name('branners.index')->middleware('check-permission:admin');
        Route::get('branners/data', 'Admin\BrannerController@data')->name('branners.data')->middleware('check-permission:admin');
        Route::get('branners/create', 'Admin\BrannerController@create')->name('branners.create')->middleware('check-permission:admin');
        Route::post('branners/store', 'Admin\BrannerController@store')->name('branners.store')->middleware('check-permission:admin');
        Route::get('branners/{id}/edit', 'Admin\BrannerController@edit')->name('branners.edit')->middleware('check-permission:admin');
        Route::put('branners/{id}/update', 'Admin\BrannerController@update')->name('branners.update')->middleware('check-permission:admin');
        Route::delete('branners/{id}/destroy', 'Admin\BrannerController@destroy')->name('branners.delete')->middleware('check-permission:admin');

        //--ShopType--//
        Route::get('shop-types', 'Admin\ShopTypeController@index')->name('shop-types.index')->middleware('check-permission:admin');
        Route::get('shop-types/data', 'Admin\ShopTypeController@data')->name('shop-types.data')->middleware('check-permission:admin|sub_admin');
        Route::get('shop-types/create', 'Admin\ShopTypeController@create')->name('shop-types.create')->middleware('check-permission:admin');
        Route::post('shop-types/store', 'Admin\ShopTypeController@store')->name('shop-types.store')->middleware('check-permission:admin');
        Route::get('shop-types/{id}/edit', 'Admin\ShopTypeController@edit')->name('shop-types.edit')->middleware('check-permission:admin');
        Route::put('shop-types/{id}/update', 'Admin\ShopTypeController@update')->name('shop-types.update')->middleware('check-permission:admin');
        Route::delete('shop-types/{id}/destroy', 'Admin\ShopTypeController@destroy')->name('shop-types.delete')->middleware('check-permission:admin');

        // Route::get('make-billing','Admin\BillingController@makeBilling');

        Route::get('sms', 'Admin\SmsController@index')->name('sms.index')->middleware('check-permission:admin|sub_admin');
        Route::get('sms/data', 'Admin\SmsController@data')->name('sms.data')->middleware('check-permission:admin|sub_admin');
        Route::get('sms/form', 'Admin\SmsController@form')->name('sms.form')->middleware('check-permission:admin|sub_admin');
        Route::post('sms/sender', 'Admin\SmsController@sender')->name('sms.sender')->middleware('check-permission:admin|sub_admin');
        Route::get('sms/check-balance', 'Admin\SmsController@checkBalance')->name('sms.check-balance')->middleware('check-permission:admin|sub_admin');

        Route::get('return-point-balances', 'Admin\ReturnPointBalanceController@index')->name('return-point-balances.index')->middleware('check-permission:admin|sub_admin');
        Route::get('return-point-balances/data', 'Admin\ReturnPointBalanceController@data')->name('return-point-balances.data')->middleware('check-permission:admin|sub_admin');
        Route::get('return-point-balances/create', 'Admin\ReturnPointBalanceController@create')->name('return-point-balances.create')->middleware('check-permission:admin|sub_admin');
        Route::post('return-point-balances/create', 'Admin\ReturnPointBalanceController@store')->name('return-point-balances.store')->middleware('check-permission:admin|sub_admin');

    });
});


Route::get('shop-type-update-image', 'Front\ShopController@updateImage');
Route::get('/reset', function (){
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
});

Route::get('/artisan/storage', function() {
    $command = 'storage:link';
    $result = \Artisan::call($command);
    return \Artisan::output();
});


