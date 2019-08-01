<?php
$view->extend('layout');
?>
<div class="container-fluid">
    <div class="header-advance-area">
        <div class="header-top-area">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="header-top-wraper"><?php // include main menu    ?></div>
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
                    <a href="<?= $view['url']->to('author_edit', ['id' => '']) ?>">Добавить</a>
                </div>
                <div class="asset-inner">
                    <table class="table table-bordered table-striped table-hover">
                        <tr>
                            <th>No</th>
                            <th>Имя</th>
                            <th>&nbsp;</th>
                        </tr>
                        <?php foreach ($data['authors'] as $author): ?>
                            <tr>
                                <td>&nbsp;</td>
                                <td><?= $author['name']; ?></td>
                                <td>
                                    <button data-toggle="tooltip" title="Изменить" class="pd-setting-ed" onclick="window.location.href = '<?= $view['url']->to('author_edit', ['id' => $author['id']]) ?>'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                                    <button data-toggle="tooltip" onclick="deleteAuthor(<?= $author['id'] ?>)" title="Удалить" class="pd-setting-ed"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php require_once('partials/pagination.inc.php'); ?>
            </div>
        </div>
    </div>
</div>
<script>
    function deleteAuthor(authorId) {
        if (!confirm('Вы уверены ?')) {
            return;
        }
        var url = '<?= $view['url']->to('author_edit', ['id' => '']) ?>' + '/' + authorId;
        var settings = {
            method: 'DELETE',
            dataType: 'json',
            success: function (response, textStatus, jqXHR) {
                if (response.status == 'success') {
                    window.location.reload();
                } else {
                    alert('Ошибка при удалении автора');
                }
            }
        }
        $.ajax(url, settings);
    }
</script>