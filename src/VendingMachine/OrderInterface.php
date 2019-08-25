<?php

namespace Softia\Challenge\CoffeeMachine\VendingMachine;

interface OrderInterface {

    /**
     * Get order product
     *
     * @return Product
     */
    public function getProduct(): ProductInterface;

    /**
     * Check if order is ready
     *
     * @return bool
     */
    public function isReady(): bool;
}
