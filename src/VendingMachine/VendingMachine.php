<?php

namespace Softia\Challenge\CoffeeMachine\VendingMachine;

use Softia\Challenge\CoffeeMachine\Exceptions\NoOrderInProgressException;
use Softia\Challenge\CoffeeMachine\Exceptions\SqlException;
use Softia\Challenge\CoffeeMachine\VendingMachine\Payments\BillInterface;
use Softia\Challenge\CoffeeMachine\VendingMachine\Product;
use Softia\Challenge\CoffeeMachine\VendingMachine\VendingMachineInterface;
use Softia\Challenge\CoffeeMachine\Common\Model;
use Softia\Challenge\CoffeeMachine\Database\Connection;
use  Softia\Challenge\CoffeeMachine\Session;
use Softia\Challenge\CoffeeMachine\Exceptions\InvalidSelectionException;

class VendingMachine extends Model implements VendingMachineInterface
{
    private static $tableName = 'coffee_machine';
    public $currentOrder = null;
    public $columns = ['id', 'locked_until'];

    /**
     * Get vending machine
     *
     * @return VendingMachineInterface
     * @throws SqlException
     */
    public static function get(): VendingMachineInterface
    {
        $conn = Connection::getConnection();
        $sql = "SELECT * FROM " . self::$tableName . " limit 1";
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute();
        if (!$result) {
            throw new SqlException();
        }
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        return new VendingMachine($data);
    }

    /**
     * Get products inventory
     *
     * @return array
     * @throws SqlException
     */
    public function getInventory(): array
    {
        return Product::all(['machine_id' => $this->id]);
    }

    /**
     * Select product from menu
     *
     * @param int Product ID
     *
     * @return bool
     * @throws InvalidSelectionException
     * @throws SqlException
     */
    public function selectProduct(int $productId): bool
    {
        $product = Product::find($productId);
        if (!$product) {
            throw new InvalidSelectionException();
        }

        return $product->isAvailable();
    }

    /**
     * Adjust sugar level
     *
     * @param int How much sugar
     * @return bool
     */
    public function setSugarLevel(int $sugarLevel): bool
    {
        // TODO: Implement setSugarLevel() method.
    }

    /**
     * Adjust milk level
     *
     * @param int How much milk
     * @return bool
     */
    public function setMilkLevel(int $milkLevel): bool
    {
        // TODO: Implement setMilkLevel() method.
    }

    /**
     * Confirm product selection
     *
     * @return bool
     */
    public function confirmSelection(): bool
    {
        // TODO: Implement confirmSelection() method.
    }

    /**
     * Scan card for payment
     *
     * @return bool Successful or not
     */
    public function scanCard(): bool
    {
        return true;
    }

    /**
     * Take 1 bill for cash payment
     *
     * @param BillInterface
     *
     * @return bool Successful or not
     */
    public function takeBill(BillInterface $bill): bool
    {
        return true;
    }

    /**
     * Sets currentOrder
     *
     * @param OrderInterface
     *
     * @return void
     */
    public function setCurrentOrder(OrderInterface $order): void
    {
        $this->currentOrder = $order;
    }

    /**
     * Get current order
     *
     * @return OrderInterface
     * @throws NoOrderInProgressException
     */
    public function getCurrentOrder(): OrderInterface
    {
        if (!$this->currentOrder) {
            throw new NoOrderInProgressException();
        }
        return $this->currentOrder;
    }

    /**
     * The lock mechanism is not complete. I'm using a timestamp to set the lock lifespan, in case the process is killed,
     * so it won't lock the vending machine forever. But the current user process is not killed because
     * i didn't implemented a timeout mechanic. I was thinking of using threads to do a countdown for the same value as
     * the lock, and if the user reaches this timer to be kicked out of the app. And if user is active to update the lock
     * timestamp, so the user doesn't get kicked of the app in the middle of using it. Right now when the lock expires
     * the current user who should have been kicked out is still using the app. Because of this there could be multiple users using the
     * machine on the same time. I tried to creat a thread, or use some signals but it took too much time, so i gave up
     * on implementing it, to have time to work on some other features.
     * @return bool
     * @throws SqlException
     */
    public function lock(): bool
    {
        $sql = sprintf("UPDATE %s SET locked_until=:locked_until WHERE id=:id", self::$tableName);
        $stmt = $this->conn->prepare($sql);

        //lock the machine for 3 minutes only in case the user doesn't close the connection
        $lockPeriod = (new \DateTime())->add(new \DateInterval("PT3M"))->format('Y-m-d H:i:s');
        $result = $stmt->execute([
            'id' => $this->id,
            'locked_until' => $lockPeriod
        ]);
        if (!$result) {
            throw new SqlException();
        }

        $this->locked_until = $lockPeriod;
        return true;
    }

    /**
     * Unlock vending machine
     *
     * @return bool
     * @throws SqlException
     */
    public function unlock(): bool
    {
        $sql = sprintf("UPDATE %s SET locked_until=:locked_until", self::$tableName);
        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute([
            'locked_until' => null
        ]);
        if (!$result) {
            throw new SqlException();
        }
        $this->locked_until = null;
        return true;
    }

    /**
     * Checks vending machine lock status
     *
     * @return bool
     */
    public function isLocked(): bool
    {
        if (!$this->locked_until) {
            return false;
        }
        $locked_until = new \DateTime($this->locked_until);
        return $locked_until > (new \DateTime());
    }
}