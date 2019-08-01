<?php
$view->extend('layout');
$publisher = $data['publisher'];
$errors = $info['errors'];
?>
<div class="single-pro-review-area mt-t-30 mg-b-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-payment-inner-st">
                    <ul id="myTabedu1" class="tab-review-design">
                        <li <?php if ($info['current_tab'] == 'publisher'): ?>class="active"<?php endif; ?> ><a href="#publisher">Издательство</a></li>
                        <li <?php if ($info['current_tab'] == 'book' || empty($info['current_tab'])): ?>class="active"<?php endif; ?> ><a href="#book">Книги</a></li>
                    </ul>
                    <div id="myTabContent" class="tab-content custom-product-edit">
                        <div class="product-tab-list tab-pane fade<?php if ($info['current_tab'] == 'publisher'): ?> in active<?php endif; ?>" id="publisher">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="review-content-section">
                                        <div class="row">
                                            <form action="<?= $view['url']->to('publisher_edit', ['id' => $publisher['id']]); ?>" method="POST">
                                                <input type="hidden" name="CSRF_TOKEN" value="<?= '12345'; ?>" />
                                                <input type="hidden" name="publisher[id]" value="<?= $publisher['id']; ?>" />
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="devit-card-custom">
                                                        <div class="form-group">
                                                            <label>Название</label>
                                                            <input type="text" required="true" name="publisher[name]" value="<?= $publisher['name'] ?>" maxlength="128"  class="form-control" placeholder="Название">
                                                            <?php if ($errors['publisher']['name']): ?>
                                                                <div class="alert alert-danger alert-mg-b alert-st-four alert-st-bg3" role="alert">
                                                                    <i class="fa fa-times edu-danger-error admin-check-pro admin-check-pro-clr3" aria-hidden="true"></i>
                                                                    <p class="message-mg-rt"><strong>Не подходит</strong>&nbsp;<?= $errors['publisher']['name'] ?></p>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Адрес</label>
                                                            <input type="text" name="publisher[address]" value="<?= $publisher['address'] ?>" maxlength="255" class="form-control" placeholder="Адрес">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Телефон</label>
                                                            <input type="tel" name="publisher[phone]" value="<?= $publisher['phone'] ?>" maxlength="20" class="form-control" placeholder="Телефон">
                                                        </div>
                                                        <button type="submit" class="btn btn-primary waves-effect waves-light">Сохранить</button>
                                                        <button type="submit" class="btn btn-default-bg waves-effect waves-light">Удалить</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="product-tab-list tab-pane fade<?php if ($info['current_tab'] == 'book'): ?> in active<?php endif; ?>" id="book">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="review-content-section">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <table class="table table-hover table-bordered">
                                                    <tr>
                                                        <th>No</th>
                                                        <th>ISBN</th>
                                                        <th>Название</th>

                                                        <th>&nbsp;</th>
                                                    </tr>
                                                    <?php if (count($data['books'])): ?>
                                                        <?php foreach ($data['books'] as $book): ?>
                                                            <tr>
                                                                <td>&nbsp;</td>
                                                                <td><?= $book['isbn']; ?></td>
                                                                <td><?= $book['name']; ?></td>
                                                                <td>
                                                                    <div class="center-block">
                                                                        <button data-toggle="tooltip" title="Edit" class="pd-setting-ed" onclick="window.location.href = '<?= $view['url']->to('book_edit', ['id' => $book['id']]) ?>'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td class="text-center" colspan="4">Пусто</td>
                                                        </tr>
                                                    <?php endif; ?>
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
        </div>
    </div>
</div>
