<?php
$view->extend('layout');
?>
<div class="container-fluid">

    <div class="header-advance-area">
        <div class="header-top-area">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="header-top-wraper"><?php // include menu                              ?></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Mobile Menu start -->
        <?php require_once 'partials/mobile_menu.inc.php'; ?>
        <!-- Mobile Menu end -->
        <?php require_once 'partials/breadcrumb.inc.php'; ?>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="product-status-wrap">
                <div class="container-fluid">
                    <div class="row">
                        <ul>
                            <li><a href="<?= $view['url']->to('migration', ['which' => 'up']) ?>">Создать таблицы</a></li>
                            <li><a href="<?= $view['url']->to('migration', ['which' => 'down']) ?>">Удалить таблицы</a></li>
                            <li><a href="<?= $view['url']->to('migration', ['which' => 'truncate']) ?>">Очистить таблицы</a></li>
                            <li><a href="<?= $view['url']->to('migration', ['which' => 'seed']) ?>">Заполнить тестовыми данными</a></li>
                        </ul>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="sparkline10-list sparkel-pro-mg-t-30 shadow-reset">
                                <div class="sparkline10-hd">
                                    <div class="main-sparkline10-hd">
                                        <h1><?= $info['page_title'] ?></h1>
                                    </div>
                                </div>
                                <div class="sparkline10-graph">
                                    <table class="table table-bordered table-striped table-hover">
                                        <tbody>
                                            <tr><th>Успещно</th></tr>
                                            <?php if (count($data['results'])): ?>
                                                <?php foreach ($data['results'] as $result): ?>
                                                    <tr class="alert-success">
                                                        <td><?= $result ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr><th>&nbsp;</th></tr>
                                            <?php endif; ?>
                                        </tbody>

                                        <tbody>
                                            <tr><th>Ошибки</th></tr>    
                                            <?php if (count($data['errors'])): ?>
                                                <?php foreach ($data['errors'] as $result): ?>
                                                    <tr class="alert-danger">
                                                        <?php if (is_string($result)): ?>
                                                            <td><?= $result ?></td>
                                                        <?php else: ?>
                                                            <?= implode('<br />', $result) ?>
                                                        <?php endif; ?>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr><th>&nbsp;</th></tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>