<?php

namespace Softia\Challenge\CoffeeMachine\Client;

class CashBag implements CashBagInterface
{

    /**
     * Get wallet cash contents
     *
     * @return arrat
     */
    public function getContents(): array {
        return [
            '100' => 1,
            '50' => 2,
            '10' => 5,
        ];
    }
}