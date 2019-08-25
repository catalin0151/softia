<?php

use Softia\Challenge\CoffeeMachine\Providers\Route;

Route::add('use-machine', 'ClientController', 'useMachine');
Route::add('list', 'ClientController', 'getProductList');
Route::add('set-order', 'ClientController', 'setOrder');
Route::add('select-payment', 'ClientController', 'selectPayment');
Route::add('pay', 'ClientController', 'pay');
Route::add('leave-machine', 'ClientController', 'leaveMachine');