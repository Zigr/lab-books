<?php 
$view->extend('layout');
?>
<div class="container-fluid">

    <div class="header-advance-area">
        <div class="header-top-area">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="header-top-wraper"><?php // include menu                     ?></div>
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
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="sparkline9-list shadow-reset responsive-mg-b-30">
                                <div class="sparkline9-hd">
                                    <div class="main-sparkline9-hd">
                                        <h1>Список рубрик</h1>
                                    </div>
                                </div>
                                <div class="sparkline9-graph">
                                    <div class="edu-content">
                                        <div id="categories"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="sparkline10-list sparkel-pro-mg-t-30 shadow-reset">
                                <div class="sparkline10-hd">
                                    <div class="main-sparkline10-hd">
                                        <h1>Редактирование рубрики</h1>
                                    </div>
                                </div>
                                <div class="sparkline10-graph">
                                    <div class="edu-content">
                                        <form action="<?= $view['url']->to('category_edit'); ?>" method="POST">
                                            <input type="hidden" name="CSRF_TOKEN" value="<?= '12345'; ?>" />
                                            <input type="hidden" id="category_id" name="category[id]" />
                                            <input type="hidden" id="category_id" name="category[parent_id]" />
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="devit-card-custom">
                                                    <div class="form-group">
                                                        <input type="text" required="true" name="category[title]" id="category_title" value="" maxlength="128"  class="form-control" placeholder="Название">
                                                        <?php if (isset($errors['category']['name'])): ?>
                                                            <div class="alert alert-danger alert-mg-b alert-st-four alert-st-bg3" role="alert">
                                                                <i class="fa fa-times edu-danger-error admin-check-pro admin-check-pro-clr3" aria-hidden="true"></i>
                                                                <p class="message-mg-rt"><strong>Пожалуйста, исправьте</strong>&nbsp;<?= $errors['category']['name'] ?></p>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <button type="submit" id="submit_save" class="btn btn-primary waves-effect waves-light" title="Изменить выделенную рубрику">Сохранить</button>
                                                    <button type="submit" id="submit_add" class="btn btn-primary waves-effect waves-light" title="Добавить новую рубрику в выделенную">Добавить</button>
                                                    <br /><br />
                                                    <a id="submit_delete" class="btn btn-default waves-effect waves-light" title="Удалить выбранную рубрику">Удалить</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<script>
    window.onload = function () {
        $(function () {
            $('#categories').jstree({
                'core':
                        {
                            "multiple": false,
                            'data': {
                                "url": "category/ajax",
                                "dataType": "json"
                            }
                        },
                "plugins": ["wholerow", 'search'],
            });

            $('#categories').on("changed.jstree", function (e, data) {
                var i, j, r = [];
                for (i = 0, j = data.selected.length; i < j; i++) {
                    r.push(data.instance.get_node(data.selected[i]).text);
                }
                $('#category_id').prop('value', data.selected);
                $('#category_title').prop('value', r.join(', '));
            });

            $('#submit_save').on('click', function () {
                if (!$('#category_title').prop('value')) {
                    alert('Введите название рубрики');
                    return false;
                }
            })

            $("#button_search").on('click', function () {
                $("#categories").jstree("search", $("#search_input").prop('value'), true);
            });

            $('#submit_delete').on('click', function () {

                if (!$('#category_id').prop('value')) {
                    alert('Выберите рубрику');
                    return false;
                }
                deleteCategory();
            });

            $('#submit_add').on('click', function () {

                if (!$('#category_title').prop('value')) {
                    alert('Выберите рубрику');
                    return false;
                }
                $('[name="category[parent_id]"]').prop('value',$('#category_id').prop('value'));
                $('#category_id').prop('value','');
            });

            function deleteCategory() {
                if (!confirm('Вы уверены ?')) {
                    return false;
                }
                var url = '<?= $view['url']->to('category_edit', ['id' => '']) ?>' + '/' + $('#category_id').prop('value');
                var settings = {
                    method: 'DELETE',
                    dataType: 'json',
                    success: function (response, textStatus, jqXHR) {
                        if (response.status == 'success') {
                            window.location.reload();
                        } else {
                            alert('Ошибка при удалении рубрики');
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        if (textStatus != null) {
                            alert('Ошибка при удалении рубрики');
                        }
                    }
                }
                $.ajax(url, settings);
            }
        });


    }


</script>