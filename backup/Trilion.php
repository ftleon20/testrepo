<?php

namespace App\Products;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use App\UserProductAPI;
use App\Bet;
use App\Product;
use App\ProductGame;
use App\ProductGameAPI;
use App\ProductTransaction;
use App\Promotion;
use App\UserPromotion;
use App\UserWallet;
use App\UserWalletHistory;
use App\ManualAdjustment;
use App\Helper;
use App\System;
use Carbon\Carbon;
use Jenssegers\Agent\Agent;

class Trilion extends Model
{

    /**
     * Group system currency to one currency of lower rate
     * Use lower rate currency to pay lesser for the loyalty to the provider
     * 
     * @var array
     */
    public static $currency_group = [
        'IDR' => [
            'IDR',
        ],
        'CNY' => [
            'SGD',
        ],
    ];

    /**
     * Isin4D currency symbol list .
     * 
     * @var array
     */
    public static $trilion_isin4d_currency_list = [
        'IDR' => 'IDR',
        'SGD' => 'SGD',
    ];

    /**
     * Isin4D currency symbol with decimal unit list.
     * 
     * @var array
     */
    public static $currency_decimal_unit = [
        'IDR' => 1000,
    ];

    /**
     * Game Types
     * 
     * @var array
     */
    public static $game_type = [
        'lottery' => [
            'ISIN' => 'Isin 4D Lotttery',
        ],
    ];
    
    /**
     * max length for generating account id 
     * 
     * @var string
     */
    public static $apiAccountLength = 12;
    
    /**
     * max length for generating transaction id 
     * 
     * @var string
     */
    public static $apiTxCodeLength = 30;
    
    /**
     * api returned successful status code
     * 
     * @var string
     */
    public static $apiSuccessCode = '0';
    
    /**
     * trilion isin4d timezone for generting timestamp (GMT-5) 
     * 
     * @var string
     */
	public static $apiTimezone = '-5';

    /**
     * Create a new class instance.
     *
     * @return void
     */
	public function __construct() 
	{
        $this->platform = [
            'ISIN4D' => Product::$provider_wallet['trilion_isin4d'],
        ];
        
		$this->product_wallets = [
			Product::$provider_wallet['trilion_isin4d'],
		];
	}

    /**
     * Trilion create account. http://[api-url]/api/v2/Player/CreatePlayer/[MerchantCode]
     * 
     * @var UserProductAPI $api_account Object of UserProductAPI
     */
    public static function create_player_acc($transaction = [], $product = []) {

		$client = static::request_configuration();
		
        do {
            $accountId = strtoupper(Helper::random_str(static::$apiUsernameLength));
			$usernameUsed = DB::table('user_product_api')
								->join('products', 'user_product_api.product_id', '=', 'products.id')
								->where('products.id', $product['id'])
								->where('api_username', $accountId)
								->exists();
        } while ($usernameUsed);
        
        $api_currency = '';
        foreach (static::$currency_group as $api_curr => $group) {
            if (in_array($transaction['currency'], $group)) {
                $api_currency = $api_curr;
                break;
            }
        }

        $timestamp = gmdate("Y-m-d\TH:i:s",strtotime($apiTimezone." hours"));

        $api_url = config("app.trilion_isin4d_api_url").'/api/v2/Player/CreatePlayer/';// noted: missing merchant code
        $api_params = [
	        'AccountId' => $accountId,
	        'Currency' => $api_currency,
	        'Timestamp' => $timestamp,
	        'Checksum' => md5($accountId.'.'.$api_currency.'.'.$timestamp.'.salt'),
        ];
        print_r($api_params);die;

        // $data = static::call_api($client, $api_url, $api_params);
        
        // API success
        if (isset($data['ErrorCode']) && $data['ErrorCode'] == static::$apiSuccessCode) {
            $api_account = UserProductAPI::create([
                'user_id' => $transaction['user_id'],
                'product_id' => $product['id'],
                'user_currency' => $transaction['currency'],
                'api_currency' => $api_currency,
                'api_username' => $username,
			]);
        } else {
            $stack = [__CLASS__, __FUNCTION__, __FILE__, __LINE__];
            System::error_email("[Trilion Isin4d] API response not success ({$api_url})", $api_params, $data, $stack);
        }
        
        return $api_account;
    }

    /**
     * Get member balance. http://[api-url]/api/v2/Player/CheckWalletBalance/[MerchantCode]/[ProductType]
     * 
     * @var float $balance Balance of API side
     */
    public static function get_balance($currency = '', UserProductAPI $api_account){
        $client = static::request_configuration();

        $timestamp = gmdate("Y-m-d\TH:i:s",strtotime(static::$apiTimezone." hours"));
        $checksum = md5($api_account->api_username.'.'.$currency.'.'.$timestamp.'.salt', true);

        $api_url = config("app.trilion_isin4d_api_url").'/api/v2/Player/CheckWalletBalance/';// noted: MerchantCode and Product type
        $api_params = [
	        'AccountId' => $api_account->api_username,
	        'Currency' => $currency,
	        'Timestamp' => $timestamp,
	        'Checksum' => base64_encode($checksum),
        ];
        
        $balance = 0;
        // $data = static::call_api($client, $api_url, $api_params);
        
        // API success
        if (isset($data['ErrorCode']) && $data['ErrorCode'] == static::$apiSuccessCode) {
            $balance = $data['Data']['WalletBalance']['GameBalance'] ?? 0;

            if (in_array($api_account->api_currency, array_keys(static::$currency_decimal_unit))) {
                $balance *= static::$currency_decimal_unit[$api_account->api_currency];
            }
	    } else {
            $stack = [__CLASS__, __FUNCTION__, __FILE__, __LINE__];
            System::error_email("[Trilion Isin4D] API response not success ({$api_url})", $api_params, $data, $stack);
        }

        return $balance;
    } 

    /**
     * Trilion Isin4D start game.
     * 
     * @var string $api_account Game url
     */
    public static function login_game(UserProductAPI $api_account, $currency = '', $game_info = []) {
		$client = static::request_configuration();

    	$agentObj = new Agent();
    	$isMobile = 'true';
        if($agentObj->isDesktop()) {
        	$isMobile = 'false';
        }
		switch (App::getlocale()) {
			case 'cn':
				$lang = 'zh-CN';
				break;
			case 'en':
				$lang = 'en-US';
				break;
			case 'id':
				$lang = 'id-ID';
				break;
			default:
				$lang = 'en-US';
				break;
        }

        $api_url = config("app.avengersx_api_url").'/api/StartGame';
        $api_params = [
	        'Operator' => config("app.avengersx_".$currency."_operator"),
	        'Product' => $game_info['Product'],
	        'Username' => $api_account->api_username,
	        'Category' => $game_info['Category'],
	        'IsMobile' => $isMobile,
	        'Language' => $lang,
	        'Game' => $game_info['Game'] ?? '',
	        'ReturnUrl' => Helper::url(),
        ];
        $api_params['Token'] = static::generate_token($currency, $api_params);
        
        // $data = static::call_api($client, $api_url, $api_params);
	
        // API success
        if (isset($data['ErrorCode']) && $data['ErrorCode'] == static::$apiSuccessCode) {
            $url = $data['Url'];
        } else {
            $stack = [__CLASS__, __FUNCTION__, __FILE__, __LINE__];
            System::error_email("[AvengersX] API response not success ({$api_url})", $api_params, $data, $stack);
        }

        return $url ?? '';
    }

    /**
     * Trilion Isin4D generate transactionID for deposit/withdraw.
     * 
     * @var ProductTransaction $productTransaction Object of ProductTransaction
     */
    public static function generate_txCode($transaction = [], $product = []) {
        do {
            $api_transaction_id = Helper::random_str(static::$apiTxCodeLength);

            $trans_id_used = DB::table('product_transactions')
                                ->join('products', 'product_transactions.product_id', '=', 'products.id')
                                ->where('products.wallet', $product['wallet'])
                                ->where('api_transaction_id', $api_transaction_id) 
                                ->where('product_transactions.status', ProductTransaction::$status['success']) 
                                ->exists();
        } while ($trans_id_used);

        // Generated api_transaction_id is unique, proceed to add record with it
        $productTransaction = ProductTransaction::create([
            'product_id' => $product['id'],
            'transaction_type' => $transaction['transaction_type'],
            'transaction_id' => $transaction['id'],
            'api_transaction_id' => $api_transaction_id,
            'status' => ProductTransaction::$status['pending'],
        ]);

        return $productTransaction;
    }

    /**
     * Trilion Isin4D deposit.
     * 
     * @var ProductTransaction $productTransaction Object of ProductTransaction
     */
    public static function deposit($transaction = [], $product = [], UserProductAPI $api_account) {
        $productTransaction = static::transfer_balance($method_name = 'WalletDeposit', $transaction, $product, $api_account);

        return $productTransaction;
    }

    /**
     * Trilion Isin4D withdraw.
     * 
     * @var ProductTransaction $productTransaction Object of ProductTransaction
     */
    public static function withdraw($transaction = [], $product = [], UserProductAPI $api_account) {
        $productTransaction = static::transfer_balance($method_name = 'WalletWithdraw', $transaction, $product, $api_account);

        return $productTransaction;
    }

    /**
     * Trilion Isin4D transfer balance.
     * 
     * @var ProductTransaction $productTransaction Object of ProductTransaction
     */
    public static function transfer_balance($method_name = '', $transaction = [], $product = [], UserProductAPI $api_account) {
		$client = static::request_configuration();

        $productTransaction = static::generate_txCode($transaction, $product);

        if (in_array($api_account->api_currency, array_keys(static::$currency_decimal_unit))) {
            $transaction['amount'] /= static::$currency_decimal_unit[$api_account->api_currency];
        }

        $timestamp = gmdate("Y-m-d\TH:i:s",strtotime(static::$apiTimezone." hours"));
        $checksum = md5($api_account->api_username . '.' . $api_account->api_currency .'.'. $productTransaction->api_transaction_id . '.' . $transaction['amount'] . '.' . $timestamp.'.salt',true);
        $api_url = config("app.trilion_isin4d_api_url").'/api/v2/Player/'.$method_name; // noted: marchant code and product type 
        $api_params = [
            'RefNo' => $productTransaction->api_transaction_id,
            'AccountId' => $api_account->api_username,
            'Currency' => $api_account->api_currency,
            'Amount' => $transaction['amount'],
            'Timestamp' => $timestamp,
            'Checksum' => base64_encode($checksum),         
        ];
        
        // $data = static::call_api($client, $api_url, $api_params);
        
        // API success
        if (isset($data['ErrorCode']) && $data['ErrorCode'] == static::$apiSuccessCode) {
            $productTransaction->fill([
                'status' => ProductTransaction::$status['success'],
                'response' => json_encode($data),
            ])->update();
        } else {
            $productTransaction->fill([
                'status' => ProductTransaction::$status['failed'],
                'response' => json_encode($data),
            ])->update();

            $stack = [__CLASS__, __FUNCTION__, __FILE__, __LINE__];
            System::error_email("[Trilion Isin4D] API response not success ({$api_url})", $api_params, $data, $stack);
        }

        return $productTransaction;
    }

    /**
     * Call Trilion retrieve bet detail API by passing in which product to retrieve.
     * 
     * @param Boolean $cron By default is true when called from scheduler
     * @param String $custom_date Date time string in timezone GMT +8
     * 
     */
    // public static function get_bet_detail($cron = true, $custom_date = '') {
    //     $trilion = new static();
    //     $client = static::request_configuration();

    //     // Important Note: $start_date & $end_date not recommend to share same object of Carbon. Eg, if you deduct 15 minutes first for $start_date, then $end_date will be affected
    //     if ($custom_date) {
    //         $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $custom_date)->subDay()->format('Y-m-d H:i:s');
    //         $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $custom_date)->format('Y-m-d H:i:s');
    //     } else {
    //         $start_date = Carbon::now()->subMinutes(static::$fetch_ticket_range)->setTimezone('GMT+0')->format('Y-m-d H:i:s');
    //         $end_date = Carbon::now()->setTimezone('GMT+0')->format('Y-m-d H:i:s');
    //     }
        
    //     $games_not_found = [];
        
    //     $product_wallet = $trilion->product_wallets;
    //     $product_wallet = array_shift($product_wallet);
    //     $wallet_to_provider = array_flip(Product::$provider_wallet);
    //     $currencies = Product::$provider_acc_currencies[$wallet_to_provider[$product_wallet]];
    //     foreach ($currencies as $currency) {
    //         $game_types = [];
    //         $games = [];
    //         $users = [];
    //         $userWallet = new UserWallet();

    //         $page = 0;
    //         do {
    //             $next_page = false;
                
    //             $api_url = config("app.avengersx_api_url").'/api/GetBetDetail';
    //             $api_params = [
    //                 'Operator' => config("app.avengersx_".$currency."_operator"),
    //                 'Product' => 'ALL',
    //                 'StartDate' => $start_date,
    //                 'EndDate' => $end_date,
    //                 'Page' => $page,
    //             ];
    //             $api_params['Token'] = static::generate_token($currency, $api_params);
                
    //             // $data = static::call_api($client, $api_url, $api_params);
                
    //             // API success
    //             if (isset($data['ErrorCode']) && $data['ErrorCode'] == static::$apiSuccessCode && isset($data['Data'])) {
    //                 foreach ($data['Data'] as $index => $value) {
    //                     if ($value['BetStatus'] != 'SETTLED') {
    //                         continue;
    //                     }

    //                     $wallet = $avengersx->platform[$value['Product']];
                        
    //                     $game_code = $value['Category'];
    //                     if (empty($games[$wallet][$game_code])) {
    //                         $game_api = DB::table('product_game_api')
    //                                     ->select('product_game_api.id', 'product_games.id AS product_game_id', 'products.id AS product_id')
    //                                     ->join('product_games', 'product_games.id', '=', 'product_game_api.product_game_id')
    //                                     ->join('products', 'products.id', '=', 'product_games.product_id')
    //                                     ->where('products.wallet', $wallet)
    //                                     ->where('product_game_api.game_code', $game_code)
    //                                     ->first();

    //                         if ($game_api === null) {
    //                             // Send email to notify admin
    //                             if (isset($games_not_found[$wallet][$game_code])) {
    //                                 $games_not_found[$wallet][$game_code] += 1;
    //                             } else {
    //                                 $games_not_found[$wallet][$game_code] = 1;
    //                             }
    
    //                             // Skip this iteration
    //                             continue;
    //                         }
    //                         $games[$wallet][$game_code] = $game_api;
    //                     } else {
    //                         $game_api = $games[$wallet][$game_code];
    //                     }
                        
    //                     $api_username = $value['Username'];
    //                     if (empty($users[$wallet][$api_username])) {
    //                         $user = DB::table('user_product_api')
    //                                     ->select('users.id', 'users.currency', 'user_product_api.api_currency')
    //                                     ->join('users', 'users.id', '=', 'user_product_api.user_id')
    //                                     ->where('product_id', $game_api->product_id)
    //                                     ->where('api_username', $api_username)
    //                                     ->first();
    //                         $users[$wallet][$api_username] = $user;
    //                     } else {
    //                         $user = $users[$wallet][$api_username];
    //                     }
    //                     // Skip this iteration if user not found(May happen when test on different database)
    //                     if ($user === null) {
    //                         continue;
    //                     }

    //                     $turnover_amount = $value['Stake']; 
    //                     $bet_amount = $value['Stake'];
    //                     $return_amount = ($value['Payout'] + $value['JackpotWin']);
    //                     $winlossamount = $return_amount - $bet_amount;

    //                     if (in_array($user->api_currency, array_keys(static::$currency_decimal_unit))) {
    //                         $decimal_unit = static::$currency_decimal_unit[$user->api_currency];
    //                         $turnover_amount *= $decimal_unit;
    //                         $bet_amount *= $decimal_unit;
    //                         $return_amount *= $decimal_unit;
    //                         $winlossamount *= $decimal_unit;
    //                     }
                        
    //                     switch ($value['PayoutStatus']) {
    //                         case 'WON':
    //                             $status = Bet::$status['win'];
    //                             $transaction_type = System::$transaction_type['general-win-bet'];
    //                             break;
    //                         case 'LOSE':
    //                             $status = Bet::$status['loss'];
    //                             $transaction_type = System::$transaction_type['general-lose-bet'];
    //                             break;
    //                         case 'DRAW':
    //                             $status = Bet::$status['draw'];
    //                             $transaction_type = System::$transaction_type['general-draw-bet'];
    //                             break;
    //                         case 'HALFWON':
    //                             $status = Bet::$status['win-half'];
    //                             $transaction_type = System::$transaction_type['general-win-half-bet'];
    //                             break;
    //                         case 'HALFLOSE':
    //                             $status = Bet::$status['lose-half'];
    //                             $transaction_type = System::$transaction_type['general-lose-half-bet'];
    //                             break;
    //                         case 'VOID':
    //                             $status = Bet::$status['void'];
    //                             $transaction_type = System::$transaction_type['general-void-bet'];
    //                             break;
    //                         case 'CASHOUT':
    //                             $status = Bet::$status['cashout'];
    //                             $transaction_type = System::$transaction_type['ibc-cashout-bet'];
    //                             break;
    //                         default:
    //                             // Send email to notify admin
    //                             if (! in_array($value['PayoutStatus'], [
    //                                 'OPEN', 
    //                             ])) {
    //                                 $stack = [__CLASS__, __FUNCTION__, __FILE__, __LINE__];
    //                                 System::error_email("[AvengersX] Bet payout status not found", $value, '', $stack);
    //                             }
    //                             continue 2;
    //                             break;
    //                     }

    //                     $bet_date = Carbon::parse($value['AccountDate'])->toDateTimeString();

    //                     $bet_exist = Bet::where('product_id', $game_api->product_id)
    //                                     ->where('api_bet_id', $value['ID'])
    //                                     ->first();
    //                     if ($bet_exist === null) {
    //                         $bet = Bet::create([
    //                             'user_id' => $user->id,
    //                             'product_id' => $game_api->product_id,
    //                             'product_game_id' => $game_api->product_game_id,
    //                             'product_game_api_id' => $game_api->id,
    //                             'api_bet_id' => $value['ID'],
    //                             'currency' => $user->currency,
    //                             'wallet' => $wallet,
    //                             'turnover_amount' => $turnover_amount,
    //                             'bet_amount' => $bet_amount,
    //                             'return_amount' => $return_amount,
    //                             'win_loss_amount' => $winlossamount,
    //                             'status' => $status,
    //                             'data' => json_encode($value),
    //                             'bet_date' => $bet_date,
    //                         ]);

    //                         // Win bet
    //                         if ($winlossamount > 0) {
    //                             // Credit balance to wallet
    //                             $history = $userWallet->update_wallet(
    //                                 $user->id, 
    //                                 UserWalletHistory::$type['credit'], 
    //                                 $user->currency, 
    //                                 $wallet, 
    //                                 $winlossamount, 
    //                                 $transaction_type, 
    //                                 $bet->id,
    //                                 '',
    //                                 $bet->toArray(), 
    //                             );
    //                         } 
    //                         // Lose bet
    //                         elseif ($winlossamount < 0) {
    //                             $winlossamount = abs($winlossamount);
    //                             // Debit balance from wallet
    //                             $history = $userWallet->update_wallet(
    //                                 $user->id, 
    //                                 UserWalletHistory::$type['debit'], 
    //                                 $user->currency, 
    //                                 $wallet, 
    //                                 $winlossamount, 
    //                                 $transaction_type, 
    //                                 $bet->id,
    //                                 '',
    //                                 $bet->toArray(), 
    //                             );
    //                         }
    //                         // Bet draw
    //                         elseif ($winlossamount == 0) {
    //                         }
    //                     }
    //                 }
                    
    //                 if ($page < $data['TotalPage']) {
    //                     $next_page = true;
    //                 }
    //             } else {
    //                 // If error is not system maintenance
    //                 if (isset($data['ErrorCode']) && ! in_array($data['ErrorCode'], [
    //                     1012, // Product is currently under maintenance
    //                     9998, // API currently under maintenance
    //                 ])) {
    //                     // Send email to notify admin 
    //                     $stack = [__CLASS__, __FUNCTION__, __FILE__, __LINE__];
    //                     System::error_email("[Trilion Isin4D] Error in retrieve bet history ({$api_url})", $api_params, $data, $stack);
    //                 }
    //             }
    //             $page++;
    //         } while ($next_page);
    //     }

    //     if (! empty($games_not_found)) {
    //         $stack = [__CLASS__, __FUNCTION__, __FILE__, __LINE__];
    //         System::error_email("[AvengersX] Game not found in db", [], $games_not_found, $stack);
    //     }
    // }
}
