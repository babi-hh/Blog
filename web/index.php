<?php
function D($varVal, $isExit = FALSE) {
    var_dump($varVal);
    $isExit && exit();
}
define('APP_DEBUG', TRUE);
include getcwd() . '/../framework/framework.class.php';
framework\Framework::run();
