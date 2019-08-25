<?php

namespace Softia\Challenge\CoffeeMachine\Client;

use Softia\Challenge\CoffeeMachine\VendingMachine\OrderInterface;
use Softia\Challenge\CoffeeMachine\VendingMachine\Payments\ReceiptInterface;
use Softia\Challenge\CoffeeMachine\VendingMachine\ProductInterface;
use Softia\Challenge\CoffeeMachine\VendingMachine\VendingMachineInterface;

interface ClientInterface {

    /**
     * User pays with card
     *
     * @param CreditCardInterface
     */
    public function setCard(CreditCardInterface $card): void;

    /**
     * Client pays with cash
     *
     * @param CashBagInterface
     *
     * @throws EmptyCashBagException 
     */
    public function setCashBag(CashBagInterface $cash): void;

    /**
     * Client shows up in from of the machine
     *
     * @param VendingMachineInterface $machine
     *
     * @return void
     */
    public function useMachine(VendingMachineInterface $machine): void;

    /**
     * Client leaves the machine they are sitting at
     *
     * @return void
     */
    public function leaveMachine(): void;

    /**
     * Client checks the machine menu
     *
     * @return array The list of ProductInterface the machine has
     */
    public function checkAvailableProducts(): array;

    /**
     * Place order
     *
     * @param ProductInterface $ProductInterface
     * @param int $quantity
     *
     * @return OrderInterface
     */
    public function placeOrder(ProductInterface $product, int $quantity): OrderInterface;

    /**
     * Cancel order
     *
     * @param OrderInterface
     *
     * @return void
     * @throws CannotCancelOrderException
     */
    public function cancelOrder(OrderInterface $order): void;

    /**
     * Pay order
     *
     * @return ReceiptInterface
     * @throws PaymentException
     */
    public function pay(): ReceiptInterface;
}
