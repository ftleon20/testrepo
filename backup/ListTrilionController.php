<?php

namespace App\Http\Controllers\Game;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Product;
use App\ProductGame;
use App\ProductGameAPI;
use App\Helper;
use App\System;
use App\Products\Trilion;
use Jenssegers\Agent\Agent;

class ListTrilionController extends Controller
{
    /**
     * Wallet
     *
     * @var string
     */
    public $wallet;
    
    /**
     * Provider
     *
     * @var string
     */
    public $provider;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->wallet = Product::$provider_wallet['trilion_isin4d'];
        $wallet_provider = array_flip(Product::$provider_wallet);
        $this->provider = $wallet_provider[$this->wallet];
    }

    /**
     * Show the games list page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request, $game_type = '')
    {
        die;
        $show = false;
        $product = Product::where('wallet', $this->wallet)
                            ->first();
        $currencies = json_decode($product->currencies, true);
        if (Auth::guard('web')->check()) {
            if (in_array($request->user()->currency, $currencies)) {
                $show = true;
            }
        } else {
            $show = true;
        }
        
        $default_game_code = '';
        if ($show) {
            $query = DB::table('product_game_api')
                        ->select('product_game_api.*', 'products.wallet')
                        ->join('product_games', 'product_games.id', '=', 'product_game_api.product_game_id')
                        ->join('products', 'products.id', '=', 'product_games.product_id')
                        ->where('products.wallet', $this->wallet)
                        ->where('product_game_api.status', ProductGameAPI::$status['active']);
            switch ($game_type) {
                case 'lottery':
                    
                default:
                    $query->where('product_games.game_type', $game_type);
                    break;
            }
            $games = $query->get();
            
            $first = $games->first();
            $default_game_code = $first->game_code ?? '';

            foreach ($games as $index => $game) {
                $game->path = $this->provider;
                
                $name = json_decode($game->name, true);
                $game->name = $name[App::getlocale()] ?? '';
                $game->image = json_decode($game->image, true);
            }
        }

        $info = [
            'game_type' => $game_type,
            'product' => $this->provider,
            'game_url' => Helper::url($this->provider.'/play/'.$default_game_code),
        ];
        if ($game_type == ProductGame::$game_type['live-dealer']) {
            $template = 'game.list-banner';
        } else {
            $template = 'game.list-games';
        }
        return view($template, [
            'info' => $info,
        	'locale' => App::getlocale(),
        	'games' => $games ?? [],
        ]);
    }
}
