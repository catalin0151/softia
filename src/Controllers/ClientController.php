<?php

namespace Softia\Challenge\CoffeeMachine\Controllers;

use Softia\Challenge\CoffeeMachine\Client\CashBag;
use Softia\Challenge\CoffeeMachine\Client\CreditCard;
use Softia\Challenge\CoffeeMachine\Exceptions\InvalidSelectionException;
use Softia\Challenge\CoffeeMachine\Exceptions\NoOrderInProgressException;
use Softia\Challenge\CoffeeMachine\Exceptions\MachineAlreadyInUseException;
use Softia\Challenge\CoffeeMachine\Exceptions\PaymentException;
use Softia\Challenge\CoffeeMachine\Exceptions\SqlException;
use Softia\Challenge\CoffeeMachine\VendingMachine\Payments\ReceiptInterface;
use Softia\Challenge\CoffeeMachine\VendingMachine\Product;
use Softia\Challenge\CoffeeMachine\VendingMachine\VendingMachine;
use Softia\Challenge\CoffeeMachine\VendingMachine\Order;
use Softia\Challenge\CoffeeMachine\Client\Client;
use  Softia\Challenge\CoffeeMachine\Session;

class ClientController
{

    /**
     * Connects the current user to the vending machine and locks it
     *
     * @return void
     * @throws SqlException
     * @throws MachineAlreadyInUseException
     */
    public function useMachine(): void
    {
        $client = new Client();
        $vendingMachine = VendingMachine::get();
        Session::set('client', $client);
        $client->useMachine($vendingMachine);
    }

    /**
     * Disconnects the current user from the vending machine and unlocks it
     *
     */
    public function leaveMachine(): void
    {
        $client = Session::get('client');
        $client->leaveMachine();
        Session::set('client', null);
    }

    /**
     * Get the vending machine products list
     *
     * @return array
     * @throws SqlException
     */
    public function getProductList(): array
    {
        $client = Session::get('client');
        $products = $client->checkAvailableProducts();
        return $products;
    }

    /**
     * Select payment option
     *
     * @param array
     *
     * @throws  PaymentException
     */
    public function selectPayment($params): void
    {
        $method = $params['type'];
        $paymentMethods = ['card', 'cash'];
        if (!isset($method) || !in_array($method, $paymentMethods)) {
            throw new PaymentException();
        }
        $client = Session::get('client');
        switch ($method) {
            case 'card':
                $client->setCard(new CreditCard());
                break;
            case 'cash':
                $client->setCashBag(new CashBag());
                break;
        }
    }
    /**
     * Pay the current order
     *
     * @return ReceiptInterface
     * @throws  SqlException
     * @throws NoOrderInProgressException
     */
    public function pay()
    {
        $client = Session::get('client');
        return $client->pay();
    }


    /**
     * Sets in the vending machine the current order
     *
     * @param array
     *
     * @return bool
     * @throws  SqlException
     * @throws InvalidSelectionException
     */
    public function setOrder($params): bool
    {
        $client = Session::get('client');
        $productId = $params['productId'];
        $quantity = $params['quantity'];
        if (!isset($quantity) || !isset($productId) || !is_numeric($quantity) || !is_numeric($productId)) {
            throw new InvalidSelectionException();
        }
        $machine = $client->getVendingMachine();
        if ($machine->selectProduct($productId)) {
            $product = Product::find($productId);
            if ($quantity < 1 || $quantity > $product->quantity) {
                throw new InvalidSelectionException();
            }
            $order = $client->placeOrder($product, $quantity);
            $machine->setCurrentOrder($order);
        } else {
            throw new InvalidSelectionException();
        }

        return true;
    }


}