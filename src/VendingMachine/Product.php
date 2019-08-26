<?php


namespace Softia\Challenge\CoffeeMachine\VendingMachine;

use Softia\Challenge\CoffeeMachine\Exceptions\SqlException;
use Softia\Challenge\CoffeeMachine\VendingMachine\Payments\BillInterface;
use Softia\Challenge\CoffeeMachine\VendingMachine\ProductInterface;
use Softia\Challenge\CoffeeMachine\VendingMachine\VendingMachineInterface;
use Softia\Challenge\CoffeeMachine\Common\Model;
use Softia\Challenge\CoffeeMachine\Database\Connection;

class Product extends Model implements ProductInterface
{

    private static $tableName = 'products';
    public $columns = ['id', 'name', 'price', 'content', 'quantity', 'machine_id'];

    /**
     * Get products
     *
     * @params array Where conditions
     *
     * @return array
     * @throws SqlException
     */
    public static function all($where = []): array
    {
        $sql = 'SELECT * FROM ' . self::$tableName;
        if (!empty($where)) {
            $sql .= " WHERE ";
            $first = true;
            foreach ($where as $key => $value) {

                $sql .= "$key = :$key";
                if (!$first) {
                    $sql .= " AND ";
                }
                $first = false;
            }
        }
        $conn = Connection::getConnection();
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute($where);
        if (!$result) {
            return null;
        }
        $products = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $products[] = new Product($row);
        }

        return $products;
    }

    /**
     * Converts to string
     *
     * @return string
     */
    public function toString(): string
    {
        return sprintf("id: %s, name: %s, price: %s, content: %s, availability: %s", $this->id, $this->name,
            $this->price, $this->content, $this->quantity);
    }
    /**
     * Find product by id
     *
     * @params int Product id
     *
     * @return ProductInterface
     * @throws SqlException
     */
    public static function find($id): ProductInterface
    {
        $sql = "SELECT * FROM " . self::$tableName . " WHERE id = :id";
        $conn = Connection::getConnection();
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([
            'id' => $id
        ]);

        if (!$result) {
            throw new SqlException();
        }
        if ($stmt->rowCount() === 0) {
            throw new SqlException();
        }

        $product = $stmt->fetch(\PDO::FETCH_ASSOC);
        return new Product($product);
    }

    /**
     * Get product ID
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Check if product can be ordered
     *
     * @return bool
     */
    public function isAvailable(): bool
    {
        return $this->quantity > 0;
    }


    /**
     * Update the product
     *
     * @return bool
     * @throws SqlException
     */
    public function update(): bool
    {
        $sql = "UPDATE " . self::$tableName . ' SET name=:name, price=:price, content=:content, 
        quantity=:quantity, machine_id=:machine_id WHERE id=:id';
        $stmt = $this->conn->prepare($sql);
        $result =  $stmt->execute([
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'content' => $this->content,
            'quantity' => $this->quantity,
            'machine_id' => $this->machine_id
        ]);
        if (!$result) {
            throw new SqlException();
        }

        return true;
    }
}