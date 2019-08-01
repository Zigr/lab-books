<!-- Mobile Menu start -->
<div class="mobile-menu-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="mobile-menu">
                    <nav id="dropdown" style="display: block;">
                        <ul>
                            <li><a data-toggle="collapse" data-target="#demopro" href="#">Книги <span class="admin-project-icon edu-icon edu-down-arrow"></span></a>
                                <ul id="demopro" class="collapse dropdown-header-top">
                                    <li><a title="Список книг" href="<?= $view['url']->to('book_list') ?>">Список книг</a></li>
                                    <li><a title="Добавить" href="<?= $view['url']->to('book_edit', ['']) ?>">Добавить</a></li>
                                </ul>
                            </li>                                        
                            <li><a data-toggle="collapse" data-target="#demopro1" href="#">Рубрики <span class="admin-project-icon edu-icon edu-down-arrow"></span></a>
                                <ul id="demopro1" class="collapse dropdown-header-top">
                                    <li><a title="Список рубрик" href="<?= $view['url']->to('category_edit') ?>">Список рубрик</a></li>
                                </ul>
                            </li>     
                            <li><a data-toggle="collapse" data-target="#demopro2" href="#">Авторы <span class="admin-project-icon edu-icon edu-down-arrow"></span></a>
                                <ul id="demopro2" class="collapse dropdown-header-top">
                                    <li><a title="Список авторов" href="<?= $view['url']->to('author_list') ?>">Список авторов</a></li>
                                    <li><a title="Добавить" href="<?= $view['url']->to('author_edit', ['']) ?>">Добавить</a></li>
                                </ul>
                            </li>   
                            <li><a data-toggle="collapse" data-target="#demopro3" href="#">Издательства <span class="admin-project-icon edu-icon edu-down-arrow"></span></a>
                                <ul id="demopro3" class="collapse dropdown-header-top">
                                    <li><a title="Список издательств" href="<?= $view['url']->to('publisher_list') ?>">Список издательств</a></li>
                                    <li><a title="Добавить" href="<?= $view['url']->to('publisher_edit') ?>">Добавить</a></li>
                                </ul>
                            </li>   
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Mobile Menu end -->
