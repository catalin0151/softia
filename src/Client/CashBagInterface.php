<?php

namespace Softia\Challenge\CoffeeMachine\Client;

interface CashBagInterface {

    /**
     * Get wallet cash contents
     *
     * @return arrat
     */
    public function getContents(): array;
}
