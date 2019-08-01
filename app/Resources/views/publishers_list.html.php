<?php
$view->extend('layout');
?>
<div class="container-fluid">
    <div class="header-advance-area">
        <div class="header-top-area">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="header-top-wraper"><?php // include menu  ?></div>
                    </div>
                </div>
            </div>
        </div>
        <?php require_once 'partials/mobile_menu.inc.php'; ?>
        <?php require_once 'partials/breadcrumb.inc.php'; ?>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="product-status-wrap">
                <h4><?= $info['page_title'] ?></h4>
                <div class="add-product">
                    <a href="<?= $view['url']->to('publisher_edit', ['id' => '']) ?>">Добавить</a>
                </div>
                <div class="asset-inner">
                    <table class="table table-bordered table-striped table-hover">
                        <tr>
                            <th>No</th>
                            <th>Название</th>
                            <th>Адрес</th>
                            <th>Телефон</th>
                            <th>&nbsp;</th>
                        </tr>
                        <?php if (count($data['publishers'])): ?>
                            <?php foreach ($data['publishers'] as $publisher): ?>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td><?= $publisher['name']; ?></td>
                                    <td><?= $publisher['address']; ?></td>
                                    <td><?= $publisher['phone']; ?></td>
                                    <td><?= $view['url']->to('publisher_edit', ['id' => $publisher['id'], 'current_tab' => 'book']) ?></td>
                                    <td>
                                        <a data-toggle="tooltip" title="Список книг" class="pd-setting-ed" href="<?= $view['url']->to('publisher_edit', ['id' => $publisher['id'], 'current_tab' => 'book']) ?>"><i class="fa fa-book" aria-hidden="true"></i></a>
                                        <button data-toggle="tooltip" title="Изменить" class="pd-setting-ed" onclick="window.location.href = '<?= $view['url']->to('publisher_edit', ['id' => $publisher['id']]) ?>'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                                        <button data-toggle="tooltip" title="Удалить" class="pd-setting-ed"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">Пусто</td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
                <?php require_once('partials/pagination.inc.php'); ?>
            </div>
        </div>
    </div>
</div>
