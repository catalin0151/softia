<?php

$__env = require_once __ROOT__ . './env.php';

function env($param) {
    global $__env;
    return $__env[$param];
}

;

require_once __ROOT__ . '/routes/client.php';