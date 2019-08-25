<?php

namespace Softia\Challenge\CoffeeMachine\VendingMachine;

use Softia\Challenge\CoffeeMachine\VendingMachine\Payments\BillInterface;

interface VendingMachineInterface {

    /**
     * Get products inventory
     *
     * @return array
     */
    public function getInventory(): array;

    /**
     * Select product from menu
     *
     * @param int Product ID
     *
     * @return bool
     * @throws InvalidSelectionException
     */
    public function selectProduct(int $productId): bool;

    /**
     * Adjust sugar level
     *
     * @param int How much sugar
     * @return bool
     */
    public function setSugarLevel(int $sugarLevel): bool;

    /**
     * Adjust milk level
     *
     * @param int How much milk
     * @return bool
     */
    public function setMilkLevel(int $milkLevel): bool;

    /**
     * Confirm product selection
     *
     * @return bool
     */
    public function confirmSelection(): bool;

    /**
     * Scan card for payment
     *
     * @return bool Successful or not
     */
    public function scanCard(): bool;

    /**
     * Take 1 bill for cash payment
     *
     * @param BillInterface
     *
     * @return bool Successful or not
     */
    public function takeBill(BillInterface $bill): bool;

    /**
     * Get current order
     *
     * @return OrderInterface
     * @throws NoOrderInProgressException
     */
    public function getCurrentOrder(): OrderInterface;
}
