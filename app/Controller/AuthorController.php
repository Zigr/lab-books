<?php

namespace App\Controller;

use \Symfony\Component\HttpFoundation\JsonResponse;
use App\Controller\AppController As Controller;
use App\Exception\AppException;
use App\Model\Author;

/**
 * @date Jun 16, 2019 
 * @encoding UTF-8
 */
class AuthorController extends Controller
{

    /**
     * Author list and search
     */
    public function authors()
    {
        $author = new Author();
        $authorList = $author->getList();
        return $this->render('authors_list',
                [
                    'data' => ['authors' => $authorList],
                    'info' => [
                        'current_tab' => !empty($this->request->query->get('current_tab')) ? $this->request->query->get('current_tab') : 'author',
                        'errors' => $author->hasErrors() ? $author->errors() : null,
                        'page_title' => 'Список авторов'
                    ]
                ]
        );
    }

    /**
     * Add/Edit Record
     * @param integer $id
     */
    public function author($id)
    {
        if($this->isDelete())
        {
            $this->delete($id);
        }
        
        if ($this->wasPosted())
        {
            $input = $this->request->request->all();
            $data = $input['author'];
            $res = null;
            $author = (new Author(['id' => intval($input['author']['id'])]))->setData($data);
            if ($author->validate())
            {
                $res = $author->save();
            }
            if ($res)
            {
                $this->redirect($this->toUrl('author_list'));
            } else
            {
                $authorInfo = $author->info();
            }
        } else
        {
            $author = new Author(['id' => intval($id)]);
            $authorInfo = $author->info();
        }
        return $this->render('author_view',
                [
                    'data' => $authorInfo,
                    'info' => [
                        'current_tab' => !empty($this->request->query->get('current_tab')) ? $this->request->query->get('current_tab') : 'author',
                        'errors' => ($author->errors()) ? $author->errors() : null,
                        'page_title' => 'Редактирование автора'
                    ]
                ]
        );
    }
    
    public function delete($id){
        $author = new Author(['id'=> intval($id)]);
        $res = null;
        $errors = null;
        if($author->isExists()){
            $res = $author->delete();
        }
        if(! $res){
            $errors = $author->errors();
        }else{
            
        }
        return (new JsonResponse(['status'=> $res ? 'success' : 'error']))->send();
    }

}
