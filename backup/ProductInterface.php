<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\User;
use App\UserProductAPI;
use App\Product;
use App\ProductTransaction;
use App\Helper;
use App\System;
use App\Products\AllBet;
use App\Products\DreamGame;
use App\Products\HKBGaming;
use App\Products\PlayTech;
use App\Products\SSports;
use App\Products\SBO;
use App\Products\AGGB;
use App\Products\IDNPLAY;
use App\Products\AWC;
use App\Products\MGP;
use App\Products\AvengersX;
use App\Products\CMD;
use App\Products\Ezugi;
use App\Products\GD;
use App\Products\PragmaticPlay;
use App\Products\Habanero;
// use App\Products\Trilion;

class ProductInterface extends Model
{
    /**
     * Interface to decide which Product to call for create player.
     *
     * @param  array  $transaction Data require to call API
     * @param  array  $product Product data
     * @param  array  $stack Stack in case of API error
     * 
     */
    public static function create_player($transaction = [], $product = [], $stack = []) {
        switch ($product['wallet']) {
            case Product::$provider_wallet['main']:
                // If is main wallet then do nothing
                break;
            case Product::$provider_wallet['playtech']:
                // PlayTech Create account
                $api_account = PlayTech::playtech_create_acc($transaction, $product);
                break;
            case Product::$provider_wallet['ssport']:
                // S-Sports Create account
                $api_account = SSports::ssport_create_acc($transaction, $product);
                break;
            case Product::$provider_wallet['allbet']:
                // AllBet Create account
                $api_account = AllBet::allbet_create_acc($transaction, $product);
                break;
            case Product::$provider_wallet['dreamgame']:
                // DreamGame Create account
                $api_account = DreamGame::dreamgame_create_acc($transaction, $product);
                break;
            case Product::$provider_wallet['hkbgaming']:
                // HKBGaming Create account
                $user = User::find($transaction['user_id']);
                $transaction['user_fullname'] = $user->name;
                $transaction['user_email'] = $user->email;

                $api_account = HKBGaming::hkbgaming_create_acc($transaction, $product);
                break;
            case Product::$provider_wallet['sbo']:
                // SBO Create account
                $api_account = SBO::sbo_create_acc($transaction, $product);
                break;
            case Product::$provider_wallet['aggb']:
                // AGGB Create account
                $api_account = AGGB::aggb_create_acc($transaction);
                break;
            case Product::$provider_wallet['idnplay']:
                // IDNPLAY Create account
                $api_account = IDNPLAY::idnplay_create_acc($transaction, $product);
                break;
            case Product::$provider_wallet['awc_sexygaming']:
            case Product::$provider_wallet['awc_pragmaticplay']:
            case Product::$provider_wallet['awc_spadegaming']:
            case Product::$provider_wallet['awc_tgp']:
            case Product::$provider_wallet['awc_jdb']:
            case Product::$provider_wallet['awc_jdbfish']:
            case Product::$provider_wallet['awc_kingmaker']:
                // AWC Create account
                $api_account = AWC::create_player_acc($transaction, $product);
                break;
            case Product::$provider_wallet['mgp']:
                // MGP Create account
                $api_account = MGP::createAccount($transaction);
                break;
            case Product::$provider_wallet['avengersx_xprogaming']:
            case Product::$provider_wallet['avengersx_gameplay']:
            case Product::$provider_wallet['avengersx_sagaming']:
            case Product::$provider_wallet['avengersx_ibc']:
            case Product::$provider_wallet['avengersx_winningft']:
            case Product::$provider_wallet['avengersx_wmc']:
            case Product::$provider_wallet['avengersx_evolution']:
            case Product::$provider_wallet['avengersx_s128']:
                // AvengersX Create account
                $api_account = AvengersX::create_player_acc($transaction, $product);
                break;
            case Product::$provider_wallet['cmd']:
                // CMD Create account
                $api_account = CMD::createAccount($transaction);
                break;
            case Product::$provider_wallet['ezugi']:
                // Ezugi Create account
                $api_account = Ezugi::create_player_acc($transaction, $product);
                break;
            case Product::$provider_wallet['gd']:
                // GD Create account
                $api_account = GD::createMember($transaction);
                break;
            case Product::$provider_wallet['pragmaticplay']:
                // Pragmatic Create account
                $api_account = PragmaticPlay::createMember($transaction);
                break;
            case Product::$provider_wallet['habanero']:
                // Habanero Create account
                $api_account = Habanero::createMember($transaction);
                break;
            // case Product::$provider_wallet['trilion_isin4d']:
                // isin4d Create account
                // break;
            default:
                System::error_email("Create account method of this product not found.", $product, '', $stack);
                break;
        }

        return $api_account ?? null;
    }
    
    /**
     * Interface to decide which Product to call for create player.
     *
     * @param  array  $transaction Data require to call API
     * @param  UserProductAPI  $api_account UserProductAPI object
     * @param  array  $stack Stack in case of API error
     * 
     */
    public static function get_balance($transaction = [], UserProductAPI $api_account, $stack = []) {
        switch ($transaction['wallet']) {
            case Product::$provider_wallet['playtech']:
                $balance = PlayTech::playtech_get_balance($transaction['currency'], $api_account);
                break;
            case Product::$provider_wallet['ssport']:
                $balance = SSports::ssport_get_balance($api_account);
                break;
            case Product::$provider_wallet['allbet']:
                $balance = AllBet::allbet_get_balance($transaction['currency'], $api_account);
                break;
            case Product::$provider_wallet['dreamgame']:
                $balance = DreamGame::dreamgame_get_balance($api_account);
                break;
            case Product::$provider_wallet['hkbgaming']:
                $balance = HKBGaming::hkbgaming_player_balance($transaction['currency'], $api_account);
                break;
            case Product::$provider_wallet['sbo']:
                $balance = SBO::sbo_get_balance($api_account);
                break;
            case Product::$provider_wallet['aggb']:
                $balance = AGGB::aggb_get_balance($transaction['currency'], $api_account);
                break;
            case Product::$provider_wallet['idnplay']:
                $balance = IDNPLAY::idnplay_get_balance($transaction['currency'], $api_account);
                break;
            case Product::$provider_wallet['awc_sexygaming']:
            case Product::$provider_wallet['awc_pragmaticplay']:
            case Product::$provider_wallet['awc_spadegaming']:
            case Product::$provider_wallet['awc_tgp']:
            case Product::$provider_wallet['awc_jdb']:
            case Product::$provider_wallet['awc_jdbfish']:
            case Product::$provider_wallet['awc_kingmaker']:
                $balance = AWC::get_balance($transaction['currency'], $api_account);
                break;
            case Product::$provider_wallet['mgp']:
                $balance = MGP::getBalance($api_account, $transaction);
                break;
            case Product::$provider_wallet['avengersx_xprogaming']:
            case Product::$provider_wallet['avengersx_gameplay']:
            case Product::$provider_wallet['avengersx_sagaming']:
            case Product::$provider_wallet['avengersx_ibc']:
            case Product::$provider_wallet['avengersx_winningft']:
            case Product::$provider_wallet['avengersx_wmc']:
            case Product::$provider_wallet['avengersx_evolution']:
            case Product::$provider_wallet['avengersx_s128']:
                $balance = AvengersX::get_balance($transaction['currency'], $transaction['wallet'], $api_account);
                break;
            case Product::$provider_wallet['cmd']:
                $balance = CMD::getBalance($api_account, $transaction['currency']);
                break;
            case Product::$provider_wallet['ezugi']:
                $balance = Ezugi::get_balance($transaction['currency'], $api_account);
                break;
            case Product::$provider_wallet['gd']:
                $balance = GD::getBalance($api_account);
                break;
            case Product::$provider_wallet['pragmaticplay']:
                $balance = PragmaticPlay::getBalance($api_account, $transaction['currency']);
                break;
            case Product::$provider_wallet['habanero']:
                $balance = Habanero::getBalance($api_account, $transaction['currency']);
                break;
            // case Product::$provider_wallet['trilion_isin4d']:
                // $balance = Trilion::get_balance($transaction['currency'], $api_account);
                // break;
            default:
                System::error_email("Get player balance of this product not found.", $transaction, '', $stack);
                break;
        }

        return $balance ?? 0;
    }

    /**
     * Interface to decide which Product to call for create player.
     *
     * @param  array  $transaction Data require to call API
     * @param  array  $product Product data
     * @param  object  $api_account UserProductAPI object
     * @param  array  $stack Stack in case of API error
     * 
     */
    public static function deposit($transaction = [], $product = [], $api_account = null, $stack = []) {
        switch ($product['wallet']) {
            case Product::$provider_wallet['main']:
                // If is main wallet then do nothing
                break;
            case Product::$provider_wallet['playtech']:
                // PlayTech deposit
                $productTransaction = PlayTech::playtech_deposit($transaction, $product, $api_account);
                break;
            case Product::$provider_wallet['ssport']:
                // S-Sports deposit
                $productTransaction = SSports::ssport_deposit($transaction, $product, $api_account);
                break;
            case Product::$provider_wallet['allbet']:
                // AllBet deposit
                $productTransaction = AllBet::allbet_deposit($transaction, $product, $api_account);
                break;
            case Product::$provider_wallet['dreamgame']:
                // DreamGame deposit
                $productTransaction = DreamGame::dreamgame_deposit($transaction, $product, $api_account);
                break;
            case Product::$provider_wallet['hkbgaming']:
                // HKBGaming deposit
                $productTransaction = HKBGaming::hkbgaming_deposit($transaction, $product, $api_account);
                break;
            case Product::$provider_wallet['sbo']:
                // SBO deposit
                $productTransaction = SBO::sbo_deposit($transaction, $product, $api_account);
                break;
            case Product::$provider_wallet['aggb']:
                // AGGB deposit
                $productTransaction = AGGB::aggb_deposit($transaction, $api_account);
                break;
            case Product::$provider_wallet['idnplay']:
                // IDNPLAY deposit
                $productTransaction = IDNPLAY::idnplay_deposit($transaction, $product, $api_account);
                break;
            case Product::$provider_wallet['awc_sexygaming']:
            case Product::$provider_wallet['awc_pragmaticplay']:
            case Product::$provider_wallet['awc_spadegaming']:
            case Product::$provider_wallet['awc_tgp']:
            case Product::$provider_wallet['awc_jdb']:
            case Product::$provider_wallet['awc_jdbfish']:
            case Product::$provider_wallet['awc_kingmaker']:
                // AWC deposit
                $productTransaction = AWC::deposit($transaction, $product, $api_account);
                break;
            case Product::$provider_wallet['mgp']:
                // MGP deposit
                $productTransaction = MGP::deposit($api_account, $transaction);
                break;
            case Product::$provider_wallet['avengersx_xprogaming']:
            case Product::$provider_wallet['avengersx_gameplay']:
            case Product::$provider_wallet['avengersx_sagaming']:
            case Product::$provider_wallet['avengersx_ibc']:
            case Product::$provider_wallet['avengersx_winningft']:
            case Product::$provider_wallet['avengersx_wmc']:
            case Product::$provider_wallet['avengersx_evolution']:
            case Product::$provider_wallet['avengersx_s128']:
                // AvengersX deposit
                $productTransaction = AvengersX::deposit($transaction, $product, $api_account);
                break;
            case Product::$provider_wallet['cmd']:
                // CMD deposit
                $productTransaction = CMD::deposit($api_account, $transaction);
                break;
            case Product::$provider_wallet['ezugi']:
                // Ezugi deposit
                $productTransaction = Ezugi::deposit($transaction, $product, $api_account);
                break;
            case Product::$provider_wallet['gd']:
                // GD deposit
                $productTransaction = GD::deposit($api_account, $transaction);
                break;
            case Product::$provider_wallet['pragmaticplay']:
                // PragmaticPlay deposit
                $productTransaction = PragmaticPlay::deposit($api_account, $transaction);
                break;
            case Product::$provider_wallet['habanero']:
                // Habanero deposit
                $productTransaction = Habanero::deposit($api_account, $transaction);
                break;
            // case Product::$provider_wallet['trilion_isin4d']:
                // Isin4D deposit
                // $productTransaction = Trilion::deposit($transaction, $product, $api_account);
                // break;
            default:
                System::error_email("Deposit method of this product not found.", $product, '', $stack);
                break;
        }

        return $productTransaction ?? null;
    }

    /**
     * Interface to decide which Product to call for create player.
     *
     * @param  array  $transaction Data require to call API
     * @param  array  $product Product data
     * @param  object  $api_account UserProductAPI object
     * @param  array  $stack Stack in case of API error
     * 
     */
    public static function withdraw($transaction = [], $product = [], $api_account = null, $stack = []) {
        switch ($product['wallet']) {
            case Product::$provider_wallet['main']:
                // If is main wallet then do nothing
                break;
            case Product::$provider_wallet['playtech']:
                // Logout member if member is currently logging/playing
                if (PlayTech::playtech_is_online($transaction['currency'], $api_account)) {
                    PlayTech::playtech_logout($transaction['currency'], $api_account);
                }

                // Call PlayTech API to withdraw
                $productTransaction = PlayTech::playtech_withdraw($transaction, $product, $api_account);
                break;
            case Product::$provider_wallet['ssport']:
                // Call S-Sports API to withdraw
                $productTransaction = SSports::ssport_withdraw($transaction, $product, $api_account);
                break;
            case Product::$provider_wallet['allbet']:
                // Call AllBet API to withdraw
                $productTransaction = AllBet::allbet_withdraw($transaction, $product, $api_account);
                break;
            case Product::$provider_wallet['dreamgame']:
                // Call DreamGame API to withdraw
                $productTransaction = DreamGame::dreamgame_withdraw($transaction, $product, $api_account);
                break;
            case Product::$provider_wallet['hkbgaming']:
                // Call HKBGaming API to withdraw
                $productTransaction = HKBGaming::hkbgaming_withdraw($transaction, $product, $api_account);
                break;
            case Product::$provider_wallet['sbo']:
                // Call SBO API to withdraw
                $productTransaction = SBO::sbo_withdraw($transaction, $product, $api_account);
                break;
            case Product::$provider_wallet['aggb']:
                // Call AGGB API to withdraw
                $productTransaction = AGGB::aggb_withdrawal($transaction, $api_account);
                break;
            case Product::$provider_wallet['idnplay']:
                // Call IDNPLAY API to withdraw
                $productTransaction = IDNPLAY::idnplay_withdraw($transaction, $product, $api_account);
                break;
            case Product::$provider_wallet['awc_sexygaming']:
            case Product::$provider_wallet['awc_pragmaticplay']:
            case Product::$provider_wallet['awc_spadegaming']:
            case Product::$provider_wallet['awc_tgp']:
            case Product::$provider_wallet['awc_jdb']:
            case Product::$provider_wallet['awc_jdbfish']:
            case Product::$provider_wallet['awc_kingmaker']:
                // AWC withdraw
                $productTransaction = AWC::withdraw($transaction, $product, $api_account);
                break;
            case Product::$provider_wallet['mgp']:
                // Call MGP API to withdraw
                $productTransaction = MGP::withdraw($api_account, $transaction);
                break;
            case Product::$provider_wallet['avengersx_xprogaming']:
            case Product::$provider_wallet['avengersx_gameplay']:
            case Product::$provider_wallet['avengersx_sagaming']:
            case Product::$provider_wallet['avengersx_ibc']:
            case Product::$provider_wallet['avengersx_winningft']:
            case Product::$provider_wallet['avengersx_wmc']:
            case Product::$provider_wallet['avengersx_evolution']:
            case Product::$provider_wallet['avengersx_s128']:
                // Call AvengersX API to withdraw
                $productTransaction = AvengersX::withdraw($transaction, $product, $api_account);
                break;
            case Product::$provider_wallet['cmd']:
                // Call CMD API to withdraw
                $productTransaction = CMD::withdraw($api_account, $transaction);
                break;
            case Product::$provider_wallet['ezugi']:
                // Call Ezugi API to withdraw
                $productTransaction = Ezugi::withdraw($transaction, $product, $api_account);
                break;
            case Product::$provider_wallet['gd']:
                // Call GD API to withdraw
                $productTransaction = GD::withdraw($api_account, $transaction);
                break;
            case Product::$provider_wallet['pragmaticplay']:
                // Call PragmaticPlay API to withdraw
                $productTransaction = PragmaticPlay::withdraw($api_account, $transaction);
                break;
            case Product::$provider_wallet['habanero']:
                // Call Habanero API to withdraw
                $productTransaction = Habanero::withdraw($api_account, $transaction);
                break;
            // case Product::$provider_wallet['trilion_isin4d']:
                // Call Isin4D API to withdraw
                // $productTransaction = Trilion::withdraw($transaction, $product, $api_account);
                // break;
            default:
            System::error_email("Withdrawal method of this product not found.", $product, '', $stack);
                break;
        }

        return $productTransaction ?? null;
    }
}
