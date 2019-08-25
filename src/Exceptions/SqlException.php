<?php


namespace Softia\Challenge\CoffeeMachine\Exceptions;

/**
 * Encapsulate sql exceptions
 */
class SqlException extends \Exception {
    public function errorMessage() {
        return "Something went wrong, please try again later \n";
    }
}
