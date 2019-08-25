<?php

namespace Softia\Challenge\CoffeeMachine\Client;

use Softia\Challenge\CoffeeMachine\Database\Connection;
use Softia\Challenge\CoffeeMachine\Exceptions\MachineAlreadyInUseException;
use Softia\Challenge\CoffeeMachine\Exceptions\SqlException;
use Softia\Challenge\CoffeeMachine\Session;
use Softia\Challenge\CoffeeMachine\VendingMachine\Order;
use Softia\Challenge\CoffeeMachine\VendingMachine\OrderInterface;
use Softia\Challenge\CoffeeMachine\VendingMachine\Payments\Receipt;
use Softia\Challenge\CoffeeMachine\VendingMachine\Payments\ReceiptInterface;
use Softia\Challenge\CoffeeMachine\VendingMachine\ProductInterface;
use Softia\Challenge\CoffeeMachine\VendingMachine\VendingMachine;
use Softia\Challenge\CoffeeMachine\Client\ClientInterface;
use Softia\Challenge\CoffeeMachine\VendingMachine\VendingMachineInterface;

class Client implements ClientInterface
{
    private $vendingMachine = null;
    private $card = null;
    private $cash = null;
    private $payMethod = null;

    public function useMachine(VendingMachineInterface $vendingMachine): void {
        $this->vendingMachine = $vendingMachine;
        if($this->vendingMachine->isLocked()) {
            throw new MachineAlreadyInUseException();
        }
        $this->vendingMachine->lock();
    }


    /**
     * Client leaves the machine they are sitting at
     *
     * @return void
     */
    public function leaveMachine(): void {
        $this->vendingMachine->unlock();
        $this->vendingMachine = null;
    }

    public function getVendingMachine() {
        return $this->vendingMachine;
    }

    public function willPayWithCard() {
        return $this->payMethod === 'card';
    }

    public function willPayWithCash() {
        return $this->payMethod === 'cash';
    }

    /**
     * User pays with card
     *
     * @param CreditCardInterface
     */
    public function setCard(CreditCardInterface $card): void {
        $this->card = $card;
        $this->payMethod = 'card';
    }

    /**
     * Client pays with cash
     *
     * @param CashBagInterface
     *
     * @throws EmptyCashBagException
     */
    public function setCashBag(CashBagInterface $cash): void {
        $this->cash = $cash;
        $this->payMethod = 'cash';
    }

    /**
     * Client checks the machine menu
     *
     * @return array The list of ProductInterface the machine has
     */
    public function checkAvailableProducts(): array {
        return $this->getVendingMachine()->getInventory();
    }

    /**
     * Pay order
     *
     * @return ReceiptInterface
     * @throws PaymentException
     */
    public function pay(): ReceiptInterface {
        if ($this->willPayWithCash()) {
            return $this->payWithCash();
        }
        if ($this->willPayWithCard()) {
            return $this->payWithCard();
        }
    }

    public function payWithCash() {
        $machine = $this->getVendingMachine();
        return $this->getReceipt();
    }

    public function payWithCard() {
        $machine = $this->getVendingMachine();
        if ($machine->scanCard()) {
            return $this->getReceipt();
        }
        return null;
    }

    public function getReceipt() {
        $machine = $this->getVendingMachine();
        $order = $machine->getCurrentOrder();
        $conn = Connection::getConnection();
        $conn->beginTransaction();
        try {
            $product = $order->getProduct();
            $newQuantity = $product->quantity - $order->quantity;
            $product->quantity = max($newQuantity, 0);
            $product->update();
            $order->insert();
            $conn->commit();
            $receipt = new Receipt([
                'total' => $order->total,
                'products' => [$product]
            ]);
            return $receipt;
        } catch (SqlException $e) {
            $conn->rollBack();
            throw new SqlException();
        }
    }


    /**
     * Place order
     *
     * @param ProductInterface $ProductInterface
     * @param int $quantity
     *
     * @return OrderInterface
     */
    public function placeOrder(ProductInterface $product, int $quantity): OrderInterface {
        $machine = $this->getVendingMachine();
        $order = new Order([
            'product_id' => $product->id,
            'machine_id' => $machine->id,
            'quantity' => $quantity,
            'total' => $quantity * $product->price,
        ]);

        return $order;
    }

    /**
     * Cancel order
     *
     * @param OrderInterface
     *
     * @return void
     * @throws CannotCancelOrderException
     */
    public function cancelOrder(OrderInterface $order): void {
    }
}