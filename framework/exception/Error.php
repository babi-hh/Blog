<?php

/**
 * 错误模版
 * @author Seldoon <Sedloon@sina.cn>
 * Created: Mar 8, 2017 2:26:32 PM
 */
/**
 * 可用变量 $exception 异常对象
 */
echo ERROR_INCO . '<br />';
echo '内部错误<br />';
echo $exception->getMessage() . "<br />";
echo $exception->getFile(), '(';
echo $exception->getLine() . ")<br />";

