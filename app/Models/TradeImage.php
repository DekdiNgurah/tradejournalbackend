<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradeImage extends Model
{
    protected $fillable = [
        'trade_id', 
        'image_path',
    ];

    public function trade() {
        return $this->belongsTo(Trade::class);
    }
}
