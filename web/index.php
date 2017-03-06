<?php
function D($varVal, $isExit = FALSE) {
    var_dump($varVal);
    $isExit && exit();
}

include getcwd() . '/../framework/framework.class.php';
(new framework\Framework())->run();
