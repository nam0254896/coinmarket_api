<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinValue extends Model
{
    use HasFactory;

    protected $table = 'coin_values';
    protected $fillable = [
        'coin_id',
        'open',
        'high',
        'low',
        'close',
        'volumefrom',
        'volumeto',
        'date',
    ];

    public function setCoinValue($coinId, $open, $high, $low, $close, $volumeto,$volumefrom, $date)
    {
        $this->coin_id = $coinId;
        $this->open = $open;
        $this->high = $high;
        $this->low = $low;
        $this->close = $close;
        $this->volumefrom = $volumefrom;
        $this->volumeto = $volumeto;
        $this->date = $date;
        $this->save();
    }
}
