<?php

namespace App\Http\Services;

use App\Http\Repositories\OrderRepository;
use App\Http\Resources\OrderResource;
use App\Mail\IngredientLowStockAlert;
use App\Models\Product;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderService
{
    /**
     * @var OrderRepository $orderRepository
     */
    private OrderRepository $orderRepository;

    /**
     * @param OrderRepository $orderRepository
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param array $products
     * @return OrderResource|JsonResponse
     */
    public function storeOrder(array $products)
    {
        // Begin a database transaction
        DB::beginTransaction();

        try {
            $order = $this->orderRepository->create([]);

            $productsData = [];

            foreach ($products as $productInput) {
                $product = Product::find($productInput['product_id']);
                $quantity = $productInput['quantity'];

                $productsData[$product->id] = ['quantity' => $quantity];

                // Update the stock of each ingredient
                $this->updateIngredientStock($product, $quantity);
            }

            // Create a new pivot row in the order_product table
            $order->products()->sync($productsData);

            // Commit the transaction
            DB::commit();

            return new OrderResource($order);
        } catch (Exception $exception) {
            // Roll back the transaction
            DB::rollBack();

            // Return an error response
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    /**
     * @throws Exception
     */
    private function updateIngredientStock($product, int $productQuantity): void
    {
        $productIngredients = $product->ingredients;

        foreach ($productIngredients as $ingredient) {
            // Calculate the amount of ingredient used in this order
            $ingredient->used_stock += $ingredient->pivot->quantity * $productQuantity;
            $ingredient->save();

            if ($ingredient->used_stock > $ingredient->stock) {
                throw new Exception('Insufficient stock for ingredient: ' . $ingredient->name);
            } elseif ($ingredient->used_stock > 0.5 * $ingredient->stock && !$ingredient->stock_alert_sent) {
                // Send an email alert if the stock is below 50%
                Mail::to('saadmehmood758@gmail.com')->send(new IngredientLowStockAlert($ingredient));
                $ingredient->stock_alert_sent = true;
                $ingredient->save();
            }
        }
    }
}
