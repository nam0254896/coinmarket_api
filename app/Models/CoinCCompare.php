<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinCCompare extends Model
{
    use HasFactory;
    protected $table = 'coinccompare';
    protected $fillable = [ 'symbol', 'partner_symbol', 'data_available_from'];
}
