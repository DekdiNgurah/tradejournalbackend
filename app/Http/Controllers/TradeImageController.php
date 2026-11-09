<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TradeImage;
use App\Http\Resources\TradeImageResource;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class TradeImageController extends Controller
{
    public function store(Request $request, string $tradeId) {
        $trade = $request->user()->trades()->findOrFail($tradeId);

        $validated = $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:1024',
        ]);

        $path = $validated['image']->store('trades', 'public');
        if ($path === false) {
            return response()->json([
                'message' => 'Gambar gagal disimpan.',
            ], 500);
        }

        foreach ($trade->images as $existingImage) {
            Storage::disk('public')->delete($existingImage->image_path);
        }
        $trade->images()->delete();

        $tradeImage = $trade->images()->create([
            'image_path' => $path,
        ]);

        return new TradeImageResource($tradeImage);
    }

    public function destroy(Request $request, string $imageId) {
        $tradeImage = TradeImage::whereHas('trade', function ($query) use ($request) {
            $query->where('user_id', $request->user()->id);
        })->findOrFail($imageId);

        if (Storage::disk('public')->exists($tradeImage->image_path)) {
            Storage::disk('public')->delete($tradeImage->image_path);
        }

        $tradeImage->delete();

        return response()->json([
            'message' => 'Gambar berhasil dihapus.'
        ], 200);
    }
}
