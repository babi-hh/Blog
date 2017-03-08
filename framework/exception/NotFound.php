<?php

/**
 * @author Seldoon <Sedloon@sina.cn>
 * Created: Mar 8, 2017 2:08:58 PM
 */   
echo ERROR_INCO . '<br />';
echo "<h1> 404 文件不存在! </h1>";
echo $exception->getMessage() . ' In ' . $exception->getFile() . ' (' . $exception->getLine() . ')<br />';
?>

