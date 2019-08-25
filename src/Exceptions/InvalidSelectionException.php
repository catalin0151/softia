<?php


namespace Softia\Challenge\CoffeeMachine\Exceptions;

/**
 * Encapsulate exceptions when client selection is invalid
 */
class InvalidSelectionException extends \Exception {
    public function errorMessage() {
        return "Invalid selection, please try again \n";
    }
}
