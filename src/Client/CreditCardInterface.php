<?php

namespace Softia\Challenge\CoffeeMachine\Client;

interface CreditCardInterface {

    /**
     * Get card details
     *
     * @return string
     */
    public function details(): string;
}
