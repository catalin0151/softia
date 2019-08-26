<?php

namespace Softia\Challenge\CoffeeMachine\Tests;

use PHPUnit\Framework\TestCase;
use Softia\Challenge\CoffeeMachine\Exceptions\MachineAlreadyInUseException;
use Softia\Challenge\CoffeeMachine\Exceptions\SqlException;
use Softia\Challenge\CoffeeMachine\Providers\Route;
use Softia\Challenge\CoffeeMachine\Session;
use Softia\Challenge\CoffeeMachine\VendingMachine\VendingMachine;

if (!defined('__ROOT__')) {
    define('__ROOT__', __DIR__ . '/..');
}
require_once(__ROOT__ . '/vendor/autoload.php');
require_once(__ROOT__ . '/bootstrap/app.php');


class VendingMachineTest extends TestCase {

    public function testGetMachine() {

        $machine = VendingMachine::get();
        $this->assertInstanceOf('\Softia\Challenge\CoffeeMachine\VendingMachine\VendingMachine', $machine);
    }

    public function testUnlockMachine() {

        $machine = VendingMachine::get();
        $unlocked = $machine->unlock();
        $this->assertTrue($unlocked);
    }

    public function testLockMachine() {

        $machine = VendingMachine::get();
        $locked = $machine->lock();
        $this->assertTrue($locked);
    }
}
