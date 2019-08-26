<?php

namespace Softia\Challenge\CoffeeMachine;

use Softia\Challenge\CoffeeMachine\Database\Connection;
use Softia\Challenge\CoffeeMachine\Exceptions\InvalidSelectionException;
use Softia\Challenge\CoffeeMachine\Exceptions\MachineAlreadyInUseException;
use Softia\Challenge\CoffeeMachine\Exceptions\NoOrderInProgressException;
use Softia\Challenge\CoffeeMachine\Exceptions\PaymentException;
use Softia\Challenge\CoffeeMachine\Exceptions\SqlException;
use Softia\Challenge\CoffeeMachine\Providers\Route;
use Softia\Challenge\CoffeeMachine\VendingMachine\VendingMachine;

class App
{
    private static $instance = null;
    private $products = null;
    private $oder = null;

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new App();
        }
        return self::$instance;
    }

    /**
     * Handle the coffee machine app logic
     *
     */
    public function run():void
    {

        try {
            $this->useMachine();
            $this->showList();
            $this->placeOrder();
            $this->showPaymentOptions();
            $this->pay();
            $this->leaveMachine();
        } catch (SqlException $e) {
            try {
                $machine = VendingMachine::get();
                $machine->unlock();
            } catch (SqlException $e) {
            }
            echo $e->errorMessage();
        } catch (MachineAlreadyInUseException $e) {
            echo $e->errorMessage();
        } finally {
            (Connection::getInstance())->close();
        }
    }

    /**
     * Disconnects the current user from the vending machine and unlocks it
     *
     */
    private function leaveMachine(): void
    {
        Route::goTo('leave-machine');
        echo "Please come again\n";
    }

    /**
     * Pay the current order and show the receipt
     *
     * @throws  SqlException
     */
    private function pay(): void
    {
        try {
            $client = Session::get('client');
            $receipt = null;
            if ($client->willPayWithCard()) {
                $this->payWithCard();
            }
            if ($client->willPayWithCash()) {
                $this->payWithCash();

            }
        } catch (NoOrderInProgressException $e) {
            echo $e->errorMessage();
            $this->leaveMachine();
            exit();
        } catch (PaymentException $e) {
            echo $e->errorMessage();
            $this->pay();
        }


    }

    /**
     * Pay the current order with cash and show the receipt
     *
     * @throws  SqlException
     * @throws NoOrderInProgressException
     */
    private function payWithCash(): void
    {
        $receipt = Route::goTo('pay');
        if ($receipt) {
            echo $receipt->toString();
        }
    }
    /**
     * Pay the current order with credit card and show the receipt
     *
     * @throws  SqlException
     * @throws NoOrderInProgressException
     */
    private function payWithCard(): void
    {
        $receipt = Route::goTo('pay');
        if ($receipt) {
            echo $receipt->toString();
        }
    }

    private function placeOrder(): void
    {
        try {
            echo "Please select a product id\n";
            $productId = $this->getInput();

            echo "Please select quantity \n";
            $quantity = $this->getInput();

            Route::goTo('set-order', [
                'productId' => $productId,
                'quantity' => $quantity,
            ]);
        } catch (InvalidSelectionException $e) {
            echo $e->errorMessage();
            $this->placeOrder();
        }
    }

    private function getInput(): string
    {
        return trim(fgets(STDIN));
    }

    /**
     * Connects the current user to the vending machine and locks it
     *
     * @return void
     * @throws SqlException
     * @throws MachineAlreadyInUseException
     */
    private function useMachine(): void
    {
        Route::goTo('use-machine');
    }

    /**
     * Get the vending machine products list
     *
     * @throws SqlException
     */
    private function showList(): void
    {
        $msg = "Welcome customer, to view the product list type: list\n";
        echo $msg;
        while ($command = $this->getInput() !== 'list') {
            echo $msg;
        };
        $products = Route::goTo('list');
        $this->products = $products;
        foreach ($this->products as $product) {
            echo $product->toString() . "\n";
        }
    }

    /**
     * Select payment option
     *
     * @param array
     *
     * @throws  PaymentException
     */
    private function showPaymentOptions(): void
    {
        try {
            echo "Select a method of payment by typing: cash or card \n";
            $method = $this->getInput();
            Route::goTo('select-payment', [
                'type' => $method
            ]);
        } catch (PaymentException $e) {
            echo $e->errorMessage();
            $this->showPaymentOptions();
        }
    }
}