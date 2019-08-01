<?php
$assetUrl = $view['url']->to('index');
$route = \Application::get('Request')->attributes['_route'];
?>
<!doctype html>
<html class="no-js" lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title><?php $view['slots']->output('title', 'Hello Application') ?></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- favicon
                    ============================================ -->
        <link rel="shortcut icon" type="image/x-icon" href="<?= $assetUrl ?>favicon.ico">
        <!-- Google Fonts
                    ============================================ -->
        <link href="https://fonts.googleapis.com<?= $assetUrl ?>css?family=Roboto:100,300,400,700,900" rel="stylesheet">
        <!-- Bootstrap CSS
                    ============================================ -->
        <link rel="stylesheet" href="<?= $assetUrl ?>css/bootstrap.min.css">
        <!-- Bootstrap CSS
                    ============================================ -->
        <link rel="stylesheet" href="<?= $assetUrl ?>css/font-awesome.min.css">
        <!-- owl.carousel CSS
                    ============================================ -->
        <link rel="stylesheet" href="<?= $assetUrl ?>css/owl.carousel.css">
        <link rel="stylesheet" href="<?= $assetUrl ?>css/owl.theme.css">
        <link rel="stylesheet" href="<?= $assetUrl ?>css/owl.transitions.css">
        <!-- animate CSS
                    ============================================ -->
        <link rel="stylesheet" href="<?= $assetUrl ?>css/animate.css">
        <!-- normalize CSS
                    ============================================ -->
        <link rel="stylesheet" href="<?= $assetUrl ?>css/normalize.css">
        <!-- meanmenu icon CSS
                    ============================================ -->
        <link rel="stylesheet" href="<?= $assetUrl ?>css/meanmenu.min.css">
        <!-- main CSS
                    ============================================ -->
        <link rel="stylesheet" href="<?= $assetUrl ?>css/main.css">
        <!-- educate icon CSS
                    ============================================ -->
        <link rel="stylesheet" href="<?= $assetUrl ?>css/educate-custon-icon.css">
        <!-- morrisjs CSS
                    ============================================ -->
        <link rel="stylesheet" href="<?= $assetUrl ?>css/morrisjs/morris.css">
        <!-- mCustomScrollbar CSS
                    ============================================ -->
        <link rel="stylesheet" href="<?= $assetUrl ?>css/scrollbar/jquery.mCustomScrollbar.min.css">
        <!-- metisMenu CSS
                    ============================================ -->
        <link rel="stylesheet" href="<?= $assetUrl ?>css/metisMenu/metisMenu.min.css">
        <link rel="stylesheet" href="<?= $assetUrl ?>css/metisMenu/metisMenu-vertical.css">
        <!-- calendar CSS
                    ============================================ -->
        <link rel="stylesheet" href="<?= $assetUrl ?>css/calendar/fullcalendar.min.css">
        <link rel="stylesheet" href="<?= $assetUrl ?>css/calendar/fullcalendar.print.min.css">

        <link rel="stylesheet" href="<?= $assetUrl ?>css/dropzone/dropzone.css">
        <link rel="stylesheet" href="<?= $assetUrl ?>css/tree-viewer/tree-viewer.css">
        <!-- style CSS
                    ============================================ -->
        <link rel="stylesheet" href="<?= $assetUrl ?>css/style.css">
        <!-- responsive CSS
                    ============================================ -->
        <link rel="stylesheet" href="<?= $assetUrl ?>css/responsive.css">
        <!-- modernizr JS
                    ============================================ -->
        <script src="<?= $assetUrl ?>js/vendor/modernizr-2.8.3.min.js"></script>
    </head>

    <body>
        <!--[if lt IE 8]>
                    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
            <![endif]-->
        <!-- Start Left menu area -->
        <div class="left-sidebar-pro">
            <nav id="sidebar" class="">
<!--                <div class="sidebar-header">
                    <a href="<?= $assetUrl ?>"><img class="main-logo" src="<?= $assetUrl ?>img/logo/logo.png" alt="" /></a>
                    <strong><a href="<?= $assetUrl ?>"><img src="<?= $assetUrl ?>img/logo/logosn.png" alt="" /></a></strong>
                </div>-->
                <div class="left-custom-menu-adp-wrap comment-scrollbar">
                    <nav class="sidebar-nav left-sidebar-menu-pro">
                        <ul class="metismenu" id="menu1">
                            <li<?php if (strpos($route, 'book') !== false): ?> class="active"<?php endif; ?>>
                                <a class="has-arrow" href="#" aria-expanded="false"><span class="educate-icon educate-library icon-wrap "></span> <span class="mini-click-non">Книги</span></a>
                                <ul class="submenu-angle" aria-expanded="false">
                                    <li><a title="Список книг" href="<?= $view['url']->to('book_list') ?>"><span class="mini-sub-pro">Список книг</span></a></li>
                                    <li><a title="Добавить" href="<?= $view['url']->to('book_edit') ?>"><span class="mini-sub-pro">Добавить</span></a></li>
                                </ul>
                            </li>
                            <li<?php if (strpos($route, 'category') !== false): ?> class="active"<?php endif; ?>>
                                <a class="has-arrow" href="#" aria-expanded="false"><span class="educate-icon educate-library icon-wrap"></span> <span class="mini-click-non">Рубрики</span></a>
                                <ul class="submenu-angle" aria-expanded="false">
                                    <li><a title="Список рубрик" href="<?= $view['url']->to('category_edit') ?>"><span class="mini-sub-pro">Список рубрик</span></a></li>
                                </ul>
                            </li>
                            <li<?php if (strpos($route, 'author') !== false): ?> class="active"<?php endif; ?>>
                                <a class="has-arrow" href="#" aria-expanded="false"><span class="educate-icon educate-library icon-wrap"></span> <span class="mini-click-non">Авторы</span></a>
                                <ul class="submenu-angle" aria-expanded="false">
                                    <li><a title="Список авторов" href="<?= $view['url']->to('author_list') ?>"><span class="mini-sub-pro">Список авторов</span></a></li>
                                    <li><a title="Добавить" href="<?= $view['url']->to('author_edit') ?>"><span class="mini-sub-pro">Добавить</span></a></li>
                                </ul>
                            </li>
                            <li<?php if (strpos($route, 'publisher') !== false): ?> class="active"<?php endif; ?>>
                                <a class="has-arrow" href="#" aria-expanded="false"><span class="educate-icon educate-library icon-wrap"></span> <span class="mini-click-non">Издательства</span></a>
                                <ul class="submenu-angle" aria-expanded="false">
                                    <li><a title="Список издательств" href="<?= $view['url']->to('publisher_list') ?>"><span class="mini-sub-pro">Список издательств</span></a></li>
                                    <li><a title="Добавить" href="<?= $view['url']->to('publisher_edit') ?>"><span class="mini-sub-pro">Добавить</span></a></li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
            </nav>
        </div>
        <!-- End Left menu area -->
        <!-- Start Welcome area -->
        <div class="all-content-wrapper">
            <div class="product-status mg-b-15">
                 <?php $view['slots']->output('_content') ?>
            </div>
            <div class="footer-copyright-area">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="footer-copy-right">
                                <p>Template by <a href="https://colorlib.com/wp/templates/">Colorlib</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- jquery
                    ============================================ -->
        <script src="<?= $assetUrl ?>js/vendor/jquery-1.12.4.min.js"></script>
        <!-- bootstrap JS
                    ============================================ -->
        <script src="<?= $assetUrl ?>js/bootstrap.min.js"></script>
        <!-- wow JS
                    ============================================ -->
        <script src="<?= $assetUrl ?>js/wow.min.js"></script>
        <!-- price-slider JS
                    ============================================ -->
        <script src="<?= $assetUrl ?>js/jquery-price-slider.js"></script>
        <!-- meanmenu JS
                    ============================================ -->
        <script src="<?= $assetUrl ?>js/jquery.meanmenu.js"></script>
        <!-- owl.carousel JS
                    ============================================ -->
        <script src="<?= $assetUrl ?>js/owl.carousel.min.js"></script>
        <!-- sticky JS
                    ============================================ -->
        <script src="<?= $assetUrl ?>js/jquery.sticky.js"></script>
        <!-- scrollUp JS
                    ============================================ -->
        <script src="<?= $assetUrl ?>js/jquery.scrollUp.min.js"></script>
        <!-- mCustomScrollbar JS
                    ============================================ -->
<!--        <script src="<?= $assetUrl ?>js/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
        <script src="<?= $assetUrl ?>js/scrollbar/mCustomScrollbar-active.js"></script>-->
        <!-- metisMenu JS
                    ============================================ -->
        <script src="<?= $assetUrl ?>js/metisMenu/metisMenu.min.js"></script>
        <script src="<?= $assetUrl ?>js/metisMenu/metisMenu-active.js"></script>
        <!-- morrisjs JS
                    ============================================ -->
        <script src="<?= $assetUrl ?>js/sparkline/jquery.sparkline.min.js"></script>
        <script src="<?= $assetUrl ?>js/sparkline/jquery.charts-sparkline.js"></script>
        <!-- calendar JS
                    ============================================ -->
        <script src="<?= $assetUrl ?>js/calendar/moment.min.js"></script>
        <script src="<?= $assetUrl ?>js/calendar/fullcalendar.min.js"></script>
        <script src="<?= $assetUrl ?>js/calendar/fullcalendar-active.js"></script>
        <!-- plugins JS
                    ============================================ -->
        <script src="<?= $assetUrl ?>js/tree-line/jstree.min.js"></script>

        <!-- main JS
                    ============================================ -->
        <script src="<?= $assetUrl ?>js/main.js"></script>
        <!-- tawk chat JS
                    ============================================ -->
    <!--    <script src="<?= $assetUrl ?>js/tawk-chat.js"></script>-->
    </body>

</html>

