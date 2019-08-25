<?php

namespace Softia\Challenge\CoffeeMachine\VendingMachine\Payments;

interface ReceiptInterface {

    /**
     * Get receipt total
     *
     * @return int
     */
    public function getTotal(): int;

    /**
     * Get products on receipt
     *
     * @return array
     */
    public function getProducts(): array;
}
