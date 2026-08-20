<?php

namespace App\Services;

use App\Models\ProductVariant;
use Illuminate\Support\Collection;

class Cart
{
    protected const SESSION_KEY = 'cart';

    public function add(int $variantId, int $quantity = 1): void
    {
        $cart = $this->raw();
        $cart[$variantId] = ($cart[$variantId] ?? 0) + $quantity;
        $this->save($cart);
    }

    public function update(int $variantId, int $quantity): void
    {
        $cart = $this->raw();

        if ($quantity <= 0) {
            unset($cart[$variantId]);
        } else {
            $cart[$variantId] = $quantity;
        }

        $this->save($cart);
    }

    public function remove(int $variantId): void
    {
        $cart = $this->raw();
        unset($cart[$variantId]);
        $this->save($cart);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    /**
     * Re-resolves every line from the database so stale price/stock/deleted
     * products in the session self-heal instead of showing bad data.
     */
    public function items(): Collection
    {
        $cart = $this->raw();

        if (empty($cart)) {
            return collect();
        }

        $variants = ProductVariant::with(['product.images' => fn ($q) => $q->orderBy('sort_order')])
            ->whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        $items = collect();
        $changed = false;

        foreach ($cart as $variantId => $quantity) {
            $variant = $variants->get($variantId);

            if (! $variant || ! $variant->is_active || ! $variant->product->is_active) {
                unset($cart[$variantId]);
                $changed = true;

                continue;
            }

            $quantity = min($quantity, $variant->stock);

            if ($quantity <= 0) {
                unset($cart[$variantId]);
                $changed = true;

                continue;
            }

            if ($quantity !== $cart[$variantId]) {
                $cart[$variantId] = $quantity;
                $changed = true;
            }

            $unitPrice = $variant->effective_price;

            $items->push((object) [
                'variant' => $variant,
                'quantity' => $quantity,
                'unitPrice' => $unitPrice,
                'lineTotal' => round($unitPrice * $quantity, 2),
            ]);
        }

        if ($changed) {
            $this->save($cart);
        }

        return $items;
    }

    public function count(): int
    {
        return array_sum($this->raw());
    }

    public function subtotal(): float
    {
        return round($this->items()->sum('lineTotal'), 2);
    }

    protected function raw(): array
    {
        return session(self::SESSION_KEY, []);
    }

    protected function save(array $cart): void
    {
        session([self::SESSION_KEY => $cart]);
    }
}
