<?php


namespace Softia\Challenge\CoffeeMachine\Exceptions;

/**
 * Encapsulate payment exceptions
 */
class PaymentException extends \Exception {
    public function errorMessage() {
        return "Payment error \n";
    }
}
