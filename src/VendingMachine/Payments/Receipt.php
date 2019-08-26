<?php

namespace Softia\Challenge\CoffeeMachine\VendingMachine\Payments;

use Softia\Challenge\CoffeeMachine\Common\Model;

class Receipt extends Model implements ReceiptInterface
{

    public $columns= ['products', 'total'];

    /**
     * Get receipt total
     *
     * @return int
     */
    public function getTotal(): int {
        return $this->total;
    }

    /**
     * Get products on receipt
     *
     * @return array
     */
    public function getProducts(): array {
       return $this->products;
    }

    /**
     * Converts to string
     *
     * @return string
     */
    public function toString(): string {
        $txt = "Receipt: \n";
        foreach($this->products as $product) {
            $txt .=  sprintf("Product: %s \n", $product->name );
        }
        $txt .= sprintf("Total: %s \n", $this->getTotal());
        return $txt;
    }
}