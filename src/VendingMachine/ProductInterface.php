<?php

namespace Softia\Challenge\CoffeeMachine\VendingMachine;

interface ProductInterface {

    /**
     * Get product ID
     *
     * @return int
     */
    public function getId(): int;

    /**
     * Check if product can be ordered
     *
     * @return bool
     */
    public function isAvailable(): bool;
}
