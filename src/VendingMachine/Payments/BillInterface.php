<?php

namespace Softia\Challenge\CoffeeMachine\VendingMachine\Payments;

interface BillInterface {

    /**
     * Get banknote value
     *
     * @return int
     */
    public function getValue(): int;
}
