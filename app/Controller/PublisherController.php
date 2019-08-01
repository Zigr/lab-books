<?php

namespace App\Controller;

use App\Controller\AppController As Controller;
use App\Exception\AppException;
use App\Model\Publisher;

/**
 * @date Jun 15, 2019
 * @encoding UTF-8
 */
class PublisherController extends Controller
{
    /** 
     * Publisher list and search
     */

    public function publishers()
    {
        $publisher = new Publisher();
        $pubList = $publisher->getList();
        return $this->render('publishers_list',
                [
                    'data' => ['publishers' => $pubList],
                    'info' => [
                        'current_tab' => !empty($this->request->query->get('current_tab')) ? $this->request->query->get('current_tab') : 'publisher',
                        'errors' => $publisher->hasErrors() ? $publisher->errors() : null,
                        'page_title' => 'Список издательств'
                    ]
                ]
        );
    }

    /**
     * Add/Edit Record
     * @param integer $id
     */
    public function publisher($id)
    {
        if ($this->wasPosted())
        {
            $input = $this->request->request->all();
            $data = $input['publisher'];
            $res = null;
            $publiser = (new Publisher(['id' => intval($id)]))->setData($data);
            if ($publiser->validate())
            {
                $res = $publiser->save();
            }
            if ($res)
            {
                //$this->redirect($this->toUrl('publisher_edit', ['id' => $publiser->id]), 201);
                $this->redirectToSelf();
            } else
            {
                $publisherInfo = $publiser->info();
            }
        } else
        {
            $publiser = new Publisher(['id' => intval($id)]);
            $publisherInfo = $publiser->info();
        }
        return $this->render('publisher_view',
                [
                    'data' => $publisherInfo,
                    'info' => [
                        'current_tab' => !empty($this->request->query->get('current_tab')) ? $this->request->query->get('current_tab') : 'publisher',
                        'errors' => ($publiser->errors()) ? $publiser->errors() : null,
                        'page_title' => 'Редактирование издательства'
                    ]
                ]
        );
    }

}
