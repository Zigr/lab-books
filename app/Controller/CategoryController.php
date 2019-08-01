<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use App\Controller\AppController As Controller;
use App\Exception\AppException;
use App\Model\Category;

/**
 * Description of CategoryController
 *
 * @date Jun 16
 * @encoding UTF-8
 */
class CategoryController extends Controller
{

    public function category($id)
    {

        if ($this->isDelete())
        {
            $this->delete($id);
        }

        if ($this->wasPosted())
        {
            $input = $this->request->request->all();
            $data = $input['category'];
            if(isset($data['parent_id'])){
                $data['parent_id'] = intval($data['parent_id']);
            }
            $res = null;
            $category = (new Category(['id' => intval($input['category']['id'])],true))
                    ->setData($data);
            if ($category->validate())
            {
                $res = $category->save();
            }
            if ($res)
            {
                $this->redirectToSelf();
            } else
            {
                $categoryInfo = $category->info();
            }
        } else
        {
            $category = (new Category(['id' => intval($id)]));
            $categoryInfo = $category->info();
        }

        return $this->render('categories',
                [
                    'data' => $categoryInfo,
                    'info' => [
                        'errors' => $category->hasErrors() ? $category->errors() : null,
                        'page_title' => 'Редактирование рубрики'
                    ]
                ]
        );
    }

    public function categoryAjax()
    {
        $data = Category::all([], ['id', 'parent_id', 'title']);
        
        $tree = array();
        foreach ($data as $key => &$item)
        {
            $item['text'] = $item['title'];
            unset($item['title']);
            $tree[$item['id']] = &$item;
            $tree[$item['id']]['children'] = array();
            $tree[$item['id']]['data'] = [];
        }
        foreach ($data as $key => &$item)
        {
            if ($item['parent_id'] && isset($tree[$item['parent_id']]))
            {
                $tree [$item['parent_id']]['children'][] = &$item;
            }
        }
        foreach ($data as $key => &$item)
        {
            if ($item['parent_id'] && isset($tree[$item['parent_id']]))
            {
                unset($data[$key]);
            }
        }
        return (new JsonResponse(array_values($data)))->send();
    }

    public function delete($id)
    {
        $category = new Category(['id' => intval($id)]);
        $res = null;
        $errors = null;
        if ($category->isExists())
        {
            $res = $category->delete($id);
        }
        return (new JsonResponse(['status' => 'success']))->send();
    }

}
