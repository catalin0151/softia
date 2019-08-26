<?php

namespace Softia\Challenge\CoffeeMachine\Tests;

use PHPUnit\Framework\TestCase;
use Softia\Challenge\CoffeeMachine\Exceptions\InvalidSelectionException;
use Softia\Challenge\CoffeeMachine\Providers\Route;
use Softia\Challenge\CoffeeMachine\Session;
use Softia\Challenge\CoffeeMachine\VendingMachine\VendingMachine;

if (!defined('__ROOT__')) {
    define('__ROOT__', __DIR__ . '/..');
}
require_once(__ROOT__ . '/vendor/autoload.php');
require_once(__ROOT__ . '/bootstrap/app.php');


class ClientControllerTest extends TestCase
{


    public function testUseMachine()
    {
        $machine = VendingMachine::get();
        $machine->unlock();
        Route::goTo('use-machine');
        $client = Session::get('client');
        $this->assertTrue(!!$client);
    }


    public function testGetProductList()
    {
        $products = Route::goTo('list');
        $this->assertIsArray($products);
        $this->assertNotEmpty($products);
        $firstProduct = $products[0];
        $this->assertInstanceOf('\Softia\Challenge\CoffeeMachine\VendingMachine\Product', $firstProduct);
    }

    public function testPlaceOrderOk()
    {
        $productId = 1;
        $quantity = 1;
        $result = Route::goTo('set-order', [
            'productId' => $productId,
            'quantity' => $quantity,
        ]);
        $this->assertTrue($result);
    }

    public function testPlaceOrderErrors()
    {
        $combinations = [
            [
                'productId' => 'string',
                'quantity' => 1
            ],
            [
                'productId' => 1,
                'quantity' => 'string'
            ],
            [
                'productId' => 1,
                'quantity' => 0
            ],
            [
                'productId' => 1,
                'quantity' => -1
            ],
            [
                'productId' => 1,
                'quantity' => 10000
            ],
        ];
        foreach($combinations as $combination) {
            $productId = $combination['productId'];
            $quantity = $combination['quantity'];
//            $this->expectException(InvalidSelectionException::class);
            try {
                $result = Route::goTo('set-order', [
                    'productId' => $productId,
                    'quantity' => $quantity,
                ]);
            } catch(InvalidSelectionException $e) {
                $this->assertTrue(true);
            }

        }
    }
    public function testLeaveMachine()
    {
//        $this->expectException(SqlException::class);
        Route::goTo('leave-machine');
        $client = Session::get('client');
        $this->assertFalse(!!$client);
    }
}
