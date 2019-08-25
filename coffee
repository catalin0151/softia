#!/usr/bin/env php
<?php
define('__ROOT__', __DIR__);
require_once(__ROOT__ . '/vendor/autoload.php');
require_once(__ROOT__ . '/bootstrap/app.php');

/*
 *  I wanted to make the client / server as two separated services and make them communicate with a messaging service like
 *  rabbitmq. Because i feel the problem had two parties that needed to exchange messages. With this approach the clients
 *  could have waited in queue like in real life and be notified of the number of users in front of them and  when there turn
 *  came up could have used the machine without the need try again and again until the machine was free. But i choose not
 *  to do so because this arhitecture didn't fit with the interfaces provided.
 *  I didn't use laravel to do this project because i didn't know if it i was allowed to do so, and the structure
 *  didn't match.
 */
use Softia\Challenge\CoffeeMachine\App;
(App::getInstance())->run();
