<?php

namespace App\Models;

class Order extends Model
{
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function recentItem()
    {
        return $this->belongsTo(OrderItem::class, 'last_added_item_id');
    }

    public function scopeLastAddedItem($query, Reader $reader)
    {
        return $query->addSelect(['last_added_item_id' => OrderItem::select('id')
            ->whereColumn('order_id', 'orders.id')
            ->orderBy('created_at', 'desc')
            ->limit(1)])
            ->with('recentItem');
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function getTotalAmount()
    {
        return $this->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }

    public function getItemsCount()
    {
        return $this->items->count();
    }
}
