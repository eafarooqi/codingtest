<?php

namespace App\Machine;

/**
 * Class CigaretteMachine
 * @package App\Machine
 */
class CigaretteMachine implements MachineInterface
{
    const ITEM_PRICE = 4.99;

    public function execute(PurchaseTransactionInterface $purchaseTransaction): PurchasedItem
    {
        $totalAmount = $purchaseTransaction->getItemQuantity() * self::ITEM_PRICE;
        $paidAmount = $purchaseTransaction->getPaidAmount();
        $restAmount = round($paidAmount - $totalAmount, 2);

        // getting the change in coins
        $change = (new CoinMachine())->calculateChange($restAmount);

        return new PurchasedItem($purchaseTransaction->getItemQuantity(), $totalAmount, $change);
    }
}