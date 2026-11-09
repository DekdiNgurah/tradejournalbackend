<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TradeImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return [
            'id' => $this->id,
            'trade_id' => $this->trade_id,
            'image_url' => $this->image_path ? asset('storage/' . $this->image_path) : null,
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
