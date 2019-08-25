<?php

namespace Softia\Challenge\CoffeeMachine;

use Softia\Challenge\CoffeeMachine\Database\Connection;
use Softia\Challenge\CoffeeMachine\Exceptions\InvalidSelectionException;
use Softia\Challenge\CoffeeMachine\Exceptions\MachineAlreadyInUseException;
use Softia\Challenge\CoffeeMachine\Exceptions\NoOrderInProgressException;
use Softia\Challenge\CoffeeMachine\Exceptions\PaymentException;
use Softia\Challenge\CoffeeMachine\Exceptions\SqlException;
use Softia\Challenge\CoffeeMachine\Providers\Route;

class App
{
    private static $instance = null;
    private $products = null;
    private $oder = null;

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new App();
        }
        return self::$instance;
    }

    public function run() {
        try {
            $this->useMachine();
            $this->showList();
            $this->placeOrder();
            //$this->confirmOrder();
            $this->showPaymentOptions();
            $this->pay();
            $this->leaveMachine();
        } catch (SqlException | MachineAlreadyInUseException $e) {
            echo $e->errorMessage();
        } finally {
            (Connection::getInstance())->close();
        }
    }

    private function leaveMachine() {
        Route::goTo('leave-machine');
        echo "Please come again\n";
    }

    private function pay() {
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

    private function payWithCash() {
        $receipt = Route::goTo('pay');
        if ($receipt) {
            echo $receipt->toString();
        }
    }

    private function payWithCard() {
        $receipt = Route::goTo('pay');
        if ($receipt) {
            echo $receipt->toString();
        }
    }

    private function placeOrder() {
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

    private function getInput() {
        return trim(fgets(STDIN));
    }

    private function useMachine() {
        Route::goTo('use-machine');
    }

    private function showList() {
        $msg = "Welcome customer, to view the product list type: list\n";
        echo $msg;
        while ($command = $this->getInput() !== 'list') {
            echo $msg;
        };
        $products = Route::goTo('list');
        $this->products = $products;
        $this->showProducts();
        return $products;
    }

    private function showProducts() {
        foreach ($this->products as $product) {
            echo $product->toString() . "\n";
        }
    }

    private function confirmOrder() {
        echo "Write yes to confirm order or no order something else \n";
        $confirmation = $this->getInput();

    }

    private function showPaymentOptions() {
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