<?php

namespace App\Http\Controllers\Game;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\UserProductAPI;
use App\Product;
use App\ProductGame;
use App\ProductGameAPI;
use App\Products\Trilion;
use App\Helper;
use App\System;

class TrilionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request, $game_code = '')
    {
        $product = DB::table('product_game_api')
                    ->select('products.id AS product_id')
                    ->join('product_games', 'product_games.id', '=', 'product_game_api.product_game_id')
                    ->join('products', 'products.id', '=', 'product_games.product_id')
                    ->where('products.wallet', Product::$provider_wallet['isin4d'])
                    ->where('products.status', ProductGameAPI::$status['active'])
                    ->where('product_games.status', ProductGame::$status['active'])
                    ->where('product_game_api.game_code', $game_code)
                    ->where('product_game_api.status', ProductGameAPI::$status['active'])
                    ->first();
        if ($product === null) {
            return $this->maintenance();
        }
        $product = [
        	'id' => $product->product_id,
        ];

        $currency = $request->user()->currency;
        $api_account = UserProductAPI::where('user_id', $request->user()->id)
                                        ->where('product_id', $product['id'])
                                        ->orderBy('id', 'asc')
                                        ->first();
        // Help member create account for the product if doesn't have one 
		if ($api_account === null) {
	        $transaction = [
	            'currency' => $request->user()->currency,
	            'user_id' => $request->user()->id,
	        ];
	        // $api_account = Trilion::create_acc($transaction, $product);
            
            // Check create acc success or failed
            if (empty($api_account)) {
                return $this->maintenance();
            }
		}

        // $game_session = Trilion::member_login($api_account);
        if (empty($game_session)) {
            return $this->maintenance();
        }

        return view('game.redirect', [
        	'game_session' => $game_session ?? '',
        ]);
    }
}
