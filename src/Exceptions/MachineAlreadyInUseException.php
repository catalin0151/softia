<?php


namespace Softia\Challenge\CoffeeMachine\Exceptions;

/**
 * Encapsulate payment exceptions
 */
class MachineAlreadyInUseException extends \Exception {
    public function errorMessage() {
        return "Machine already in use please try again later \n";
    }
}
