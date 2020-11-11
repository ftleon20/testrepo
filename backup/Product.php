<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\UserProductAPI;
use App\Bet;
use App\Product;
use App\ProductGame;
use App\ProductGameAPI;
use App\ProductTransaction;
use App\UserWallet;
use App\UserWalletHistory;
use App\Helper;
use Carbon\Carbon;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'wallet', 'main', 'currencies', 'last_modified_by', 'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    /**
     * The basic attribute rules for product model
     *
     * @var array
     */
    public static $attribute_rules = [
        'status' => ['required', 'string'],
    ];

    /**
     * The product status
     *
     * @var array
     */
    public static $status = [
        'active' => 'active',
        'inactive' => 'inactive',
    ];

    /**
     * Provider with wallet in key value pair.
     * 
     * @var array
     */
    public static $provider_wallet = [
        'main' => 'main_wallet',
        'ssport' => 'ssport_wallet',
        'playtech' => 'playtech_wallet',
        'allbet' => 'allbet_wallet',
        'dreamgame' => 'dreamgame_wallet',
        'hkbgaming' => 'hkbgaming_wallet',
        'sbo' => 'sbo_wallet',
        'aggb' => 'aggb_wallet',
        'idnplay' => 'idnplay_wallet',
        'awc_sexygaming' => 'awc_sexygaming_wallet',
        'awc_pragmaticplay' => 'awc_pragmaticplay_wallet',
        'awc_spadegaming' => 'awc_spadegaming_wallet',
        'mgp' => 'mgp_wallet',
        'avengersx_xprogaming' => 'avengersx_xprogaming_wallet',
        'avengersx_gameplay' => 'avengersx_gameplay_wallet',
        'avengersx_sagaming' => 'avengersx_sagaming_wallet',
        'avengersx_ibc' => 'avengersx_ibc_wallet',
        'avengersx_winningft' => 'avengersx_winningft_wallet',
        'cmd' => 'cmd_wallet',
        'avengersx_wmc' => 'avengersx_wmc_wallet',
        'avengersx_evolution' => 'avengersx_evolution_wallet',
        'avengersx_s128' => 'avengersx_s128_wallet',
        'awc_jdbfish' => 'awc_jdbfish_wallet', 
        'awc_jdb' => 'awc_jdb_wallet', 
        'awc_tgp' => 'awc_tgp_wallet', 
        'awc_kingmaker' => 'awc_kingmaker_wallet', 
        'ezugi' => 'ezugi_wallet', 
        'gd' => 'gd_wallet',
        'pragmaticplay' => 'pragmaticplay_wallet', 
        'habanero' => 'habanero_wallet', 
        // 'trilion_isin4d' => 'trilion_isin4d_wallet', 
    ];

    /**
     * Provider with wallet in key value pair for search engine purpose.
     * 
     * @var array
     */
    public static $provider_wallet_filter = [
        'main' => 'main_wallet',
        'ssport' => 'ssport_wallet',
        'playtech' => 'playtech_wallet',
        'allbet' => 'allbet_wallet',
        'dreamgame' => 'dreamgame_wallet',
        'hkbgaming' => 'hkbgaming_wallet',
        'sbo' => 'sbo_wallet',
        'aggb' => 'aggb_wallet',
        'idnplay' => 'idnplay_wallet',
        'awc_sexygaming' => 'awc_sexygaming_wallet',
        'awc_pragmaticplay' => 'awc_pragmaticplay_wallet',
        'awc_spadegaming' => 'awc_spadegaming_wallet',
        'mgp' => 'mgp_wallet',
        'avengersx_xprogaming' => 'avengersx_xprogaming_wallet',
        'avengersx_gameplay' => 'avengersx_gameplay_wallet',
        'avengersx_sagaming' => 'avengersx_sagaming_wallet',
        'avengersx_ibc' => 'avengersx_ibc_wallet',
        'avengersx_winningft' => 'avengersx_winningft_wallet',
        'cmd' => 'cmd_wallet',
        'avengersx_wmc' => 'avengersx_wmc_wallet',
        'avengersx_evolution' => 'avengersx_evolution_wallet',
        'avengersx_s128' => 'avengersx_s128_wallet',
        'awc_jdbfish' => 'awc_jdbfish_wallet', 
        'awc_jdb' => 'awc_jdb_wallet', 
        'awc_tgp' => 'awc_tgp_wallet', 
        'awc_kingmaker' => 'awc_kingmaker_wallet', 
        'ezugi' => 'ezugi_wallet', 
        'gd' => 'gd_wallet',
        'pragmaticplay' => 'pragmaticplay_wallet', 
        'habanero' => 'habanero_wallet',
        // 'trilion_isin4d' => 'trilion_isin4d_wallet',  
    ];

    /**
     * Provider account has access to what currency. Whenever request provider side to add/open new currency. We will have to add it here as well else all games will not be available the member of new currency.
     * 
     * @var array
     */
    public static $provider_acc_currencies = [
        'main' => [
            // 'MYR',
            'SGD',
            // 'CNY',
            'IDR',
        ],
        'ssport' => [
            'IDR',
            'SGD',
        ],
        'playtech' => [
            'IDR',
        ],
        'allbet' => [
            'IDR',
            'SGD',
        ],
        'dreamgame' => [
            'IDR',
            'SGD',
        ],
        'hkbgaming' => [
            'IDR',
        ],
        'sbo' => [
            'IDR',
        ],
        'aggb' => [
            'IDR', 
        ],
        'idnplay' => [
            'IDR',
        ],
        'awc_sexygaming' => [
            'IDR',
            'SGD',
        ],
        'awc_pragmaticplay' => [
            'IDR',
        ],
        'awc_spadegaming' => [
            'IDR',
            'SGD',
        ],
        'mgp' => [
            'IDR',
        ],
        'avengersx_xprogaming' => [
            'IDR',
            'SGD',
        ],
        'avengersx_gameplay' => [
            'IDR',
        ],
        'avengersx_sagaming' => [
            'IDR',
            // 'SGD',
        ],
        'avengersx_ibc' => [
            'IDR',
        ],
        'avengersx_winningft' => [
            'IDR',
        ],
        'cmd' => [
            'IDR',
            'SGD',
        ],
        'avengersx_wmc' => [
            'IDR',
            'SGD',
        ],
        'avengersx_evolution' => [
            'IDR',
        ],
        'avengersx_s128' => [
            'IDR',
        ],
        'awc_jdbfish' => [
            'IDR',
        ],
        'awc_jdb' => [
            'IDR',
        ],
        'awc_tgp' => [
            'IDR',
        ],
        'awc_kingmaker' => [
            'IDR',
        ],
        'ezugi' => [
            'IDR',
            'SGD',
        ],
        'gd' => [
            'IDR',
        ],
        'pragmaticplay' => [
            'IDR',
            'SGD',
        ],
        'habanero' => [
            'IDR', 
        ],
        // 'trilion_isin4d' => [
        //     'IDR', 
        //     'SGD', 
        // ],
    ];
}
