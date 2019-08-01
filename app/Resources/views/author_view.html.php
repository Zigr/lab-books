<?php
$view->extend('layout');
$author = $data['author'];
$errors = $info['errors'];
?>
<div class="single-pro-review-area mt-t-30 mg-b-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-payment-inner-st">
                    <ul id="myTabedu1" class="tab-review-design">
                        <li <?php if ($info['current_tab'] == 'author'): ?>class="active"<?php endif; ?> ><a href="#author">Автор</a></li>
                        <li <?php if ($info['current_tab'] == 'book' || empty($info['current_tab'])): ?>class="active"<?php endif; ?> ><a href="#book">Книги автора</a></li>
                    </ul>
                    <div id="myTabContent" class="tab-content custom-product-edit">
                        <div class="product-tab-list tab-pane fade<?php if ($info['current_tab'] == 'author'): ?> in active<?php endif; ?>" id="author">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="review-content-section">
                                        <div class="row">
                                            <form action="<?= $view['url']->to('author_edit'); ?>" method="POST">
                                                <input type="hidden" name="CSRF_TOKEN" value="<?= '12345'; ?>" />
                                                <input type="hidden" name="author[id]" value="<?= $author['id']; ?>" />
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="devit-card-custom">
                                                        <div class="form-group">
                                                            <input type="text" required="true" name="author[name]" value="<?= $author['name'] ?>" maxlength="128"  class="form-control" placeholder="Название">
                                                            <?php if ($errors['author']['name']): ?>
                                                                <div class="alert alert-danger alert-mg-b alert-st-four alert-st-bg3" role="alert">
                                                                    <i class="fa fa-times edu-danger-error admin-check-pro admin-check-pro-clr3" aria-hidden="true"></i>
                                                                    <p class="message-mg-rt"><strong>Не подходит</strong>&nbsp;<?= $errors['author']['name'] ?></p>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary waves-effect waves-light">Сохранить</button>
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
