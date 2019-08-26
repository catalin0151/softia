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

//TODO Implement the rest of the unit tests
class BaseTest extends TestCase
{

    public function testCommon()
    {
        $this->assertTrue(true);
    }
}
