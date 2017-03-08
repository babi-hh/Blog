<?php

namespace framework\exception;

use framework\base\ErrorException;

/**
 * 404 类
 * @author Seldoon <Sedloon@sina.cn>
 * Created: Mar 8, 2017 10:54:41 AM
 */
class NotFoundException extends ErrorException {

    public function __construct($message = "", $code = 0, \Exception $previous = null) {
        self::$statusCode = 404;
        parent::__construct($message, $code, $previous);
    }

}
