<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TradeResource extends JsonResource
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
            'user_id' => $this->user_id,

            'trade_date' => $this->trade_date ? $this->trade_date->format('Y-m-d') : null,
            
            'pair' => $this->pair,
            'direction' => $this->direction,
            
            'entry_price' => (float) $this->entry_price,
            'exit_price' => (float) $this->exit_price,
            'lot_size' => (float) $this->lot_size,
            'pnl' => (float) $this->pnl,
            'follow_plan' => (bool) $this->follow_plan,
            
            'notes' => $this->notes,
            
            'images' => TradeImageResource::collection($this->whenLoaded('images')),
            
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),

        ];
    }
}
