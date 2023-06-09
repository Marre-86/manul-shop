<?php

namespace Tests\Feature\Order;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function testOrderIsStoredIntoDatabase(): void
    {
        $this->seed();
        $productData = [
            3 => [
                'price' => 89.90,
                'quantity' => 8
            ]
        ];
        $response = $this
            ->post(route('orders.store'), ['phone' => '+7-913-717', 'description' => 'Some text',
                                            'items' => $productData ]);

        $response->assertRedirectToRoute('prodlist');
        $this->assertDatabaseHas('orders', [
            'phone' => '+7-913-717', 'description' => 'Some text', 'status' => 'Awaiting Confirmation'
        ]);
        $this->assertDatabaseHas('order_product', [
            'product_id' => 3, 'quantity' => 8, 'price' => 89.90
        ]);
    }

    public function testInvalidPhoneNumberIsNotAccepted(): void
    {
        $this->seed();

        $response = $this
            ->post(route('orders.store'), ['phone' => '+7-913-717d', 'description' => 'Some text']);

        $response->assertInvalid(['phone']);
    }

    public function testCreatorIdIsAttachedToStoredOrder(): void
    {
        $this->seed();

        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('orders.store'), ['phone' => '+7-913-888']);

        $response->assertRedirectToRoute('orders.index');
        $this->assertDatabaseHas('orders', [
            'phone' => '+7-913-888', 'created_by_id' => $user->id
        ]);
    }
}
