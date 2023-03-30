<?php

namespace App\Http\Controllers;

use App\Models\CoinForCMC;
use App\Models\CoinCCompare;
use App\Models\Coin;
use App\Models\CoinValue;
use DateTime;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Mail\Transport\ArrayTransport;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Storage;
use LengthException;

use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class coinController extends Controller
{
    public function getCoinIntoDatabase(Request $request)
    {
        ini_set('max_execution_time', 600);
        ini_set('max_input_time', 600);
        $convert = $request->input('convert');
        if (empty($convert) || is_null($convert)) {
            $convert = 'USD';
        }
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
        // $headers = [
        //     'Accepts: application/json',
        //     'X-CMC_PRO_API_KEY: ' . $key,
        // ];
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
        // dd($data);
        // $values = implode(',', $array);
        // dd($coins); 
        try {
            foreach ($data as $coin) {
                $database = DB::table('coin_for_c_m_c_s')->get();
                // dd($database);
                $tags = json_encode($coin['tags']) . implode(',', $coin['tags']);
                $tags = str_replace('"', '', $tags);
                $tags = str_replace('[', '', $tags);
                $tags = str_replace(']', '', $tags);
                // dd($tags);
                $existingCoin = CoinForCMC::where('symbol', $coin['symbol'])->first();
                $checkCoin = $database->where('symbol', $coin['symbol'])->first();
                // dd($existingCoin);
                // dd($existingCoin !== $checkCoin, $existingCoin, $checkCoin);
                if ($existingCoin !== $checkCoin) {
                    // $existingCoin->name = $coin['name'];
                    // $existingCoin->symbol = $coin['symbol'];
                    // $existingCoin->slug = $coin['slug'];
                    $existingCoin->num_market_pairs = $coin['num_market_pairs'];
                    $existingCoin->date_added = $coin['date_added'];
                    $existingCoin->tags = $tags;
                    $existingCoin->max_supply = $coin['max_supply'];
                    $existingCoin->circulating_supply = $coin['circulating_supply'];
                    $existingCoin->total_supply = $coin['total_supply'];
                    // $existingCoin->platform = $coin['platform'];
                    $existingCoin->cmc_rank = $coin['cmc_rank'];
                    // $existingCoin->self_reported_circulating_supply = $coin['self_reported_circulating_supply'];
                    // $existingCoin->self_reported_market_cap = $coin['self_reported_market_cap'];
                    $existingCoin->tvl_ratio = $coin['tvl_ratio'];
                    $existingCoin->last_updated = $coin['last_updated'];
                    $existingCoin->price = $coin['quote'][$convert]['price'];
                    $existingCoin->volume_24h = $coin['quote'][$convert]['volume_24h'];
                    $existingCoin->percent_change_1h = $coin['quote'][$convert]['percent_change_1h'];
                    $existingCoin->percent_change_24h = $coin['quote'][$convert]['percent_change_24h'];
                    $existingCoin->percent_change_7d = $coin['quote'][$convert]['percent_change_7d'];
                    $existingCoin->percent_change_30d = $coin['quote'][$convert]['percent_change_30d'];
                    $existingCoin->percent_change_60d = $coin['quote'][$convert]['percent_change_60d'];
                    $existingCoin->percent_change_90d = $coin['quote'][$convert]['percent_change_90d'];
                    $existingCoin->market_cap = $coin['quote'][$convert]['market_cap'];
                    $existingCoin->fully_diluted_market_cap = $coin['quote'][$convert]['fully_diluted_market_cap'];
                    $existingCoin->tvl = $coin['quote'][$convert]['tvl'];
                    $existingCoin->last_updated_quote = $coin['quote'][$convert]['last_updated'];
                    $existingCoin->save();
                    // break;
                } else {
                    CoinForCMC::updateOrCreate([
                        'id' => $coin['id'],
                    ], [
                        'name' => $coin['name'],
                        'symbol' => $coin['symbol'],
                        'slug' => $coin['slug'],
                        'num_market_pairs' => $coin['num_market_pairs'],
                        'date_added' => $coin['date_added'],
                        $tags => $coin['tags'],
                        'max_supply' => $coin['max_supply'],
                        'circulating_supply' => $coin['circulating_supply'],
                        'total_supply' => $coin['total_supply'],
                        // 'platform' => $coin['platform'],
                        'cmc_rank' => $coin['cmc_rank'],
                        // 'self_reported_circulating_supply' => $coin['self_reported_circulating_supply'],
                        // 'self_reported_market_cap' => $coin['self_reported_market_cap'],
                        'tvl_ratio' => $coin['tvl_ratio'],
                        'last_updated' => $coin['last_updated'],
                        'price' => $coin['quote'][$convert]['price'],
                        'volume_24h' => $coin['quote'][$convert]['volume_24h'],
                        'volume_change_24h' => $coin['quote'][$convert]['volume_change_24h'],
                        'percent_change_1h' => $coin['quote'][$convert]['percent_change_1h'],
                        'percent_change_24h' => $coin['quote'][$convert]['percent_change_24h'],
                        'percent_change_7d' => $coin['quote'][$convert]['percent_change_7d'],
                        'percent_change_30d' => $coin['quote'][$convert]['percent_change_30d'],
                        'percent_change_60d' => $coin['quote'][$convert]['percent_change_60d'],
                        'percent_change_90d' => $coin['quote'][$convert]['percent_change_90d'],
                        'market_cap' => $coin['quote'][$convert]['market_cap'],
                        'market_cap_dominance' => $coin['quote'][$convert]['market_cap_dominance'],
                        'fully_diluted_valuation' => $coin['quote'][$convert]['fully_diluted_market_cap'],
                        'tvl' => $coin['quote'][$convert]['tvl'],
                        'last_updated_quote' => $coin['quote'][$convert]['last_updated'],
                        // add more columns as necessary

                    ]);
                }
                // break;
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Coin data not updated',
                'error' => $e->getMessage(),
            ], 500);
        }
        return response()->json([
            'message' => 'Coin data updated successfully',
            'data' => DB::table('coin_for_c_m_c_s')->get(),
        ], 200);;
    }

    public function updateDatabeCoin(Request $request)
    {
        ini_set('max_execution_time', 600);
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', 'https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&order=market_cap_desc&per_page=5000&page=1&sparkline=false');

        // Chuyển đổi dữ liệu API từ JSON sang mảng PHP
        $data = json_decode($res->getBody(), true);
        // dd($data);
        try {
            foreach ($data as $coin) {
                $database = DB::table('coins')->get();
                $existingCoinByDatabase = $database->where('symbol', $coin['symbol'])->first();
                $existingCoin = Coin::Where('symbol', $coin['symbol'])->first();
                if ($existingCoin !== $existingCoinByDatabase) {
                    $existingCoin->id_name = $coin['id'];
                    $existingCoin->name = $coin['name'];
                    $existingCoin->symbol = $coin['symbol'];
                    $existingCoin->image = $coin['image'];
                    $existingCoin->current_price = $coin['current_price'];
                    $existingCoin->market_cap = $coin['market_cap'];
                    $existingCoin->market_cap_rank = $coin['market_cap_rank'];
                    $existingCoin->fully_diluted_valuation = $coin['fully_diluted_valuation'];
                    $existingCoin->total_volume = $coin['total_volume'];
                    $existingCoin->high_24h = $coin['high_24h'];
                    $existingCoin->low_24h = $coin['low_24h'];
                    $existingCoin->price_change_24h = $coin['price_change_24h'];
                    $existingCoin->price_change_percentage_24h = $coin['price_change_percentage_24h'];
                    $existingCoin->market_cap_change_24h = $coin['market_cap_change_24h'];
                    $existingCoin->market_cap_change_percentage_24h = $coin['market_cap_change_percentage_24h'];
                    $existingCoin->circulating_supply = $coin['circulating_supply'];
                    $existingCoin->total_supply = $coin['total_supply'];
                    $existingCoin->max_supply = $coin['max_supply'];
                    $existingCoin->ath = $coin['ath'];
                    $existingCoin->ath_change_percentage = $coin['ath_change_percentage'];
                    $existingCoin->ath_date = $coin['ath_date'];
                    $existingCoin->atl = $coin['atl'];
                    $existingCoin->atl_change_percentage = $coin['atl_change_percentage'];
                    $existingCoin->atl_date = $coin['atl_date'];
                    // $existingCoin->roi = $coin['roi'];
                    $existingCoin->last_updated = $coin['last_updated'];
                    $existingCoin->save();
                } else {
                    Coin::updateOrCreate([
                        'symbol' => $coin['symbol'],
                    ], [
                        'id_name' => $coin['id'],
                        'name' => $coin['name'],
                        'symbol' => $coin['symbol'],
                        'image' => $coin['image'],
                        'current_price' => $coin['current_price'],
                        'market_cap' => $coin['market_cap'],
                        'market_cap_rank' => $coin['market_cap_rank'],
                        'fully_diluted_valuation' => $coin['fully_diluted_valuation'],
                        'total_volume' => $coin['total_volume'],
                        'high_24h' => $coin['high_24h'],
                        'low_24h' => $coin['low_24h'],
                        'price_change_24h' => $coin['price_change_24h'],
                        'price_change_percentage_24h' => $coin['price_change_percentage_24h'],
                        'market_cap_change_24h' => $coin['market_cap_change_24h'],
                        'market_cap_change_percentage_24h' => $coin['market_cap_change_percentage_24h'],
                        'circulating_supply' => $coin['circulating_supply'],
                        'total_supply' => $coin['total_supply'],
                        'max_supply' => $coin['max_supply'],
                        'ath' => $coin['ath'],
                        'ath_change_percentage' => $coin['ath_change_percentage'],
                        'ath_date' => $coin['ath_date'],
                        'atl' => $coin['atl'],
                        'atl_change_percentage' => $coin['atl_change_percentage'],
                        'atl_date' => $coin['atl_date'],
                        // 'roi' => $coin['roi'],
                        'last_updated' => $coin['last_updated'],
                    ]);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Coin data not updated',
                'error' => $e->getMessage(),
            ], 500);
        }
        return response()->json([
            'message' => 'Coin data updated successfully',
            'data' => DB::table('coins')->get(),
        ], 200);;
    }
    public function updateChartCoinIntoDatabase(Request $request)
    {
        $client = new \GuzzleHttp\Client();
        $key = env('COINTOCOMPARE_API_KEY');
        $res = Storage::get('public/jsonCompare.txt');
        // dd($res);
        $data = json_decode($res, true)['Data'];
        // dd($data);
        try {
            foreach ($data as $coin) {
                $database = DB::table('coinccompare')->get();
                $existingCoinByDatabase = $database->where('symbol', $coin['symbol'])->first();
                $existingCoin = Coinccompare::Where('symbol', $coin['symbol'])->first();
                if ($existingCoin !== $existingCoinByDatabase) {
                    $existingCoin->partner_symbol = $coin['partner_symbol'];
                    $existingCoin->data_available_from = Carbon::createFromTimestamp($coin['data_available_from']);
                    $existingCoin->save();
                } else {
                    Coinccompare::updateOrCreate([
                        'id' => $coin['id'],
                    ], [
                        'symbol' => $coin['symbol'],
                        'partner_symbol' => $coin['partner_symbol'],
                        'data_available_from' => Carbon::createFromTimestamp($coin['data_available_from']),
                    ]);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Coin data not updated',
                'error' => $e->getMessage(),
            ], 500);
        }
        return response()->json([
            'message' => 'Coin data updated successfully',
            'data' => DB::table('coinccompare')->get(),
        ], 200);;
    }
    public function forHourto(Request $request)
    {
        $checkSymbol = $request->input('symbol');
        $coins = DB::table('coinccompare')->get();
        // dd($coins->where('symbol', $checkSymbol)->first());
        $coinsValue = DB::table('coin_values')->get();
        // dd($checkSymbol === $coins->where('symbol', $checkSymbol)->first()->symbol);
        $client = new \GuzzleHttp\Client();
        try{
        foreach($coinsValue as $coinsValues){
            dd(isNull($coinsValues));
            if(isNull($coinsValues)){
                $response = Http::get('https://min-api.cryptocompare.com/data/v2/histohour', [
                    'fsym' => $checkSymbol,
                    'tsym' => 'USD',
                    'limit' => 10,
                ]);
                dd($response->json());
                
            }else{
                $coinsValue = DB::table('coin_values')->delete($checkSymbol);
                echo($coinsValue);
            }
            break;
        }}catch(\Exception $e){
            return response()->json([
                'message' => 'Coin Value data not updated',
                'error' => $e->getMessage(),
            ], 500);
        }
        return response()->json([
            'message' => 'Coin Value data updated successfully',
            'data' => DB::table('coin_values')->get(),
        ], 200);;
    }
    public function importDataWithApi(Request $request){
        ini_set('max_execution_time', 600);
        ini_set('max_input_time', 600);
        $client = new \GuzzleHttp\Client();
        $url = env('APP_URL');
        try{
            $res = $client->request('GET', ''.$url.':8000/api'.'/updateCoinIntoDatabase');
            $res2 = $client->request('GET', ''.$url.':8000/api'.'/updateChartCoinIntoDatabase');
            $res3 = $client->request('GET', ''.$url.':8000/api'.'/callvalue/forHourto');
            $res4 = $client->request('GET', ''.$url.':8000/api'.'/getCoinIntoDatabase');
        }
        catch(\Exception $e){
            return response()->json([
                'message' => 'Coin Value data not updated',
                'error' => $e->getMessage(),
            ], 500);
        }
        return response()->json([
            'message' => 'Coin In MySQL data updated successfully',
            // 'data' => DB::table('coin_values')->get(),
        ], 200);;
    }
}
