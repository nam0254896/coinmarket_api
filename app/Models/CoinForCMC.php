<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinForCMC extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'symbol',
        'slug',
        'num_market_pairs',
        'date_added',
        'tags',
        'max_supply',
        'circulating_supply',
        'total_supply',
        'platform',
        'cmc_rank',
        'self_reported_circulating_supply',
        'self_reported_market_cap',
        'tvl_ratio',
        'cmc_rank',
        'last_updated',
        'price',
        'volume_24h',
        'percent_change_1h',
        'percent_change_24h',
        'percent_change_7d',
        'percent_change_30d',
        'percent_change_60d',
        'percent_change_90d',
        'market_cap',
        'fully_diluted_market_cap',
        'tvl',
        'last_updated_quote',
    ];

    protected $table = 'coin_for_c_m_c_s';
}
