<?php

namespace App\Console\Commands;

use App\Models\Coin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchCoinsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $url = 'https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&order=market_cap_desc&per_page=100&page=1&sparkline=false';
        $response = Http::get($url);

    if ($response->ok()) {
        $data = $response->json();
        foreach ($data as $coinData) {
            $coin = new Coin();
            $coin->name = $coinData['name'];
            $coin->symbol = $coinData['symbol'];
            $coin->current_price = $coinData['current_price'];
            $coin->market_cap = $coinData['market_cap'];
            $coin->market_cap_rank = $coinData['market_cap_rank'];
            $coin->total_volume = $coinData['total_volume'];
            $coin->high_24h = $coinData['high_24h'];
            $coin->low_24h = $coinData['low_24h'];
            $coin->price_change_24h = $coinData['price_change_24h'];
            $coin->price_change_percentage_24h = $coinData['price_change_percentage_24h'];
            $coin->market_cap_change_24h = $coinData['market_cap_change_24h'];
            $coin->market_cap_change_percentage_24h = $coinData['market_cap_change_percentage_24h'];
            $coin->circulating_supply = $coinData['circulating_supply'];
            $coin->total_supply = $coinData['total_supply'];
            $coin->max_supply = $coinData['max_supply'];
            $coin->ath = $coinData['ath'];
            $coin->ath_change_percentage = $coinData['ath_change_percentage'];
            $coin->ath_date = $coinData['ath_date'];
            $coin->atl = $coinData['atl'];
            $coin->atl_change_percentage = $coinData['atl_change_percentage'];
            $coin->atl_date = $coinData['atl_date'];
            $coin->last_updated = $coinData['last_updated'];
            $coin->save();
        }
        $this->info('Data saved successfully.');
    } else {
        $this->error('Failed to fetch data.');
    }
    }
}
