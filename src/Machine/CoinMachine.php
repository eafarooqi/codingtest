<?php

namespace App\Machine;

/**
 * Class CoinMachine
 * @package App\Machine
 */
class CoinMachine implements CoinMachineInterface
{
    /**
     * @param float $amount
     * @return array
     */
    public function calculateChange(float $amount): array
    {
        $changeCoins = [];
        $amountInCent = (int)$this->convertToCent($amount);

        foreach (self::AVAILABLE_COINS as $coin)
        {
            $coinCentValue = $this->convertToCent($coin);
            $totalCoins = (int)($amountInCent / $coinCentValue);
            $amountInCent -= $coinCentValue * $totalCoins;

            if ($totalCoins)
            {
                $changeCoins[] = array($coin, $totalCoins);
            }
        }

        return $changeCoins;
    }

    /**
     * convert amount to cent value
     *
     * @param float $amount
     * @return float
     */
    private function convertToCent(float $amount): float
    {
        return $amount * 100;
    }
}