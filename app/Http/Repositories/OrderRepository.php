<?php

namespace App\Http\Repositories;

use App\Models\Order;

class OrderRepository
{
    /**
     * @var Order $order
     */
    private Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * @param array $orderData
     */
    public function create(array $orderData)
    {
        return $this->order->create($orderData);
    }
}
