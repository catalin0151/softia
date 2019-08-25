<?php


namespace Softia\Challenge\CoffeeMachine\Exceptions;

/**
 * Exception for scenarios when an order is requested when there is none
 */
class NoOrderInProgressException extends \Exception {
    public function errorMessage() {
        return "No order in progress \n";
    }
}
