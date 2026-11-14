<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TradePaginationTest extends TestCase
{
    use RefreshDatabase;

    public function test_trade_list_paginates_ten_records_and_preserves_false_follow_plan(): void
    {
        $user = User::factory()->create();

        for ($index = 0; $index < 28; $index++) {
            $user->trades()->create([
                'trade_date' => '2026-11-' . str_pad((string) (($index % 28) + 1), 2, '0', STR_PAD_LEFT),
                'pair' => 'XAUUSD',
                'direction' => 'buy',
                'entry_price' => 100,
                'exit_price' => 101,
                'lot_size' => 0.1,
                'pnl' => 1,
                'follow_plan' => $index !== 27,
            ]);
        }

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/trades?page=1')
            ->assertOk()
            ->assertJsonPath('meta.total', 28)
            ->assertJsonPath('meta.per_page', 10)
            ->assertJsonCount(10, 'data')
            ->assertJsonPath('data.0.follow_plan', false);

        $this->getJson('/api/trades?page=3')
            ->assertOk()
            ->assertJsonCount(8, 'data');
    }

    public function test_trade_creation_keeps_the_submitted_calendar_date(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/trades', [
                'trade_date' => '2026-10-12',
                'pair' => 'EURUSD',
                'direction' => 'buy',
                'entry_price' => 1.1,
                'exit_price' => 1.2,
                'lot_size' => 0.1,
                'pnl' => 10,
                'follow_plan' => false,
                'notes' => 'Previous month entry',
            ])
            ->assertCreated()
            ->assertJsonPath('data.trade_date', '2026-10-12')
            ->assertJsonPath('data.follow_plan', false);
    }
}
