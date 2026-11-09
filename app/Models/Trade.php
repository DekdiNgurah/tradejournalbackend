<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{

    // use HasFactory;

    protected $fillable = [
        'user_id',
        'trade_date',
        'pair',
        'direction',
        'entry_price',
        'exit_price',
        'lot_size',
        'pnl',
        'follow_plan',
        'notes',
    ];

    protected $casts = [
        'trade_date' => 'date',
        'follow_plan' => 'boolean',
        'entry_price' => 'decimal:5',
        'exit_price' => 'decimal:5',
        'lot_size' => 'decimal:3',
        'pnl' => 'decimal:2',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function images() {
        return $this->hasMany(TradeImage::class);
    }
    
}
