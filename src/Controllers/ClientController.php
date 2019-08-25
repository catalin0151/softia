<?php

namespace Softia\Challenge\CoffeeMachine\Controllers;

use Softia\Challenge\CoffeeMachine\Client\CashBag;
use Softia\Challenge\CoffeeMachine\Client\CreditCard;
use Softia\Challenge\CoffeeMachine\Exceptions\InvalidSelectionException;
use Softia\Challenge\CoffeeMachine\Exceptions\PaymentException;
use Softia\Challenge\CoffeeMachine\VendingMachine\Product;
use Softia\Challenge\CoffeeMachine\VendingMachine\VendingMachine;
use Softia\Challenge\CoffeeMachine\VendingMachine\Order;
use Softia\Challenge\CoffeeMachine\Client\Client;
use  Softia\Challenge\CoffeeMachine\Session;

class ClientController
{

    public function useMachine() {
        $client = new Client();
        $vendingMachine = VendingMachine::get();
        Session::set('client', $client);
        $client->useMachine($vendingMachine);
    }

    public function leaveMachine() {
        $client = Session::get('client');
        $client->leaveMachine();
    }

    public function getProductList() {
        $client = Session::get('client');
        $products = $client->checkAvailableProducts();
        return $products;
    }

    public function selectPayment($params) {
        $method = $params['type'];
        $paymentMethods = ['card', 'cash'];
        if(!isset($method) || !in_array($method, $paymentMethods)) {
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

    public function pay() {
        $client = Session::get('client');
        return $client->pay();
    }

    public function setOrder($params) {
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
    }


}