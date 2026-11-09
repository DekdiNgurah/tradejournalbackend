<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function summary(Request $request) {
        $user = $request->user();

        $totalTrades = $user->trades()->count();

        $totalNetPnl = (float) $user->trades()->sum('pnl');

        $winningTrades = $user->trades()->where('pnl', '>', 0)->count();

        $followedPlanTrades = $user->trades()->where('follow_plan', true)->count();

        $winRate = $totalTrades > 0 ? round(($winningTrades / $totalTrades) * 100, 2) : 0;

        $followPlanRate = $totalTrades > 0 ? round(($followedPlanTrades / $totalTrades) * 100, 2) : 0;

        return response()->json([
            'data' => [
                'total_net_pnl'    => $totalNetPnl,
                'total_trades'     => $totalTrades,
                'win_rate'         => $winRate,
                'follow_plan_rate' => $followPlanRate,
            ]
        ], 200);
    }
}
