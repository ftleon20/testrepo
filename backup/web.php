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

/*
 *  Set up locale and runtime_locale if other language is selected
 */
if (Request::server('HTTP_HOST') != config('app.admin_domain')) {
	config([
		'app.domain' => Request::server('HTTP_HOST'),
		'app.url' => Request::root(),
	]);
	// Change session domain if member domain is different from session domain 
	$domain = config('app.domain');
	if (strpos($domain, config('session.domain')) === false) {
		config(['session.domain' => $domain]);
	}

	// Check domain is for what currency and country, then display related CMS
	switch ($domain) {
		// local
		case 'yes8.test':
		case 'yes88.test':
		// staging
		case '77testbet.com':
		// Production
		case 'permainanhebat88.com': 
		case '77betindo.com': 
		case '77betgame.com': 
		case '77betidn.com': 
		case '77bet-idn.com': 
		case '77bet-indo.com': 
			$currency = System::$currency['IDR'];
			break;
		// local
		case '77betsgd.test':
		case '77bet-sgd.test':
		// staging
		case '77testsg.com':
		// Production
		case '77betsg.com':
			$currency = System::$currency['SGD'];
			break;
		default:
			$currency = System::$currency['IDR'];
			break;
	}
	config(['app.currency' => $currency]);
	View::share('currency', $currency);
	
	// It will be used to retrieve CMS & promotion content
	$country = System::$cms_currency_to_country[$currency] ?? 'id';
	config(['app.country' => $country]);
	View::share('country', $country);
		
	$locales = System::$country_locales[config('app.country')] ?? [];
	$primary_locale = reset($locales);
	
	if (in_array(Request::segment(1), $locales) && Request::segment(1) != $primary_locale) {
		App::setLocale(Request::segment(1));
		config(['app.runtime_locale' => Request::segment(1)]);
		config(['app.prefix_locale' => Request::segment(1)]);
	} else {
		App::setLocale($primary_locale);
		config(['app.runtime_locale' => $primary_locale]);
		config(['app.prefix_locale' => '']);
	}
}

$routes = function() {
	Auth::routes();

	Route::get('register/referer/{ref_code}', 'Auth\RegisterController@showRegistrationForm');
		
	Route::get('terms', 'Member\TermsConditionController@index');

	Route::get('/', 'Member\HomeController@index');
	Route::get('promotion', 'Member\PromotionController@index');
	Route::get('contact-us', 'Member\ContactUsController@index');
	Route::get('language', 'Member\LanguageController@index');

	Route::get('spin', 'Member\WheelSpinController@index');
	Route::post('campaign-gifts/{campaign_code}', 'Member\WheelSpinController@ajaxGetCampaignGifts');
	Route::post('demo-spin/{campaign_code}', 'Member\WheelSpinController@ajaxDemoSpin');
	Route::post('spin-wheel/{campaign_code}', 'Member\WheelSpinController@ajaxSpinWheel');

	Route::get('buku-mimpi', 'Member\BukuMimpiController@index');
	Route::get('vip', 'Member\VipController@index');

	Route::group(['middleware' => ['get.cms.banner']], function() {
		Route::get('list/{game_type}', 'Game\ListProductController@index');

		Route::get('{game_type}/hkbgaming', 'Game\ListHKBGamingController@index');
		Route::get('{game_type}/singapore-&-hk', 'Game\ListHKBGamingController@index');
		Route::get('{game_type}/dreamgame', 'Game\ListDreamGameController@index');
		Route::get('{game_type}/playtech', 'Game\ListPlayTechController@index');
		Route::get('{game_type}/ssport', 'Game\ListSSportsController@index');
		Route::get('{game_type}/sbo', 'Game\ListSBOController@index');
		Route::get('{game_type}/allbet', 'Game\ListAllBetController@index');
		Route::get('{game_type}/aggb', 'Game\ListAGGBController@index');
		Route::get('{game_type}/idnplay', 'Game\ListIDNPLAYController@index');
		Route::get('{game_type}/sexybcrt', 'Game\ListAWCController@index');
		// Route::get('{game_type}/pp', 'Game\ListAWCController@index');
		Route::get('{game_type}/spade', 'Game\ListAWCController@index'); 
		Route::get('{game_type}/mgp', 'Game\ListMGPController@index');
		Route::get('{game_type}/xprogaming', 'Game\ListAvengersXController@index');
		// Route::get('{game_type}/gameplay', 'Game\ListAvengersXController@index');
		Route::get('{game_type}/sagaming', 'Game\ListAvengersXController@index');
		Route::get('{game_type}/wmc', 'Game\ListAvengersXController@index');
		Route::get('{game_type}/evolution', 'Game\ListAvengersXController@index');
		Route::get('{game_type}/cmd', 'Game\ListCMDController@index');
		Route::get('{game_type}/jdb', 'Game\ListAWCController@index'); 
		Route::get('{game_type}/jdbfish', 'Game\ListAWCController@index'); 
		Route::get('{game_type}/rt', 'Game\ListAWCController@index');
		Route::get('{game_type}/kingmaker', 'Game\ListAWCController@index');  
		Route::get('{game_type}/ezugi', 'Game\ListEzugiController@index');  
		Route::get('{game_type}/gd', 'Game\ListGDController@index');
		Route::get('{game_type}/pragmaticplay', 'Game\ListPragmaticPlayController@index');
		Route::get('{game_type}/habanero', 'Game\ListHabaneroController@index'); 
		// Route::get('{game_type}/isin4d', 'Game\ListTrilionController@index'); 
	});

	Route::get('ssport/play', 'Game\SSportsController@index');
	Route::get('playtech/play/{code}', 'Game\PlayTechController@index');
	Route::get('allbet/play/{code}', 'Game\AllBetController@index');
	Route::get('dreamgame/play/{code?}', 'Game\DreamGameController@index');
	Route::get('hkbgaming/play/{code}', 'Game\HKBGamingController@index');
	Route::match(['get', 'post'], 'auth_sess', 'Game\HKBGamingAuthSessionController@index');
	Route::get('sbo/play/{code}', 'Game\SBOController@index');
	Route::get('allbet/play/{code}', 'Game\AllBetController@index');
	Route::get('aggb/play/{code}', 'Game\AGGBController@index');
	Route::get('idnplay/play/{code}', 'Game\IDNPLAYController@index');
	Route::get('awc/play/{code}', 'Game\AWCController@index');
	Route::get('mgp/play/{code}', 'Game\MGPController@index');
	Route::get('xprogaming/play/{game_type}', 'Game\AvengersXController@index');
	// Route::get('gameplay/play/{game_type}/{code?}', 'Game\AvengersXController@index');
	Route::get('sagaming/play/{game_type}', 'Game\AvengersXController@index');
	Route::get('ibc/play', 'Game\AvengersXController@index');
	Route::get('winningft/play', 'Game\AvengersXController@index');
	Route::get('cmd/play', 'Game\CMDController@index');
	Route::match(['get', 'post'], 'auth_cmd', 'Game\CMDAuthSessionController@index');
	Route::get('wmc/play/{game_type}', 'Game\AvengersXController@index');
	Route::get('evolution/play/{game_type}', 'Game\AvengersXController@index');
	Route::get('s128/play', 'Game\AvengersXController@index');
	Route::get('ezugi/play/{game_type}', 'Game\EzugiController@index');
	Route::get('gd/play/{game_type}', 'Game\GDController@index');
	Route::get('pragmaticplay/play/{game_type}', 'Game\PragmaticPlayController@index');
	Route::get('habanero/play/{game_type}', 'Game\HabaneroController@index');

	// Logged in
	Route::group(['middleware' => ['auth']], function() {
		// Register Thank You
		Route::get('register-thank-you', 'Member\RegisterThankYouController@index');

		// My Profile
		Route::get('myaccount', 'Member\AccountController@showMyAccountForm');
		Route::get('my-profile', 'Member\AccountController@showProfileForm');

		Route::post('my-profile', 'Member\AccountController@processUpdateProfile');
		Route::post('remove-bank', 'Member\RemoveBankController@removeBank');
		
		// Funds
		Route::get('funds', 'Member\FundsController@showFundsForm');
		
		// Change Password
		Route::get('change-password', 'Member\AccountController@showPasswordForm');
		Route::post('change-password', 'Member\AccountController@processChangePassword');
		
		// Referral
		Route::get('referral', 'Member\ReferralController@index');

		// Deposit
		Route::get('add-deposit', 'Member\AddDepositController@showAddDepositForm');
		Route::post('add-deposit', 'Member\AddDepositController@addDeposit');

		// Transfer
		Route::get('retrieve-wallet-balance', 'Member\TransferWalletController@retrieveWalletBalance');
		Route::get('transfer-wallet', 'Member\TransferWalletController@showTransferWalletForm');
		// Route::group(['middleware' => ['throttle:5,1']], function() {
			Route::post('transfer-wallet', 'Member\TransferWalletController@transferWallet');
			Route::post('transfer-all-wallet', 'Member\TransferWalletController@transferAllWallet');
		// });

		// User Bank
		Route::get('add-bank', 'Member\AddBankController@showAddBankForm');
		Route::post('add-bank', 'Member\AddBankController@addBank');

		// Withdrawal
		Route::get('add-withdrawal', 'Member\AddWithdrawalController@showAddWithdrawalForm');
		Route::post('add-withdrawal', 'Member\AddWithdrawalController@addWithdrawal');

		// History
		Route::get('history/{view?}', 'Member\HistoryController@showHistoryList');
	});

	// Route::get('mailable', function () {
	//     $invoice = App\Invoice::find(1);

	//     return new App\Mail\InvoicePaid($invoice);
	// });
};

foreach ([
	// Local fake domains
	'yes8-landing.test', 

	// Staging
	'landing.77testbet.com', 

	// Production
] as $domain) {
	Route::group([
		'domain' => $domain, 
		'middleware' => ['throttle:15,0.25']
	], function () {
		Route::get('/', 'Member\LandingPageController@index');
	});
}

foreach ([
	// Local fake domains
	'yes8.test', 
	'yes88.test', 
	'77betsgd.test',
	'77bet-sgd.test', 

	// Staging
	'y8tgl.com', 
	'77testbet.com', 
	'77testsg.com', 

	// Production
	'permainanhebat88.com', 
	'77betindo.com', 
	'77betgame.com', 
	'77betidn.com', 
	'77bet-idn.com', 
	'77bet-indo.com', 
	'77betsg.com',
] as $domain) {
	Route::group([
		'domain' => $domain, 
		'prefix' => config('app.prefix_locale'), 
		'middleware' => ['throttle:15,0.25', 'member.bootstrap', 'get.game.type.list']
	], $routes);
}
