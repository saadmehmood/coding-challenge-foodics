<?php

namespace Tests\Feature;

use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 * Class OrderControllerTest
 * @package Tests\Unit
 */
class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateOrderSuccess()
    {
        $product = $this->createProduct();

        $params = $this->validParams($product);

        $this->postJson(route('orders.store', $params))->assertCreated()->json('data');
    }

    public function testCreateOrderFailure()
    {
        $params = $this->invalidParams();

        $this->postJson(route('orders.store', $params))->assertStatus(422);
    }

    public function testOrderStoredInDB()
    {
        $product = $this->createProduct();

        $params = $this->validParams($product);

        $this->postJson(route('orders.store', $params))->assertCreated();
        $this->assertDatabaseHas('order_product', $params['products'][0]);

    }

    public function testUsedStockUpdated()
    {
        $product = $this->createProduct();

        $params = $this->validParams($product);

        $response = $this->postJson(route('orders.store', $params))->assertCreated();
        $response = json_decode($response->getContent(), true);
        $data = $response['data'];
        $this->assertEquals(10, $data['products'][0]['ingredients'][0]['usedStock']);
    }

    public function testStockAlertUpdated()
    {
        $product = $this->createProduct();

        $params = $this->validParams($product, 2);

        $response = $this->postJson(route('orders.store', $params))->assertCreated();
        $response = json_decode($response->getContent(), true);

        Mail::fake();

        $data = $response['data'];
        $this->assertEquals(1, $data['products'][0]['ingredients'][0]['stockAlertSent']);
    }

    public function testOrderFailureIngredientOutOfStock()
    {
        $product = $this->createProduct(30, 20);

        $params = $this->validParams($product, 2);

        $this->postJson(route('orders.store', $params))
            ->assertStatus(500)
            ->assertJson(fn(AssertableJson $json) => $json->has('message'));
    }

    private function validParams($product, $quantity = 1): array
    {
        return [
            'products' => [
                [
                    'product_id' => $product->id,
                    'quantity' => $quantity
                ]
            ]
        ];
    }

    private function invalidParams(): array
    {
        return [
            'products' => [
                [
                    'product_id' => 1000,
                    'quantity' => 0
                ]
            ]
        ];
    }

    private function createProduct($stock = 30, $quantity = 10)
    {
        $product = Product::factory()->create(['name' => 'burger']);
        $ingredients = Ingredient::factory()->count(4)->sequence(
            ['name' => 'cheese', 'stock' => $stock],
            ['name' => 'bun', 'stock' => $stock],
            ['name' => 'egg', 'stock' => $stock],
            ['name' => 'sauce', 'stock' => $stock])->create();

        $product->ingredients()->syncWithPivotValues($ingredients, ['quantity' => $quantity]);

        return $product;
    }
}
