<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
{
    public function pnl(Request $request) {
        $month = $request->query('month', date('Y-m'));

        $pnlRecords = $request->user()
        ->trades()
        ->where('trade_date', 'like', "{$month}%")
        ->select(
            'trade_date',
            DB::raw('sum(pnl) as total_pnl'),
            DB::raw('count(id) as total_trades')
        )
        ->groupBy('trade_date')
        ->get()
        ->map(function ($item) {
            return [
                'trade_date' => $item->trade_date->format('Y-m-d'),
                'total_pnl' => (float) $item->total_pnl,
                'total_trades' => (int) $item->total_trades,
            ];
        });

        return response()->json([
            'month' => $month,
            'data' => $pnlRecords,
        ], 200);

    }
}
