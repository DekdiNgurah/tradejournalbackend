<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use Illuminate\Http\Request;
use App\Http\Resources\TradeResource;
use App\Http\Controllers\Controller;

class TradeController extends Controller
{
    public function index(Request $request)
    {
        $trades = $request->user()
            ->trades()
            ->with(['images:id,trade_id,image_path'])
            ->orderBy('trade_date', 'desc')
            ->paginate(10);

        return TradeResource::collection($trades);
    }

    public function store(Request $request)
    {
        $normalizedDirection = strtolower((string) $request->input('direction', ''));

        if ($normalizedDirection !== '') {
            $request->merge(['direction' => $normalizedDirection]);
        }

        $validate = $request->validate([
            'trade_date'  => 'required|date',
            'pair'        => 'required|string|max:20',
            'direction'   => 'required|in:buy,sell',
            'entry_price' => 'required|numeric',
            'exit_price'  => 'required|numeric',
            'lot_size'    => 'required|numeric',
            'pnl'         => 'required|numeric',
            'follow_plan' => 'required|boolean',
            'notes'       => 'nullable|string',
        ]);

        $trades = $request->user()->trades()->create($validate);

        return (new TradeResource($trades))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, string $id)
    {
        $trade = $request->user()
            ->trades()
            ->with('images')
            ->findOrFail($id);

        return new TradeResource($trade);
    }

    public function update(Request $request, string $id)
    {
        $normalizedDirection = strtolower((string) $request->input('direction', ''));

        if ($normalizedDirection !== '') {
            $request->merge(['direction' => $normalizedDirection]);
        }

        $trade = $request->user()
            ->trades()
            ->with('images')
            ->findOrFail($id);

        $validated = $request->validate([
            'trade_date'  => 'required|date',
            'pair'        => 'required|string|max:20',
            'direction'   => 'required|in:buy,sell',
            'entry_price' => 'required|numeric',
            'exit_price'  => 'required|numeric',
            'lot_size'    => 'required|numeric',
            'pnl'         => 'required|numeric',
            'follow_plan' => 'required|boolean',
            'notes'       => 'nullable|string',
        ]);

        $trade->update($validated);

        return new TradeResource($trade);
    }

    public function destroy(Request $request, string $id)
    {
        $trade = $request->user()
            ->trades()
            ->with('images')
            ->findOrFail($id);

        $trade->delete();

        return response()->json([
            'message' => 'Transaksi berhasil dihapus'
        ], 200);
    }
}
