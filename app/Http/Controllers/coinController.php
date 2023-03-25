<?php

namespace App\Http\Controllers;

use App\Models\Coin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class coinController extends Controller
{
    public function getCoinIntoDatabase(Request $request)
    {   
        $convert = $request->input('convert');
        $url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
        $parameters = [
        'start' => 1,
        'limit' => 5000,
        'convert' => $convert,
        // 'sort' => 'market_cap',
        // 'sort_dir' => 'desc',
        ];
    $key = env('COINMARKETCAP_API_KEY');
    // dd($key);
    $headers = [
        'Accepts: application/json',
        'X-CMC_PRO_API_KEY: '.$key,
    ];
    // dd($headers);
    $client = new \GuzzleHttp\Client();
    $response = $client->request('GET', $url, [
        'headers' => [
            'X-CMC_PRO_API_KEY' => $key,
        ],
        'query' => $parameters,
    ]);
    // dd($response);
    $data = json_decode($response->getBody(), true)['data'];
    // $array = json_decode($response->getBody(), true)['data']['quote'];
    dd($data);
    // $values = implode(',', $array);
        // dd($coins); 
        // foreach ($data as $coin) {
        //     Coin::updateOrCreate([
        //         'id' => $coin['id'],
        //     ], [
        //         'id_name' => $coin['id'],
        //         'name' => $coin['name'],
        //         'symbol' => $coin['symbol'],
        //         'image' => $coin['image'],
        //         'current_price' => $coin['current_price'],
        //         'market_cap' => $coin['market_cap'],
        //         'market_cap_rank' => $coin['market_cap_rank'],
        //         'total_volume' => $coin['total_volume'],
        //         'high_24h' => $coin['high_24h'],
        //         'low_24h' => $coin['low_24h'],
        //         'price_change_24h' => $coin['price_change_24h'],
        //         'price_change_percentage_24h' => $coin['price_change_percentage_24h'],
        //         'market_cap_change_24h' => $coin['market_cap_change_24h'],
        //         'market_cap_change_percentage_24h' => $coin['market_cap_change_percentage_24h'],
        //         'circulating_supply' => $coin['circulating_supply'],
        //         'total_supply' => $coin['total_supply'],
        //         'max_supply' => $coin['max_supply'],
        //         'ath' => $coin['ath'],
        //         'ath_change_percentage' => $coin['ath_change_percentage'],
        //         'ath_date' => $coin['ath_date'],
        //         'atl' => $coin['atl'],
        //         'atl_change_percentage' => $coin['atl_change_percentage'],
        //         'atl_date' => $coin['atl_date'],
        //         'last_updated' => $coin['last_updated'],
        //         'fully_diluted_valuation' => $coin['fully_diluted_valuation'],
        //         // add more columns as necessary
        //     ]);
        // }
        
        return 'Coins saved to database.';
    }
}
