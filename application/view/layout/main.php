<?php
/**
 * 主题布局文件
 * @author Seldoon <Sedloon@sina.cn>
 * 
 * 可使用的变量
 * $flash
 * $content
 */
?>
<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <title><?= $this->title ? $this->title . " | Seldoon" : 'Seldoon' ?></title>
        <link rel="shortcut icon" type="image/x-icon" href="<?= ASSETS_PATH ?>style/images/favicon.png" />
        <link rel="stylesheet" type="text/css" href="<?= ASSETS_PATH ?>css/style.css" media="all" />
        <link rel="stylesheet" type="text/css" href="<?= ASSETS_PATH ?>style/css/prettyPhoto.css"  />
        <link rel="stylesheet" type="text/css" href="<?= ASSETS_PATH ?>style/type/classic.css" media="all" />
        <link rel="stylesheet" type="text/css" href="<?= ASSETS_PATH ?>style/type/goudy.css" media="all" />
    </head>
    <body>
        <div id="body-wrapper"> 
            <!-- Begin Header -->
            <div id="header">
                <div class="logo">
                    <a href="index.html"><img src="<?= ASSETS_PATH ?>style/images/logo.png" alt="" /></a>
                </div>


                <!-- Begin Menu -->
                <div class="menu">
                    <ul class="sf-menu">
                        <li><a href="<?= $this->url('/home/index') ?>">首页</a>
                            <!-- 
                            <ul>
                                <li><a href="index.html">Slider I</a></li>
                                <li><a href="index2.html">Slider II</a></li>
                                <li><a href="index3.html">Slider III</a></li>
                            </ul> 
                            -->
                        </li>
                        <li><a href="<?= $this->url('/home/index') ?>">博客</a>
                            <ul>
                                <li><a href="<?= $this->url('/home/index') ?>">Post</a></li>
                            </ul>
                        </li>
                        <li><a href="gallery.html">画廊</a></li>
                        <li><a href="typography.html">Typography</a></li>
                        <li><a href="columns.html">Columns</a></li>
                        <li><a href="contact.html">Contact</a></li>
                    </ul>
                </div>
                <div class="clear"></div>
                <!-- End Menu --> 

            </div>
            <!-- End Header --> 



            <!-- Begin Wrapper -->
            <div id="wrapper">
                <!--Flash start-->
                <?php
                if ($flash) {
                    foreach ($flash as $key => $item) {
                        
                    }
                    ?>
                <?php } ?>
                <!--Flash end-->
                <div class="intro">Phasellus vitae lectus sit amet ipsum fringilla viverra at et leo. Cras iaculis, sem vel venenatis sodales, elit dui elementum lorem, ut semper ligula ipsum at sapien.</div>

                <!-- Begin Container -->
                <?= $content ?>
                <!-- End Container -->

                <div class="sidebar">

                    <div class="sidebox">
                        <h3 class="line">About</h3>
                        <p>人生的得与失,其实是件很普通的事</p>
                        <br/>
                        <p>道理懂得不少,还是过不好这一生</p>
                    </div>

                    <div class="sidebox">
                        <h3 class="line">热门博文</h3>
                        <ul class="popular-posts">
                            <li>
                                <a href="#"><img src="<?= ASSETS_PATH ?>style/images/art/ad1.jpg" alt="" /></a>
                                <h5><a href="#">Dolor Commodo Consectetur</a></h5>
                                <span>26 August 2011 | <a href="#">21 Comments</a></span>
                            </li>

                            <li>
                                <a href="#"><img src="<?= ASSETS_PATH ?>style/images/art/ad2.jpg" alt="" /></a>
                                <h5><a href="#">Dolor Commodo Consectetur</a></h5>
                                <span>26 August 2011 | <a href="#">21 Comments</a></span>
                            </li>

                            <li>
                                <a href="#"><img src="<?= ASSETS_PATH ?>style/images/art/ad3.jpg" alt="" /></a>
                                <h5><a href="#">Dolor Commodo Consectetur</a></h5>
                                <span>26 August 2011 | <a href="#">21 Comments</a></span>
                            </li>
                        </ul>
                    </div>


                    <div class="sidebox">
                        <h3 class="line">分类</h3>
                        <ul class="cat-list">
                            <li><a href="#">Web Design (15)</a></li>
                            <li><a href="#">Photography (17)</a></li>
                            <li><a href="#">Grapic Design (34)</a></li>
                            <li><a href="#">Manipulation (24)</a></li>
                            <li><a href="#">Web Design (15)</a></li>
                            <li><a href="#">Photography (17)</a></li>
                            <li><a href="#">Grapic Design (34)</a></li>
                            <li><a href="#">Manipulation (24)</a></li>
                        </ul>
                    </div>

                    <div class="sidebox">
                        <h3 class="line">Search</h3>
                        <form class="searchform" method="get">
                            <input type="text" id="s" name="search" placeholder="type and hit enter"/>
                        </form>
                    </div>

                </div>
                <div class="clear"></div>

            </div>
            <!-- End Wrapper -->

            <div class="push"></div>
        </div>
        <!-- End Body Wrapper -->

        <div id="footer">
            <div class="footer">
                <p>Copyright &copy; 2017 Seldoon. All Rights Reserved.Collect from </p>
            </div>
        </div>
        <script type="text/javascript" src="<?= ASSETS_PATH ?>style/js/jquery-1.6.2.min.js"></script>
        <script type="text/javascript" src="<?= ASSETS_PATH ?>style/js/superfish.js"></script>
        <!--<script type="text/javascript" src="<?//= ASSETS_PATH ?>style/js/jquery.prettyPhoto.js"></script>-->
        <script type="text/javascript" src="<?= ASSETS_PATH ?>style/js/scripts.js"></script>
    </body>
</html>