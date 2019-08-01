<?php
$view->extend('layout');
?>
<div class="container-fluid">
    <div class="header-advance-area">
        <div class="header-top-area">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="header-top-wraper"><?php // include menu            ?></div>
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
                <h4><?= $info['page_title'] ?></h4>
                <div class="add-product">
                    <a href="<?= $view['url']->to('book_edit', ['id' => '']) ?>">Добавить</a>
                </div>
                <div class="asset-inner">
                    <table class="table table-bordered table-striped table-hover">
                        <tr>
                            <th>ID</th>
                            <th>ISBN</th>
                            <th>Название</th>
                            <th>Рубрика</th>
                            <th>Автор</th>
                            <th>Издательство</th>
                            <th>Фото</th>
                            <th>&nbsp;</th>
                        </tr>
                        <?php foreach ($data as $item): ?>
                            <tr>
                                <td><?= $item['book']['id']; ?></td>
                                <td><?= $item['book']['isbn']; ?></td>
                                <td><?= $item['book']['name']; ?></td>
                                <td>
                                    <?php foreach ($item['book']['categories'] as $category_id): ?>
                                        <a href="<?= $view['url']->to('category_edit', ['id' => $category_id['id']]) ?>"><?= $info['categories'][$category_id]['title']; ?></a><br>
                                    <?php endforeach; ?>
                                </td>
                                <td>
                                    <?php foreach ($item['book']['authors'] as $author_id): ?>
                                        <a href="<?= $view['url']->to('author_edit', ['id' => $author_id]) ?>"><?= $info['authors'][$author_id]['name']; ?></a><br>
                                    <?php endforeach; ?>
                                </td>
                                <td>
                                    <a href="<?= $view['url']->to('publisher_edit', ['id' => $item['book']['publisher_id']]) ?>"><?= mb_substr($info['publishers'][$item['book']['publisher_id']]['name'], 0, 30); ?></a><br>
                                </td>
                                <td>
                                    <?php foreach ($item['book']['files'] as $photo_id): ?>
                                        <img alt="" src="<?= $view['url']->to('file_display', ['template' => 'small', 'filename' => $info['files'][$photo_id]['name'] . '.jpeg']) ?>" />
                                        <?php break; ?>
                                    <?php endforeach; ?>
                                </td>
                                <td>
                                    <button data-toggle="tooltip" title="Edit" class="pd-setting-ed" onclick="window.location.href = '<?= $view['url']->to('book_edit', ['id' => $item['book']['id']]) ?>'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                                    <button data-toggle="tooltip" title="Trash" class="pd-setting-ed"><i class="fa fa-trash-o" aria-hidden="true" onclick="deleteBook('<?= $item['book']['id'] ?>')"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php require_once('partials/pagination.inc.php'); ?>
            </div>
        </div>
    </div>
    <script>
        function deleteBook(id) {
            if (!confirm('Вы уверены ?')) {
                return;
            }

            var url = '<?= $view['url']->to('book_edit', ['id' => '']) ?>' + '/' + id;
            var settings = {
                method: 'DELETE',
                dataType: 'json',
                success: function (response, textStatus, jqXHR) {
                    if (response.status == 'success') {
                        window.location.reload();
                    }
                }
            }
            $.ajax(url, settings);
        }
    </script>
</div>
