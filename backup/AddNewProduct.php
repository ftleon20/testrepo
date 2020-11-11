<?php

namespace App\Http\Controllers\Admin\Script;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use App\ProductGame;
use App\System;

class AddNewProduct extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:web_admin');
        // $this->middleware('permission:script/run_script');
    }

    /**
     * Add product.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function addProduct(Request $request)
    {
        $product_game_type = [
            'ssport_wallet' => [
                'name' => 'S-Sports',
                'currencies' => ['IDR'],
                'game_types' => [
                    ProductGame::$game_type['sport'] => 1,
                ],
            ],
            'playtech_wallet' => [
                'name' => 'PlayTech',
                'currencies' => ['IDR'],
                'game_types' => [
                    ProductGame::$game_type['live-dealer'] => 1,
                    ProductGame::$game_type['slot'] => 1,
                ],
            ],
            'allbet_wallet' => [
                'name' => 'AllBet',
                'currencies' => ['IDR'],
                'game_types' => [
                    ProductGame::$game_type['live-dealer'] => 1,
                    ProductGame::$game_type['p2p'] => 1,
                    ProductGame::$game_type['fishing'] => 1,
                ],
            ],
            'dreamgame_wallet' => [
                'name' => 'DreamGame',
                'currencies' => ['IDR'],
                'game_types' => [
                    ProductGame::$game_type['live-dealer'] => 1,
                ],
            ],
            'hkbgaming_wallet' => [
                'name' => 'HKBGaming',
                'currencies' => ['IDR'],
                'game_types' => [
                    ProductGame::$game_type['p2p'] => 1,
                    ProductGame::$game_type['lottery'] => 1,
                ],
            ],
            'sbo_wallet' => [
                'name' => 'SBO',
                'currencies' => ['IDR'],
                'game_types' => [
                    ProductGame::$game_type['sport'] => 1,
                    ProductGame::$game_type['live-dealer'] => 1,
                    ProductGame::$game_type['slot'] => 1,
                ],
            ],
            'aggb_wallet' => [
                'name' => 'AGGB',
                'currencies' => ['IDR'],
                'game_types' => [
                    ProductGame::$game_type['live-dealer'] => 1,
                    ProductGame::$game_type['slot'] => 1,
                ],
            ],
            'idnplay_wallet' => [
                'name' => 'IDNPLAY',
                'currencies' => [
                    System::$currency['IDR'],
                ],
                'game_types' => [
                    ProductGame::$game_type['p2p'] => 1,
                    ProductGame::$game_type['poker'] => 1,
                ],
            ],
            'awc_sexygaming_wallet' => [
                'name' => "AWC's Sexy Baccarat",
                'currencies' => ['IDR'],
                'game_types' => [
                    ProductGame::$game_type['live-dealer'] => 1,
                ],
            ],
            'awc_pragmaticplay_wallet' => [
                'name' => "AWC's Pragmatic Play",
                'currencies' => ['IDR'],
                'game_types' => [
                    ProductGame::$game_type['slot'] => 1,
                ],
            ],
            'awc_spadegaming_wallet' => [
                'name' => "AWC's SpadeGaming",
                'currencies' => ['IDR'],
                'game_types' => [
                    ProductGame::$game_type['slot'] => 1,
                ],
            ],
            'mgp_wallet' => [
                'name' => 'Microgaming',
                'currencies' => ['IDR'],
                'game_types' => [
                    ProductGame::$game_type['sport'] => 1,
                    ProductGame::$game_type['live-dealer'] => 1,
                    ProductGame::$game_type['slot'] => 1,
                    ProductGame::$game_type['lottery'] => 1,
                    ProductGame::$game_type['fishing'] => 1,
                ],
            ],
            'avengersx_xprogaming_wallet' => [
                'name' => "AvengersX's XPROGaming",
                'currencies' => [
                    System::$currency['IDR'],
                ],
                'game_types' => [
                    ProductGame::$game_type['live-dealer'] => 1,
                    ProductGame::$game_type['slot'] => 1,
                ],
            ],
            // 'avengersx_gameplay_wallet' => [
            //     'name' => "AvengersX's Gameplay",
            //     'currencies' => [
            //         System::$currency['IDR'],
            //     ],
            //     'game_types' => [
            //         ProductGame::$game_type['live-dealer'] => 1,
            //         ProductGame::$game_type['slot'] => 1,
            //     ],
            // ],
            'avengersx_sagaming_wallet' => [
                'name' => "AvengersX's SA Gaming",
                'currencies' => [
                    System::$currency['IDR'],
                ],
                'game_types' => [
                    ProductGame::$game_type['live-dealer'] => 1,
                ],
            ],
            'avengersx_ibc_wallet' => [
                'name' => "AvengersX's IBC",
                'currencies' => [
                    System::$currency['IDR'],
                ],
                'game_types' => [
                    ProductGame::$game_type['sport'] => 1,
                ],
            ],
            'avengersx_winningft_wallet' => [
                'name' => "AvengersX's WinningFT",
                'currencies' => [
                    System::$currency['IDR'],
                ],
                'game_types' => [
                    ProductGame::$game_type['sport'] => 1,
                ],
            ],
            'cmd_wallet' => [
                'name' => 'CMD 368',
                'currencies' => [
                    System::$currency['IDR'],
                ],
                'game_types' => [
                    ProductGame::$game_type['sport'] => 1,
                ],
            ],
            'avengersx_wmc_wallet' => [
                'name' => "AvengersX's WM Live Casino",
                'currencies' => [
                    System::$currency['IDR'],
                ],
                'game_types' => [
                    ProductGame::$game_type['live-dealer'] => 1,
                ],
            ],
            'avengersx_evolution_wallet' => [
                'name' => "AvengersX's Evolution Gaming",
                'currencies' => [
                    System::$currency['IDR'],
                ],
                'game_types' => [
                    ProductGame::$game_type['live-dealer'] => 1,
                ],
            ],
            // 'avengersx_s128_wallet' => [
            //     'name' => "AvengersX's S128 Cockfight",
            //     'currencies' => [
            //         System::$currency['IDR'],
            //     ],
            //     'game_types' => [
            //         ProductGame::$game_type['cockfight'] => 1,
            //     ],
            // ],
            'awc_jdb_wallet' => [
                'name' => "AWC's JDB",
                'currencies' => ['IDR'],
                'game_types' => [
                    ProductGame::$game_type['slot'] => 1,
                ],
            ],
            'awc_jdbfish_wallet' => [
                'name' => "AWC's JDB Fish",
                'currencies' => ['IDR'],
                'game_types' => [
                    ProductGame::$game_type['fishing'] => 1,
                ],
            ],
            'awc_tgp_wallet' => [
                'name' => "AWC's TGP",
                'currencies' => ['IDR'],
                'game_types' => [
                    ProductGame::$game_type['slot'] => 1,
                ],
            ],
            'awc_kingmaker_wallet' => [
                'name' => "AWC's KingMaker",
                'currencies' => ['IDR'],
                'game_types' => [
                    ProductGame::$game_type['p2p'] => 1,
                ],
            ],
            'ezugi_wallet' => [
                'name' => "Ezugi",
                'currencies' => ['IDR'],
                'game_types' => [
                    ProductGame::$game_type['live-dealer'] => 1,
                ],
            ],
            'gd_wallet' => [
                'name' => "Gold Deluxe",
                'currencies' => [
                    System::$currency['IDR'],
                ],
                'game_types' => [
                    ProductGame::$game_type['live-dealer'] => 1,
                ],
            ],
            'pragmaticplay_wallet' => [
                'name' => "PragmaticPlay",
                'currencies' => ['IDR'],
                'game_types' => [
                    ProductGame::$game_type['slot'] => 1,
                    ProductGame::$game_type['live-dealer'] => 1,
                ],
            ],
            'habanero_wallet' => [
                'name' => "Habanero",
                'currencies' => ['IDR'],
                'game_types' => [
                    ProductGame::$game_type['slot'] => 1, 
                ],
            ],
            // 'trilion_isin4d_wallet' => [
            //     'name' => "Trilion Isin4D",
            //     'currencies' => ['IDR'],
            //     'game_types' => [
            //         ProductGame::$game_type['lottery'] => 1, 
            //     ],
            // ],
        ];

        foreach ($product_game_type as $wallet => $value) {
            $product = Product::where('wallet', $wallet)
                                ->first();
            // If product not exist then proceed to create
            if ($product === null) {
                $product = Product::create([
                    'name' => $value['name'],
                    'wallet' => $wallet,
                    'main' => 0,
                    'currencies' => json_encode($value['currencies']),
                    'last_modified_by' => $request->user('web_admin')->id,
                    'status' => Product::$status['inactive'],
                ]);
            }

            foreach ($value['game_types'] as $game_type => $have_game_api) {
                $type_exist = ProductGame::where('product_id', $product->id)
                                        ->where('game_type', $game_type)
                                        ->exists();
                // If not exist then proceed to create
                if (! $type_exist) {
                    ProductGame::create([
                        'product_id' => $product->id,
                        'game_type' => $game_type,
                        'have_game_api' => $have_game_api,
                        'last_modified_by' => $request->user('web_admin')->id,
                        'status' => Product::$status['active'],
                    ]);
                }
            }
        }

        return;
    }
}
