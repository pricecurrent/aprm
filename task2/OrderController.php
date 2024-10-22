<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\CartItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orderData = Order::query()
            ->with(['customer', 'items', 'lastAddedItem'])
            ->latest('completed_at')
            ->lazy()
            ->map(function ($order) {
                return [
                    'order_id' => $order->id,
                    'customer_name' => $order->customer->name,
                    'total_amount' => $order->getTotalAmount(),
                    'items_count' => $order->getItemsCount(),
                    'last_added_to_cart' => $order->lastAddedItem?->created_at,
                    'completed_order_exists' => $order->isCompleted(),
                    'created_at' => $order->created_at,
                ];
            });


        return view('orders.index', ['orders' => $orderData]);
    }
}

