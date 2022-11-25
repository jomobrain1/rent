<?php

use App\Models\Vehicle;
use Illuminate\Support\Facades\Route;

Route::get('/clear', function(){
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/







Route::namespace('Gateway')->prefix('ipn')->name('ipn.')->group(function () {
    Route::any('mpesa', 'MPESA\ProcessController@ipn')->name('MPESA');
    Route::any('mpesa_payment', 'MPESA\ProcessController@payment')->name('mpesa_payment');
    Route::post('paypal', 'Paypal\ProcessController@ipn')->name('Paypal');
    Route::get('paypal-sdk', 'PaypalSdk\ProcessController@ipn')->name('PaypalSdk');
    Route::post('perfect-money', 'PerfectMoney\ProcessController@ipn')->name('PerfectMoney');
    Route::post('stripe', 'Stripe\ProcessController@ipn')->name('Stripe');
    Route::post('stripe-js', 'StripeJs\ProcessController@ipn')->name('StripeJs');
    Route::post('stripe-v3', 'StripeV3\ProcessController@ipn')->name('StripeV3');
    Route::post('skrill', 'Skrill\ProcessController@ipn')->name('Skrill');
    Route::post('paytm', 'Paytm\ProcessController@ipn')->name('Paytm');
    Route::post('payeer', 'Payeer\ProcessController@ipn')->name('Payeer');
    Route::post('paystack', 'Paystack\ProcessController@ipn')->name('Paystack');
    Route::post('voguepay', 'Voguepay\ProcessController@ipn')->name('Voguepay');
    Route::get('flutterwave/{trx}/{type}', 'Flutterwave\ProcessController@ipn')->name('Flutterwave');
    Route::post('razorpay', 'Razorpay\ProcessController@ipn')->name('Razorpay');
    Route::post('instamojo', 'Instamojo\ProcessController@ipn')->name('Instamojo');
    Route::get('blockchain', 'Blockchain\ProcessController@ipn')->name('Blockchain');
    Route::get('blockio', 'Blockio\ProcessController@ipn')->name('Blockio');
    Route::post('coinpayments', 'Coinpayments\ProcessController@ipn')->name('Coinpayments');
    Route::post('coinpayments-fiat', 'Coinpayments_fiat\ProcessController@ipn')->name('CoinpaymentsFiat');
    Route::post('coingate', 'Coingate\ProcessController@ipn')->name('Coingate');
    Route::post('coinbase-commerce', 'CoinbaseCommerce\ProcessController@ipn')->name('CoinbaseCommerce');
    Route::get('mollie', 'Mollie\ProcessController@ipn')->name('Mollie');
    Route::post('cashmaal', 'Cashmaal\ProcessController@ipn')->name('Cashmaal');
});

// User Support Ticket
Route::prefix('ticket')->group(function () {
    Route::get('/', 'TicketController@supportTicket')->name('ticket');
    Route::get('/new', 'TicketController@openSupportTicket')->name('ticket.open');
    Route::post('/create', 'TicketController@storeSupportTicket')->name('ticket.store');
    Route::get('/view/{ticket}', 'TicketController@viewTicket')->name('ticket.view');
    Route::post('/reply/{ticket}', 'TicketController@replyTicket')->name('ticket.reply');
    Route::get('/download/{ticket}', 'TicketController@ticketDownload')->name('ticket.download');
});


/*
|--------------------------------------------------------------------------
| Start Admin Area
|--------------------------------------------------------------------------
*/

Route::namespace('Admin')->prefix('admin')->name('admin.')->group(function () {
    Route::namespace('Auth')->group(function () {
        Route::get('/', 'LoginController@showLoginForm')->name('login');
        Route::post('/', 'LoginController@login')->name('login');
        Route::get('logout', 'LoginController@logout')->name('logout');
        // Admin Password Reset
        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'ForgotPasswordController@sendResetCodeEmail');
        Route::post('password/verify-code', 'ForgotPasswordController@verifyCode')->name('password.verify.code');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset.form');
        Route::post('password/reset/change', 'ResetPasswordController@reset')->name('password.change');
    });

    Route::middleware('admin')->group(function () {
        Route::get('dashboard', 'AdminController@dashboard')->name('dashboard');
        Route::get('profile', 'AdminController@profile')->name('profile');
        Route::post('profile', 'AdminController@profileUpdate')->name('profile.update');
        Route::get('password', 'AdminController@password')->name('password');
        Route::post('password', 'AdminController@passwordUpdate')->name('password.update');

        //Notification
        Route::get('notifications','AdminController@notifications')->name('notifications');
        Route::get('notification/read/{id}','AdminController@notificationRead')->name('notification.read');
        Route::get('notifications/read-all','AdminController@readAll')->name('notifications.readAll');

        //Report Bugs
        Route::get('request-report','AdminController@requestReport')->name('request.report');
        Route::post('request-report','AdminController@reportSubmit');

        Route::get('system-info','AdminController@systemInfo')->name('system.info');

        // Hosts Manager
        Route::get('hosts', 'ManageHostsController@allHosts')->name('hosts.all');
        Route::get('hosts/active', 'ManageHostsController@activeHosts')->name('hosts.active');
        Route::get('hosts/banned', 'ManageHostsController@bannedHosts')->name('hosts.banned');
        Route::get('hosts/email-verified', 'ManageHostsController@emailVerifiedHosts')->name('hosts.email.verified');
        Route::get('hosts/email-unverified', 'ManageHostsController@emailUnverifiedHosts')->name('hosts.email.unverified');
        Route::get('hosts/sms-unverified', 'ManageHostsController@smsUnverifiedHosts')->name('hosts.sms.unverified');
        Route::get('hosts/sms-verified', 'ManageHostsController@smsVerifiedHosts')->name('hosts.sms.verified');
        Route::get('hosts/{scope}/search', 'ManageHostsController@search')->name('hosts.search');
        Route::get('hosts/detail/{id}', 'ManageHostsController@detail')->name('hosts.detail');
        Route::get('hosts/earned/{id}', 'ManageHostsController@deposits')->name('hosts.deposits');
        Route::post('hosts/update/{id}', 'ManageHostsController@update')->name('hosts.update');
        Route::get('hosts/wallet/{id}', 'ManageHostsController@wallet')->name('hosts.wallet');
        Route::post('hosts/pay_update/{id}', 'ManageHostsController@pay_update')->name('hosts.pay_update');



         //Login History
        Route::get('hosts/login/history/{id}', 'ManageHostsController@hostLoginHistory')->name('hosts.login.history.single');
        Route::get('hosts/login/{id}', 'ManageHostsController@login')->name('hosts.login');

        Route::get('hosts/send-email', 'ManageHostsController@showEmailAllForm')->name('hosts.email.all');
        Route::post('hosts/send-email', 'ManageHostsController@sendEmailAll')->name('hosts.email.send');
        Route::get('hosts/send-email/{id}', 'ManageHostsController@showEmailSingleForm')->name('hosts.email.single');
        Route::post('hosts/send-email/{id}', 'ManageHostsController@sendEmailSingle')->name('hosts.email.single');
        Route::get('hosts/email-log/{id}', 'ManageHostsController@emailLog')->name('hosts.email.log');
        Route::get('hosts/email-details/{id}', 'ManageHostsController@emailDetails')->name('hosts.email.details');



        // Users Manager
        Route::get('users', 'ManageUsersController@allUsers')->name('users.all');
        Route::get('users/active', 'ManageUsersController@activeUsers')->name('users.active');
        Route::get('users/banned', 'ManageUsersController@bannedUsers')->name('users.banned');
        Route::get('users/email-verified', 'ManageUsersController@emailVerifiedUsers')->name('users.email.verified');
        Route::get('users/email-unverified', 'ManageUsersController@emailUnverifiedUsers')->name('users.email.unverified');
        Route::get('users/sms-unverified', 'ManageUsersController@smsUnverifiedUsers')->name('users.sms.unverified');
        Route::get('users/sms-verified', 'ManageUsersController@smsVerifiedUsers')->name('users.sms.verified');
        Route::get('users/with-balance', 'ManageUsersController@usersWithBalance')->name('users.with.balance');

        Route::get('users/{scope}/search', 'ManageUsersController@search')->name('users.search');
        Route::get('user/detail/{id}', 'ManageUsersController@detail')->name('users.detail');
        Route::post('user/update/{id}', 'ManageUsersController@update')->name('users.update');
        Route::get('user/send-email/{id}', 'ManageUsersController@showEmailSingleForm')->name('users.email.single');
        Route::post('user/send-email/{id}', 'ManageUsersController@sendEmailSingle')->name('users.email.single');
        Route::get('user/login/{id}', 'ManageUsersController@login')->name('users.login');
        Route::get('user/payments/{id}', 'ManageUsersController@deposits')->name('users.deposits');
        Route::get('user/payments/via/{method}/{type?}/{userId}', 'ManageUsersController@depositViaMethod')->name('users.deposits.method');
        // Login History
        Route::get('users/login/history/{id}', 'ManageUsersController@userLoginHistory')->name('users.login.history.single');

        Route::get('users/send-email', 'ManageUsersController@showEmailAllForm')->name('users.email.all');
        Route::post('users/send-email', 'ManageUsersController@sendEmailAll')->name('users.email.send');
        Route::get('users/email-log/{id}', 'ManageUsersController@emailLog')->name('users.email.log');
        Route::get('users/email-details/{id}', 'ManageUsersController@emailDetails')->name('users.email.details');

        // Subscriber
        Route::get('subscriber', 'SubscriberController@index')->name('subscriber.index');
        Route::get('subscriber/send-email', 'SubscriberController@sendEmailForm')->name('subscriber.sendEmail');
        Route::post('subscriber/remove', 'SubscriberController@remove')->name('subscriber.remove');
        Route::post('subscriber/send-email', 'SubscriberController@sendEmail')->name('subscriber.sendEmail');

        // Brand
        Route::get('brands', 'BrandController@index')->name('brand.index');
        Route::post('brands', 'BrandController@store')->name('brand.store');
        Route::post('brands/{brand}', 'BrandController@update')->name('brand.update');
        Route::post('brands/status/{brand}', 'BrandController@status')->name('brand.status');

        // Type
        Route::get('types', 'TypeController@index')->name('type.index');
        Route::post('types', 'TypeController@store')->name('type.store');
        Route::post('types/{type}', 'TypeController@update')->name('type.update');
        Route::post('types/status/{type}', 'TypeController@status')->name('type.status');

        // Location
        Route::get('locations', 'LocationController@index')->name('location.index');
        Route::post('locations', 'LocationController@store')->name('location.store');
        Route::post('locations/{location}', 'LocationController@update')->name('location.update');
        Route::post('locations/status/{location}', 'LocationController@status')->name('location.status');

        // Seater
        Route::get('seaters', 'SeaterController@index')->name('seater.index');
        Route::post('seaters', 'SeaterController@store')->name('seater.store');
        Route::post('seaters/{seater}', 'SeaterController@update')->name('seater.update');
        Route::post('seaters/status/{seater}', 'SeaterController@status')->name('seater.status');

        // 6 s
        Route::get('vehicles', 'VehicleController@index')->name('vehicles.index');
        Route::get('vehicles/host/{id}', 'VehicleController@index')->name('vehicles.hosts');
        Route::get('vehicles/add', 'VehicleController@add')->name('vehicles.add');
        Route::post('vehicles/store', 'VehicleController@store')->name('vehicles.store');
        Route::get('vehicles/{id}', 'VehicleController@edit')->name('vehicles.edit');
        Route::post('vehicles/update/{id}', 'VehicleController@update')->name('vehicles.update');
        Route::post('vehicles/image/remove/{id}/{image}', 'VehicleController@deleteImage')->name('vehicles.image.delete');
        Route::post('vehicles/{id}/status', 'VehicleController@status')->name('vehicles.status');

        //Vehicle Booking Log
        Route::get('vehicles/booking/log', 'VehicleController@bookingLog')->name('vehicles.booking.log');
        Route::get('vehicles/booking/log/upcoming', 'VehicleController@upcomingBookingLog')->name('vehicles.booking.log.upcoming');
        Route::get('vehicles/booking/log/running', 'VehicleController@runningBookingLog')->name('vehicles.booking.log.running');
        Route::get('vehicles/booking/log/completed', 'VehicleController@completedBookingLog')->name('vehicles.booking.log.completed');
        Route::get('vehicles/booking/log/{id}', 'VehicleController@userBookingLog')->name('user.vehicles.booking.log');
        Route::get('vehicles/booking/log/{id}/upcoming', 'VehicleController@userUpcomingBookingLog')->name('user.vehicles.booking.log.upcoming');
        Route::get('vehicles/booking/log/{id}/running', 'VehicleController@userRunningBookingLog')->name('user.vehicles.booking.log.running');
        Route::get('vehicles/booking/log/{id}/completed', 'VehicleController@userCompletedBookingLog')->name('user.vehicles.booking.log.completed');
        Route::get('vehicles/host/booking/log/{id}', 'VehicleController@hostBookingLog')->name('host.vehicles.booking.log');
        Route::get('vehicles/host/booking/log/{id}/upcoming', 'VehicleController@hostUpcomingBookingLog')->name('host.vehicles.booking.log.upcoming');
        Route::get('vehicles/host/booking/log/{id}/running', 'VehicleController@hostRunningBookingLog')->name('host.vehicles.booking.log.running');
        Route::get('vehicles/host/booking/log/{id}/completed', 'VehicleController@hostCompletedBookingLog')->name('host.vehicles.booking.log.completed');

        // Plans
        Route::get('plans', 'PlanController@index')->name('plans.index');
        Route::get('plans/add', 'PlanController@add')->name('plans.add');
        Route::post('plans/store', 'PlanController@store')->name('plans.store');
        Route::get('plans/{id}', 'PlanController@edit')->name('plans.edit');
        Route::post('plans/update/{id}', 'PlanController@update')->name('plans.update');
        Route::post('plans/{id}/status', 'PlanController@status')->name('plans.status');

        //Plan Booking Log
        Route::get('plans/booking/log', 'PlanController@bookingLog')->name('plans.booking.log');
        Route::get('plans/booking/log/upcoming', 'PlanController@upcomingBookingLog')->name('plans.booking.log.upcoming');
        Route::get('plans/booking/log/running', 'PlanController@runningBookingLog')->name('plans.booking.log.running');
        Route::get('plans/booking/log/completed', 'PlanController@completedBookingLog')->name('plans.booking.log.completed');
        Route::get('plans/booking/log/{id}', 'PlanController@userBookingLog')->name('user.plans.booking.log');
        Route::get('plans/booking/log/{id}/upcoming', 'PlanController@userUpcomingBookingLog')->name('user.plans.booking.log.upcoming');
        Route::get('plans/booking/log/{id}/running', 'PlanController@userRunningBookingLog')->name('user.plans.booking.log.running');
        Route::get('plans/booking/log/{id}/completed', 'PlanController@userCompletedBookingLog')->name('user.plans.booking.log.completed');

        // Payment Gateway
        Route::name('gateway.')->prefix('gateway')->group(function(){
            // Automatic Gateway
            Route::get('automatic', 'GatewayController@index')->name('automatic.index');
            Route::get('automatic/edit/{alias}', 'GatewayController@edit')->name('automatic.edit');
            Route::post('automatic/update/{code}', 'GatewayController@update')->name('automatic.update');
            Route::post('automatic/remove/{code}', 'GatewayController@remove')->name('automatic.remove');
            Route::post('automatic/activate', 'GatewayController@activate')->name('automatic.activate');
            Route::post('automatic/deactivate', 'GatewayController@deactivate')->name('automatic.deactivate');


            // Manual Methods
            Route::get('manual', 'ManualGatewayController@index')->name('manual.index');
            Route::get('manual/new', 'ManualGatewayController@create')->name('manual.create');
            Route::post('manual/new', 'ManualGatewayController@store')->name('manual.store');
            Route::get('manual/edit/{alias}', 'ManualGatewayController@edit')->name('manual.edit');
            Route::post('manual/update/{id}', 'ManualGatewayController@update')->name('manual.update');
            Route::post('manual/activate', 'ManualGatewayController@activate')->name('manual.activate');
            Route::post('manual/deactivate', 'ManualGatewayController@deactivate')->name('manual.deactivate');
        });


        // Payment SYSTEM
        Route::name('deposit.')->prefix('deposit')->group(function(){
            Route::get('/', 'DepositController@deposit')->name('list');
            Route::get('pending', 'DepositController@pending')->name('pending');
            Route::get('rejected', 'DepositController@rejected')->name('rejected');
            Route::get('approved', 'DepositController@approved')->name('approved');
            Route::get('successful', 'DepositController@successful')->name('successful');
            Route::get('details/{id}', 'DepositController@details')->name('details');

            Route::post('reject', 'DepositController@reject')->name('reject');
            Route::post('approve', 'DepositController@approve')->name('approve');
            Route::get('via/{method}/{type?}', 'DepositController@depositViaMethod')->name('method');
            Route::get('/{scope}/search', 'DepositController@search')->name('search');
            Route::get('date-search/{scope}', 'DepositController@dateSearch')->name('dateSearch');

        });

        // WITHDRAWAL REQUESTS SYSTEM
        Route::name('requests.')->prefix('requests')->group(function(){
            Route::get('/', 'RequestsController@requests')->name('list');
            Route::get('pending', 'RequestsController@pending')->name('pending');
            Route::get('rejected', 'RequestsController@rejected')->name('rejected');
            Route::get('approved', 'RequestsController@approved')->name('approved');
            Route::get('successful', 'RequestsController@successful')->name('successful');
            Route::get('details/{id}', 'RequestsController@details')->name('details');

            Route::post('reject', 'RequestsController@reject')->name('reject');
            Route::post('approve', 'RequestsController@approve')->name('approve');
            Route::post('send', 'RequestsController@send')->name('send');
            Route::get('/{scope}/search', 'RequestsController@search')->name('search');
            Route::get('date-search/{scope}', 'RequestsController@dateSearch')->name('dateSearch');

        });

        // Report
        Route::get('report/login/history', 'ReportController@loginHistory')->name('report.login.history');
        Route::get('report/login/ipHistory/{ip}', 'ReportController@loginIpHistory')->name('report.login.ipHistory');
        Route::get('report/email/history', 'ReportController@emailHistory')->name('report.email.history');
        Route::get('report/host/login/history', 'ReportController@hostLoginHistory')->name('report.host.login.history');
        Route::get('report/host/login/ipHistory/{ip}', 'ReportController@hostLoginIpHistory')->name('report.host.login.ipHistory');


        // Admin Support
        Route::get('tickets', 'SupportTicketController@tickets')->name('ticket');
        Route::get('tickets/pending', 'SupportTicketController@pendingTicket')->name('ticket.pending');
        Route::get('tickets/closed', 'SupportTicketController@closedTicket')->name('ticket.closed');
        Route::get('tickets/answered', 'SupportTicketController@answeredTicket')->name('ticket.answered');
        Route::get('tickets/view/{id}', 'SupportTicketController@ticketReply')->name('ticket.view');
        Route::post('ticket/reply/{id}', 'SupportTicketController@ticketReplySend')->name('ticket.reply');
        Route::get('ticket/download/{ticket}', 'SupportTicketController@ticketDownload')->name('ticket.download');
        Route::post('ticket/delete', 'SupportTicketController@ticketDelete')->name('ticket.delete');


        // Language Manager
        Route::get('/language', 'LanguageController@langManage')->name('language.manage');
        Route::post('/language', 'LanguageController@langStore')->name('language.manage.store');
        Route::post('/language/delete/{id}', 'LanguageController@langDel')->name('language.manage.del');
        Route::post('/language/update/{id}', 'LanguageController@langUpdate')->name('language.manage.update');
        Route::get('/language/edit/{id}', 'LanguageController@langEdit')->name('language.key');
        Route::post('/language/import', 'LanguageController@langImport')->name('language.importLang');



        Route::post('language/store/key/{id}', 'LanguageController@storeLanguageJson')->name('language.store.key');
        Route::post('language/delete/key/{id}', 'LanguageController@deleteLanguageJson')->name('language.delete.key');
        Route::post('language/update/key/{id}', 'LanguageController@updateLanguageJson')->name('language.update.key');



        // General Setting
        Route::get('general-setting', 'GeneralSettingController@index')->name('setting.index');
        Route::post('general-setting', 'GeneralSettingController@update')->name('setting.update');
        Route::get('optimize', 'GeneralSettingController@optimize')->name('setting.optimize');

        // Logo-Icon
        Route::get('setting/logo-icon', 'GeneralSettingController@logoIcon')->name('setting.logo.icon');
        Route::post('setting/logo-icon', 'GeneralSettingController@logoIconUpdate')->name('setting.logo.icon');

        //Custom CSS
        Route::get('custom-css','GeneralSettingController@customCss')->name('setting.custom.css');
        Route::post('custom-css','GeneralSettingController@customCssSubmit');


        //Cookie
        Route::get('cookie','GeneralSettingController@cookie')->name('setting.cookie');
        Route::post('cookie','GeneralSettingController@cookieSubmit');


        // Plugin
        Route::get('extensions', 'ExtensionController@index')->name('extensions.index');
        Route::post('extensions/update/{id}', 'ExtensionController@update')->name('extensions.update');
        Route::post('extensions/activate', 'ExtensionController@activate')->name('extensions.activate');
        Route::post('extensions/deactivate', 'ExtensionController@deactivate')->name('extensions.deactivate');



        // Email Setting
        Route::get('email-template/global', 'EmailTemplateController@emailTemplate')->name('email.template.global');
        Route::post('email-template/global', 'EmailTemplateController@emailTemplateUpdate')->name('email.template.global');
        Route::get('email-template/setting', 'EmailTemplateController@emailSetting')->name('email.template.setting');
        Route::post('email-template/setting', 'EmailTemplateController@emailSettingUpdate')->name('email.template.setting');
        Route::get('email-template/index', 'EmailTemplateController@index')->name('email.template.index');
        Route::get('email-template/{id}/edit', 'EmailTemplateController@edit')->name('email.template.edit');
        Route::post('email-template/{id}/update', 'EmailTemplateController@update')->name('email.template.update');
        Route::post('email-template/send-test-mail', 'EmailTemplateController@sendTestMail')->name('email.template.test.mail');


        // SMS Setting
        Route::get('sms-template/global', 'SmsTemplateController@smsTemplate')->name('sms.template.global');
        Route::post('sms-template/global', 'SmsTemplateController@smsTemplateUpdate')->name('sms.template.global');
        Route::get('sms-template/setting','SmsTemplateController@smsSetting')->name('sms.templates.setting');
        Route::post('sms-template/setting', 'SmsTemplateController@smsSettingUpdate')->name('sms.template.setting');
        Route::get('sms-template/index', 'SmsTemplateController@index')->name('sms.template.index');
        Route::get('sms-template/edit/{id}', 'SmsTemplateController@edit')->name('sms.template.edit');
        Route::post('sms-template/update/{id}', 'SmsTemplateController@update')->name('sms.template.update');
        Route::post('email-template/send-test-sms', 'SmsTemplateController@sendTestSMS')->name('sms.template.test.sms');

        // SEO
        Route::get('seo', 'FrontendController@seoEdit')->name('seo');


        // Frontend
        Route::name('frontend.')->prefix('frontend')->group(function () {


            Route::get('templates', 'FrontendController@templates')->name('templates');
            Route::post('templates', 'FrontendController@templatesActive')->name('templates.active');


            Route::get('frontend-sections/{key}', 'FrontendController@frontendSections')->name('sections');
            Route::post('frontend-content/{key}', 'FrontendController@frontendContent')->name('sections.content');
            Route::get('frontend-element/{key}/{id?}', 'FrontendController@frontendElement')->name('sections.element');
            Route::post('remove', 'FrontendController@remove')->name('remove');

            // Page Builder
            Route::get('manage-pages', 'PageBuilderController@managePages')->name('manage.pages');
            Route::post('manage-pages', 'PageBuilderController@managePagesSave')->name('manage.pages.save');
            Route::post('manage-pages/update', 'PageBuilderController@managePagesUpdate')->name('manage.pages.update');
            Route::post('manage-pages/delete', 'PageBuilderController@managePagesDelete')->name('manage.pages.delete');
            Route::get('manage-section/{id}', 'PageBuilderController@manageSection')->name('manage.section');
            Route::post('manage-section/{id}', 'PageBuilderController@manageSectionUpdate')->name('manage.section.update');
        });
    });
});


/*
|---------------------------------------------------------------------------
|Start Host Area
|---------------------------------------------------------------------------
*/
Route::namespace('Host')->prefix('host')->name('host.')->group(function (){
    Route::namespace('Auth')->group(function () {
        Route::get('authorization', 'AuthorizationController@authorizeForm')->name('authorization');
        Route::get('/register', 'RegisterController@showRegistrationForm')->name('register');
        Route::post('/register', 'RegisterController@register')->middleware('regStatus');
        Route::post('check-mail', 'RegisterController@checkHost')->name('checkHost');
        Route::get('/login', 'LoginController@showLoginForm')->name('login');
        Route::post('/login', 'LoginController@login');
        Route::get('logout', 'LoginController@logout')->name('logout');
        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('password/email', 'ForgotPasswordController@sendResetCodeEmail')->name('password.email');
        Route::get('password/code-verify', 'ForgotPasswordController@codeVerify')->name('password.code.verify');
        Route::post('password/reset', 'ResetPasswordController@reset')->name('password.update');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('password/verify-code', 'ForgotPasswordController@verifyCode')->name('password.verify.code');

    });
    Route::middleware('host')->group(function () {
        Route::middleware(['checkHostStatus'])->group(function () {
            Route::get('dashboard', 'HostController@home')->name('dashboard');

            // Vehicle Management
            Route::get('vehicles', 'VehicleController@index')->name('vehicles.index');
            Route::get('vehicle/search', 'VehicleController@vehicleSearch')->name('vehicle.search');
            Route::get('vehicles/add', 'VehicleController@add')->name('vehicles.add');
            Route::post('vehicles/store', 'VehicleController@store')->name('vehicles.store');
            Route::get('vehicles/{id}', 'VehicleController@edit')->name('vehicles.edit');
            Route::post('vehicles/update/{id}', 'VehicleController@update')->name('vehicles.update');
            Route::post('vehicles/image/remove/{id}/{image}', 'VehicleController@deleteImage')->name('vehicles.image.delete');
            Route::post('vehicles/{id}/status', 'VehicleController@status')->name('vehicles.status');
            Route::get('vehicle/booking/log', 'HostController@vehicleBookingLog')->name('vehicles.booking.log');

            // Profile Management
            Route::get('profile-setting', 'HostController@profile')->name('profile.setting');
            Route::post('profile-setting', 'HostController@submitProfile');
            Route::get('change-password', 'HostController@changePassword')->name('change.password');
            Route::post('change-password', 'HostController@submitPassword');
            Route::get('pay-setting', 'HostController@payDetails')->name('pay.setting');
            Route::post('pay-setting', 'HostController@submitPayDetails');

            //Withdrawal Management
            Route::get('/requests', 'WalletController@requests')->name('requests');
            Route::post('/requests', 'WalletController@updateRequests');

        });
    });
});


/*
|--------------------------------------------------------------------------
| Start User Area
|--------------------------------------------------------------------------
*/


Route::name('user.')->group(function () {
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('/login', 'Auth\LoginController@login');
    Route::get('logout', 'Auth\LoginController@logout')->name('logout');

    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'Auth\RegisterController@register')->middleware('regStatus');
    Route::post('check-mail', 'Auth\RegisterController@checkUser')->name('checkUser');

    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetCodeEmail')->name('password.email');
    Route::get('password/code-verify', 'Auth\ForgotPasswordController@codeVerify')->name('password.code.verify');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/verify-code', 'Auth\ForgotPasswordController@verifyCode')->name('password.verify.code');
});

Route::name('user.')->prefix('user')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('authorization', 'AuthorizationController@authorizeForm')->name('authorization');
        Route::get('resend-verify', 'AuthorizationController@sendVerifyCode')->name('send.verify.code');
        Route::post('verify-email', 'AuthorizationController@emailVerification')->name('verify.email');
        Route::post('verify-sms', 'AuthorizationController@smsVerification')->name('verify.sms');
        Route::post('verify-g2fa', 'AuthorizationController@g2faVerification')->name('go2fa.verify');

        Route::middleware(['checkStatus'])->group(function () {
            Route::get('dashboard', 'UserController@home')->name('home');

            Route::get('profile-setting', 'UserController@profile')->name('profile.setting');
            Route::post('profile-setting', 'UserController@submitProfile');
            Route::get('change-password', 'UserController@changePassword')->name('change.password');
            Route::post('change-password', 'UserController@submitPassword');

            //2FA
            Route::get('twofactor', 'UserController@show2faForm')->name('twofactor');
            Route::post('twofactor/enable', 'UserController@create2fa')->name('twofactor.enable');
            Route::post('twofactor/disable', 'UserController@disable2fa')->name('twofactor.disable');


            // Payment
            Route::any('/payment', 'Gateway\PaymentController@deposit')->name('deposit');
            Route::post('payment/insert', 'Gateway\PaymentController@depositInsert')->name('deposit.insert');
            Route::get('payment/pending/{log_id}', 'Gateway\PaymentController@depositPending')->name('deposit.pending');
            Route::get('payment/preview', 'Gateway\PaymentController@depositPreview')->name('deposit.preview');
            Route::get('payment/confirm', 'Gateway\PaymentController@depositConfirm')->name('deposit.confirm');
            Route::get('payment/manual', 'Gateway\PaymentController@manualDepositConfirm')->name('deposit.manual.confirm');
            Route::post('payment/manual', 'Gateway\PaymentController@manualDepositUpdate')->name('deposit.manual.update');
            Route::get('payment/history', 'UserController@depositHistory')->name('deposit.history');

            Route::post('/rating/{vehicle_id}', 'UserController@rating')->name('rating');

            //Vehicle booking log
            Route::get('vehicle/booking/log', 'UserController@vehicleBookingLog')->name('vehicle.booking.log');
            Route::get('plan/booking/log', 'UserController@planBookingLog')->name('plan.booking.log');
        });
    });
});

Route::get('/contact', 'SiteController@contact')->name('contact');
Route::post('/contact', 'SiteController@contactSubmit');
Route::get('/change/{lang?}', 'SiteController@changeLanguage')->name('lang');

Route::post('subscribe', 'SiteController@subscribe')->name('subscribe');
Route::get('/cookie/accept', 'SiteController@cookieAccept')->name('cookie.accept');

Route::get('vehicles', 'VehicleController@vehicles')->name('vehicles');
Route::get('vehicle/details/{id}/{slug}', 'VehicleController@vehicleDetails')->name('vehicle.details');
Route::get('vehicle/booking/{id}/{slug}', 'VehicleController@vehicleBooking')->name('vehicle.booking');
Route::post('vehicle/booking/confirm/{id}', 'VehicleController@vehicleBookingConfirm')->name('vehicle.booking.confirm');
Route::get('vehicle/search', 'VehicleController@vehicleSearch')->name('vehicle.search');
Route::get('vehicle/search/brand/{brand_id}/{slug}', 'VehicleController@brandVehicles')->name('vehicle.brand');
Route::get('vehicle/search/type/{type_id}/{slug}', 'VehicleController@typeVehicles')->name('vehicle.type');
Route::get('vehicle/search/{seat_id}/seater', 'VehicleController@seaterVehicles')->name('vehicle.seater');

Route::get('plans', 'SiteController@plans')->name('plans');
Route::post('plan/{id}', 'SiteController@planBooking')->name('plan.booking');

Route::get('blogs', 'SiteController@blogs')->name('blogs');
Route::get('blog/{id}/{slug}', 'SiteController@blogDetails')->name('blog.details');
Route::get('policy/{id}/{slug}', 'SiteController@policyPages')->name('policy.pages');

Route::get('placeholder-image/{size}', 'SiteController@placeholderImage')->name('placeholder.image');


Route::get('/{slug}', 'SiteController@pages')->name('pages');
Route::get('/', 'SiteController@index')->name('home');
