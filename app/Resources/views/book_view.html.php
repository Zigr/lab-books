<?php
$view->extend('layout');
$book = $data['book'];
$errors = $info['errors'];
?>
<div class="single-pro-review-area mt-t-30 mg-b-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-payment-inner-st">
                    <h4><?= $info['page_title'] ?></h4><br />
                    <ul id="myTabedu1" class="tab-review-design">
                        <li <?php if ($info['current_tab'] == 'book' || empty($info['current_tab'])): ?>class="active"<?php endif; ?> ><a href="#book">Книга</a></li>
                        <li <?php if ($info['current_tab'] == 'photo'): ?>class="active"<?php endif; ?> ><a href="#photo">Фото</a></li>
                    </ul>
                    <div id="myTabContent" class="tab-content custom-product-edit">
                        <div class="product-tab-list tab-pane fade<?php if ($info['current_tab'] == 'book'): ?> in active<?php endif; ?>" id="book">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="review-content-section x-editable-area">
                                        <div class="row">
                                            <form action="<?= $view['url']->to('book_edit', ['id' => $book['id']]); ?>" method="POST">
                                                <input type="hidden" name="CSRF_TOKEN" value="<?= '12345'; ?>" />
                                                <input type="hidden" name="book[id]" value="<?= $book['id']; ?>" />
                                                <input type="hidden" name="book[authors_orig]" value="<?= implode(',', $book['authors']) ?>" />
                                                <input type="hidden" name="book[categories_orig]" value="<?= implode(',', $book['categories']) ?>" />
                                                <table id="user" class="table table-bordered table-striped x-editor-custom">
                                                    <tbody>
                                                        <tr>
                                                            <td width="30%">
                                                                <div class="form-group">
                                                                    <label>Название</label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group">
                                                                    <input type="text" name="book[name]" value="<?= $book['name'] ?>" maxlength="255"  class="form-control" placeholder="Название">
                                                                    <?php if (isset($errors['book']['name'])): ?>
                                                                        <div class="alert alert-danger alert-mg-b alert-st-four alert-st-bg3" role="alert">
                                                                            <i class="fa fa-times edu-danger-error admin-check-pro admin-check-pro-clr3" aria-hidden="true"></i>
                                                                            <p class="message-mg-rt"><strong>Исправьте, пожалуйстат</strong>&nbsp;<?= $errors['book']['name'] ?></p>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="form-group">
                                                                    <label>ISBN</label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group">
                                                                    <input type="text" name="book[isbn]" value="<?= $book['isbn'] ?>" maxlength="255" class="form-control" placeholder="ISBN">
                                                                    <?php if (isset($errors['book']['isbn'])): ?>
                                                                        <div class="alert alert-danger alert-mg-b alert-st-four alert-st-bg3" role="alert">
                                                                            <i class="fa fa-times edu-danger-error admin-check-pro admin-check-pro-clr3" aria-hidden="true"></i>
                                                                            <p class="message-mg-rt"><strong>Исправьте, пожалуйста</strong>&nbsp;<?= $errors['book']['isbn'] ?></p>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="form-group">
                                                                    <label>Рубрика</label>
                                                                    <p>Ctrl+click для выбора нескольких</p>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group">
                                                                    <select class="form-control" name="book[categories][]" multiple="true" size="5">
                                                                        <?php foreach ($info['categories'] as $category): ?>
                                                                            <option value="<?= $category['id'] ?>"<?php if (in_array($category['id'], $book['categories'])): ?> selected="true"<?php endif; ?> ><?= $category['title'] ?></option>
                                                                        <?php endforeach; ?>
                                                                    </select> 
                                                                    <?php if (isset($errors['book']['categories'])): ?>
                                                                        <div class="alert alert-danger alert-mg-b alert-st-four alert-st-bg3" role="alert">
                                                                            <i class="fa fa-times edu-danger-error admin-check-pro admin-check-pro-clr3" aria-hidden="true"></i>
                                                                            <p class="message-mg-rt"><strong>Исправьте, пожалуйста</strong>&nbsp;<?= $errors['publisher']['name'] ?></p>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="form-group">
                                                                    <label>Издательство</label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group">
                                                                    <select class="form-control" name="book[publisher_id]">
                                                                        <option value="">Выберите из списка</option>
                                                                        <?php foreach ($info['publishers'] as $publisher): ?>
                                                                            <option value="<?= $publisher['id'] ?>"<?php if ($publisher['id'] == $book['publisher_id']): ?> selected="true"<?php endif; ?>  ><?= $publisher['name'] ?></option>
                                                                        <?php endforeach; ?>
                                                                    </select> 
                                                                    <?php if (isset($errors['book']['publishers'])): ?>
                                                                        <div class="alert alert-danger alert-mg-b alert-st-four alert-st-bg3" role="alert">
                                                                            <i class="fa fa-times edu-danger-error admin-check-pro admin-check-pro-clr3" aria-hidden="true"></i>
                                                                            <p class="message-mg-rt"><strong>Исправьте, пожалуйста</strong>&nbsp;<?= $errors['book']['publishers'] ?></p>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="form-group">
                                                                    <label>Дата издательства</label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group">
                                                                    <input type="text" name="book[issued_at]" value="<?= $book['issued_at'] ?>" maxlength="20" class="form-control" placeholder="Дата издательства">
                                                                    <?php if (isset($errors['book']['issued_at'])): ?>
                                                                        <div class="alert alert-danger alert-mg-b alert-st-four alert-st-bg3" role="alert">
                                                                            <i class="fa fa-times edu-danger-error admin-check-pro admin-check-pro-clr3" aria-hidden="true"></i>
                                                                            <p class="message-mg-rt"><strong>Исправьте, пожалуйста</strong>&nbsp;<?= $errors['book']['issued_at'] ?></p>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="form-group">
                                                                    <label>Автор</label>
                                                                    <p>Ctrl+click для выбора нескольких</p>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group">
                                                                    <select class="form-control" name="book[authors][]" multiple="true" size="5">
                                                                        <?php foreach ($info['authors'] as $author): ?>
                                                                            <option value="<?= $author['id'] ?>"<?php if (in_array($author['id'], $book['authors'])): ?> selected="true"<?php endif; ?> ><?= $author['name'] ?></option>
                                                                        <?php endforeach; ?>
                                                                    </select> 
                                                                    <?php if (isset($errors['book']['authors'])): ?>
                                                                        <div class="alert alert-danger alert-mg-b alert-st-four alert-st-bg3" role="alert">
                                                                            <i class="fa fa-times edu-danger-error admin-check-pro admin-check-pro-clr3" aria-hidden="true"></i>
                                                                            <p class="message-mg-rt"><strong>Исправьте, пожалуйста</strong>&nbsp;<?= $errors['book']['authors'] ?></p>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <button type="submit" class="btn btn-primary waves-effect waves-light">Сохранить</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="product-tab-list tab-pane fade<?php if ($info['current_tab'] == 'photo'): ?> in active<?php endif; ?>" id="photo">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="review-content-section">
                                        <div id="dropzone" class="pro-ad addcoursepro">
                                            <form action="<?= $view['url']->to('file_process'); ?>" method="POST" enctype="multipart/form-data" class="dropzone_" id="file_upload">
                                                <input type="hidden" name="MAX_FILE_SIZE" value="<?= config('application.image.max_upload_size'); ?>" />
                                                <input type="hidden" name="CSRF_TOKEN" value="<?= '12345'; ?>" />
                                                <input type="hidden" name="book[id]" value="<?= $book['id']; ?>" />
                                                <input type="hidden" name="current_tab" value="<?= $info['current_tab']; ?>" />
                                                <input type="hidden" name="book[files_orig]" value="<?= implode(',', $book['categories']) ?>"
                                                       <div class="row">
                                                    <div class="bs-glyphicons"> 
                                                        <ul class="bs-glyphicons-list"> 
                                                            <?php if (empty($book['files'])): ?>
                                                                <li>
                                                                    Пока нет фото
                                                                </li>
                                                            <?php else: ?>
                                                                <?php foreach ($book['files'] as $photo): ?>
                                                                    <li>
                                                                        <i class="glyphicon glyphicon-remove-circle" style="position: relative; right: -40px; top:-10px; cursor: pointer;" onclick="deletePhoto(<?= $book['id'] ?>,<?= $photo['id'] ?>)"></i>
                                                                        <img alt="<?= $photo['source_name'] ?>" src="<?= $view['url']->to('file_display', ['template' => 'medium', 'filename' => $photo['name'] . '.jpeg']) ?>" />
                                                                    </li>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </ul>
                                                    </div>                                                  
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="fallback">
                                                            <input type="file" name="file[]" multiple="true"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <?php if (!empty($errors['book']['files'])): ?>
                                                            <div class="alert alert-danger alert-mg-b alert-st-four alert-st-bg3" role="alert">
                                                                <i class="fa fa-times edu-danger-error admin-check-pro admin-check-pro-clr3" aria-hidden="true"></i>
                                                                <p class="message-mg-rt"><strong>Исправьте, пожалуйста</strong>&nbsp;<?= $errors['book']['authors'] ?></p>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div class="payment-adress mg-t-15 responsive-mg-t-0">
                                                            <button type="submit" class="btn btn-primary waves-effect waves-light">Сохранить</button>
                                                        </div>
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
</div>
<style>
    .bs-glyphicons {
        margin: 0 -10px 20px;
        overflow: hidden;
    }
    .bs-glyphicons-list {
        padding-left: 0;
        list-style: none;
    }
    .bs-glyphicons li {
        float: left;
        width: 25%;
        height: 100%;
        padding: 10px;
        font-size: 10px;
        line-height: 1.4;
        text-align: center;
        background-color: #f9f9f9;
        border: 1px solid #fff;
    }
    .bs-glyphicons .glyphicon {
        margin-top: 5px;
        margin-bottom: 10px;
        font-size: 24px;
    }
    .bs-glyphicons .glyphicon-class {
        display: block;
        text-align: center;
        word-wrap: break-word; /* Help out IE10+ with class names */
    }
    .bs-glyphicons li:hover {
        color: #fff;
        background-color: #3e8cfe
    }
    .bs-glyphicons li>i:hover {
        display: inline;
    }

    @media (min-width: 768px) {
        .bs-glyphicons {
            margin-right: 0;
            margin-left: 0;
        }
        .bs-glyphicons li {
            width: 12.5%;
            font-size: 12px;
        }
    }
</style>
<script>
    function deletePhoto(bookId, fileId) {
        if (!confirm('Вы уверены ?')) {
            return;
        }
        var url = '<?= $view['url']->to('file_process', ['id' => '']) ?>' + '/' + fileId;
        var settings = {
            method: 'DELETE',
            dataType: 'json',
            success: function (response, textStatus, jqXHR) {
                if (response.status == 'success') {
                    window.location.reload();
                } else {
                    alert('Ошибк при добавлении файла');
                }
            }
        }
        $.ajax(url, settings);
    }
</script>