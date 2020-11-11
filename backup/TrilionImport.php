<?php

namespace App\Imports;

use App\Product;
use App\ProductGame;
use App\ProductGameAPI;
use App\System;
use App\Products\Trilion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TrilionImport implements ToCollection, WithHeadingRow
{
    /**
     * Create a new class instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->trilion = new Trilion();
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $locales = array_keys(System::$locales);
        $file_extension = 'png';
        
        $productGames = [];
        $system_game_types = DB::table('product_games')
                                ->select('product_games.id', 'products.wallet', 'product_games.game_type')
                                ->join('products', 'products.id', '=', 'product_games.product_id')
                                ->whereIn('products.wallet', $this->avengersx->product_wallets)
                                ->get();
        foreach ($system_game_types as $key => $value) {
            $productGames[$value->wallet][$value->game_type] = $value->id;
        }
        echo "<br>Importing Trilion Isin4D Games <br><br>";

        $filter_game_type = [];
        foreach (Trilion::$game_type as $system_game_type => $api_game_types) {
            $filter_game_type = array_merge($filter_game_type, $api_game_types);
        }
        
        $games = [];
        foreach ($collection as $index => $row) 
        {
            $game_type = strval($row['game_type']);
            if ($row['game_type'] && in_array($game_type, $filter_game_type)) {
                $platform = strtoupper($row['platform']);
                $game_code = strval($row['game_code']);
                $exist = DB::table('product_game_api')
                            ->join('product_games', 'product_games.id', '=', 'product_game_api.product_game_id')
                            ->join('products', 'products.id', '=', 'product_games.product_id')
                            ->where('products.wallet', $this->avengersx->platform[$platform] ?? '')
                            ->where('product_game_api.game_code', $game_code)
                            ->exists();
                if (! $exist) {
                    $product_game_type = '';
                    foreach (Trilion::$game_type as $system_game_type => $api_game_types) {
                        if (in_array($game_type, $api_game_types)) {
                            $product_game_type = $system_game_type;
                            break;
                        }
                    }
                    
                    if (isset($this->avengersx->platform[$platform]) && ! empty($productGames[$this->avengersx->platform[$platform]][$product_game_type])) {
                        $product_game_id = $productGames[$this->avengersx->platform[$platform]][$product_game_type];
                    } else {
                        continue;
                    }
                    
                    $name = [];
                    $images = [];
                    $desktop_filename = str_replace(' ', '_', strtolower($row['en']."_desktop.{$file_extension}"));
                    $mobile_filename = str_replace(' ', '_', strtolower($row['en']."_mobile.{$file_extension}"));

                    foreach ($locales as $locale) {
                        $name[$locale] = $row[$locale];
    
                        $images[$locale]['desktop'] = $desktop_filename;
                        $images[$locale]['mobile'] = $mobile_filename;
                        $images[$locale]['alt'] = $row[$locale];
                    }
                    // echo "{$desktop_filename} <br>";
                    // echo "{$mobile_filename} <br>";

                    $extra_info = [
                        'platform' => $platform,
                    ];
                    
                    ProductGameAPI::create([
                        'product_game_id' => $product_game_id,
                        'game_code' => $game_code,
                        'name' => json_encode($name),
                        'image' => json_encode($images),
                        'extra_info' => json_encode($extra_info),
                        'status' => ProductGameAPI::$status['active'],
                    ]);
                    echo "{$platform} - {$row['en']} ({$game_code}) inserted... <br>";
                } else {
                    echo "{$platform} - {$row['en']} ({$game_code}) existed, skipped... <br>";
                }
            }
        }
    }
}
