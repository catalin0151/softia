<?php

namespace Softia\Challenge\CoffeeMachine\VendingMachine;

use Softia\Challenge\CoffeeMachine\VendingMachine\OrderInterface;
use Softia\Challenge\CoffeeMachine\VendingMachine\ProductInterface;
use Softia\Challenge\CoffeeMachine\VendingMachine\Product;
use Softia\Challenge\CoffeeMachine\Common\Model;
use Softia\Challenge\CoffeeMachine\Exceptions\SqlException;

class Order extends Model implements OrderInterface
{

    private static $tableName = 'orders';
    public $columns = ['id', 'machine_id', 'product_id', 'quantity', 'total', 'timestamp'];

    /**
     * Get order product
     *
     * @return ProductInterface
     */
    public function getProduct(): ProductInterface {
        return Product::find($this->product_id);
    }

    /**
     * Insert the order
     *
     * @return bool
     * @throws SqlException
     */
    public function insert(): bool {
        $sql = 'INSERT INTO ' . self::$tableName . ' (id, machine_id, product_id, quantity, total, timestamp) 
                VALUES(NULL, :machine_id, :product_id, :quantity, :total, :timestamp)';

        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute([
            'machine_id' => $this->machine_id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'total' => $this->total,
            'timestamp' => (new \DateTime())->format('Y-m-d H:i:s')
        ]);
        if (!$result) {
            throw new SqlException();
        }
        return $result;
    }

    /**
     * Check if order is ready
     *
     * @return bool
     */
    public function isReady(): bool {
        // TODO: Implement isReady() method.
    }
}