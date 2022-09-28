<?php

namespace App\Machine;

/**
 * Class PurchasableItem
 * @package App\Machine
 */
class PurchaseTransaction implements PurchaseTransactionInterface
{
    private int $itemQuantity;
    private float $paidAmount;

    public function __construct(int $quantity, float $amount)
    {
        $this->itemQuantity = $quantity;
        $this->paidAmount = $amount;
    }

    public function getItemQuantity(): int
    {
        return $this->itemQuantity;
    }

    public function getPaidAmount(): float
    {
        return $this->paidAmount;

    }
}