<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use App\Controller\AppController As Controller;
use App\Exception\AppException;
use App\Model\Database\Migration;
use App\Model\Book;

class IndexController extends Controller
{

    public function index()
    {
        return $this->redirect('/books');
    }

    public function books()
    {
        try
        {
            $book = new Book();
            $bookList = $book->getList();
            return $this->render('books_list',
                    [
                        'data' => $bookList,
                        'info' => [
                            'current_tab' => !empty($this->request->query->get('current_tab')) ? $this->request->query->get('current_tab') : 'book',
                            'errors' => isset($bookList->errors) ? $bookList->errors : null,
                            'page_title' => 'Список книг',
                            'authors' => \App\Model\Author::all(null, ['id', 'name']),
                            'publishers' => \App\Model\Publisher::all(null, ['id', 'name']),
                            'categories' => \App\Model\Category::all(null, ['id', 'parent_id', 'title']),
                            'files' => \App\Model\EntityFile::all(null,['id','name'])
                        ]
                    ]
            );
        } catch (\Exception $ex)
        {
            if ($ex instanceof \Zend\Db\Adapter\Exception\ErrorException || $ex instanceof \Zend\Db\Adapter\Exception\ExceptionInterface)
            {
                echo 'Возможно не выполнена миграция БД' . '<br />';
                echo sprintf('Можно попробовать  <a href="%s">выполнить миграцию</a>', $this->toUrl('migration', ['which' => 'up']));
            } else
            {
                echo $ex->getMessage() . '<br />';
            }
        }
    }

    public function book($id)
    {
        if ($this->isDelete())
        {
            $this->delete($id);
        }

        if ($this->wasPosted())
        {
            $input = $this->request->request->all();
            $data = $input['book'];
            $res = null;
            $book = Book::find($id)
                    ->setData($data);
            if ($book->validate())
            {
                $res = $book->save();
            }
            if ($res)
            {
                $this->redirectToSelf();
            } else
            {
                $bookInfo = $book->info();
            }
        } else
        {
            $book = (new Book(['id' => intval($id)]));
            $bookInfo = $book->info(true);
        }
        return $this->render('book_view',
                [
                    'data' => $bookInfo,
                    'info' => [
                        'authors' => \App\Model\Author::all(null, ['id', 'name']),
                        'publishers' => \App\Model\Publisher::all(null, ['id', 'name']),
                        'categories' => \App\Model\Category::all(null, ['id', 'parent_id', 'title']),
                        'page_title' => 'Редактирование книги',
                        'errors' => $book->hasErrors() ? $book->errors() : null,
                        'current_tab' => !empty($this->request->query->get('current_tab')) ? $this->request->query->get('current_tab') : 'book',
                    ]
                ]
        );
    }

    public function delete($id)
    {
        $book = (new Book(['id' => intval($id)]));
        if ($book->isExists())
        {
            $res = $book->delete($id);
        }
        $data = $res ? ['status' => 'success'] : ['status' => 'error'];
        return (new JsonResponse($data))->send();
    }

    public function migrate()
    {
        $what = func_get_arg(0);
        $migration = new Migration();
        switch (strtolower($what))
        {
            case 'up':
                $result = $migration->up();
                break;
            case 'down':
                $result = $migration->down();
                break;
            case 'seed':
                $result = $migration->seed();
                break;
            case 'truncate':
                $result = $migration->truncate(['*']);
                break;
            default :
                $result = ['results'=>[], 'errors'=>['Unknown Migration Action' . __METHOD__ . ' ' . __LINE__]];
        }
        return $this->render('migration_results', [
            'data' => $result,
            'info' => [
                'page_title' => 'Результаты миграции',
            ]
        ]);
    }

}
