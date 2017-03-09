<?php
/**
 * 错误提示的模版文件 error.tpl.php
 * 可调用变量 $e
 * @author Seldoon <Sedloon@sina.cn>
 * Created: Mar 9, 2017 11:06:21 AM
 */
?>
<html>
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
        <title>系统发生错误</title>
        <style type="text/css">
            *{ padding: 0; margin: 0; }
            html{ overflow-y: scroll; }
            body{ background: #fff; font-family: '微软雅黑'; color: #333; font-size: 16px; }
            img{ border: 0; }
            .error{ padding: 24px 48px; }
            .face{ font-size: 80px; font-weight: normal; line-height: 120px; margin-bottom: 12px; }
            h1{ font-size: 32px; line-height: 48px; }
            .error .content{ padding-top: 10px}
            .error .info{ margin-bottom: 12px; }
            .error .info .title{ margin-bottom: 3px; }
            .error .info .title h3{ color: #000; font-weight: 700; font-size: 16px; }
            .error .info .text{ line-height: 24px; }
            .copyright{ padding: 12px 48px; color: #999; }
            .copyright a{ color: #000; text-decoration: none; }
        </style>
    </head>
    <body>
        <div class="error">
            <p class="face">￣へ￣</p>
            <h1> <?= strip_tags($e['message']) ?> </h1>
            <div class="content">

                <?php if (isset($e['file'])) { ?>
                <div class="info">
                        <div class="titile"> 
                            <h3>错误位置</h3>
                        </div>
                        <div class="text">
                            <p>FILE: <?= $e['file'] ?> &#12288; LINE <?= $e['line'] ?></p>
                        </div>
                    </div>
                <?php } ?>
                <?php if (isset($e['trace'])) { ?>
                <div class="info">
                        <div class="title">
                            <h3>TRACE</h3>
                        </div>
                        <div class="text">
                            <p><?= nl2br($e['trace']) ?></p>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="copyright"></div>
    </body>
</html>

