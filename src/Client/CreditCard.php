<?php
namespace Softia\Challenge\CoffeeMachine\Client;

class CreditCard implements CreditCardInterface {

    /**
     * Get card details
     *
     * @return string
     */
    public function details(): string {
        return 'mastercard';
    }
}