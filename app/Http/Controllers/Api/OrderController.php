<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Http\Services\OrderService;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    /**
     * @var OrderService
     */
    private OrderService $orderService;

    /**
     * @param OrderService $orderService
     */
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * @param OrderRequest $request
     * @return JsonResponse|OrderResource
     */
    public function store(OrderRequest $request): JsonResponse|OrderResource
    {
        return $this->orderService->storeOrder($request->input('products'));
    }
}
