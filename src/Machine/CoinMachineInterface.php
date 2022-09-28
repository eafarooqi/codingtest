<?php

namespace App\Machine;

/**
 * Interface CoinMachine
 * @package App\Machine
 */
interface CoinMachineInterface
{
    const AVAILABLE_COINS = [
        2,
        1,
        0.5,
        0.2,
        0.1,
        0.05,
        0.02,
        0.01
    ];

    /**
     * @param float $amount
     * @return array
     */
    public function calculateChange(float $amount): array;
}