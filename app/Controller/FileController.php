<?php

namespace App\Controller;

use App\Controller\AppController As Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Model\EntityFile;
use App\Model\Book;
use App\Lib\UploadFileLocal ;
use App\Lib\UploadFileRemote;
use App\Exception\AppException;

/**
 * @date Jun 14, 2019
 * @encoding UTF-8
 */
class FileController extends Controller
{

    public function process($id = null)
    {
        if ($this->wasPosted())
        {
            if (count($this->request->files))
            {
                $fileModel = new EntityFile();
                $uploadPath = config('application.image.upload_path', APPPATH . DS, 'public/images');
                $book_id = $this->request->request->get('book')['id'];
                $errors = [];

                foreach ($this->request->files->get('file') as $file)
                {
                    $destination = $uploadPath . DS . $book_id . DS . $file->getClientOriginalName();
                    $localFile = new UploadFileLocal($file, $destination);
                    $fileModel->setUploadFile($localFile);

                    $res = $fileModel
                            ->setBook(new Book(['id' => $book_id]))
                            ->save();
                    $fileModel->hasErrors() && $errors[] = $fileModel->errors();
                }
                !count($errors) ? $this->redirect($this->toUrl('book_edit', ['id' => $book_id, 'current_tab' => 'photo'])) : '';
            }
        }elseif ($this->isDelete())
        {
            $this->delete($this->request->attributes['id']);
        }
    }
    
    public function delete($fileId){
        $file = new EntityFile(['id'=>$fileId]);
        if($file->isExists()){
           $res =  $file->delete();
        }
        return $res ? (new \Symfony\Component\HttpFoundation\JsonResponse(['status'=>'success']))->send() : (new \Symfony\Component\HttpFoundation\JsonResponse(['status'=>'error']))->send();
    }

    protected function fitImage($template)
    {
        
    }

    public function display($template, $filename)
    {
        $imageStore = config('application.image.upload_path');
        $filename = pathinfo($filename, PATHINFO_FILENAME);
        $fileModel = (new EntityFile())->loadBy(['name'=>$filename]);
        $relPath = $fileModel->filePath();
        $filename = $imageStore . DS . $relPath;
        $meta = $fileModel->getImageinfo();


        $handle = fopen($filename, 'r');
        $contents = fread($handle, filesize($filename));
        fclose($handle);
        $etag = md5($contents);
        $not_modified = isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == $etag;
        $contents = $not_modified ? NULL : $contents;
        $status_code = $not_modified ? 304 : 200;

        $mime = $meta->mime;
        $filesize = $meta->size;

        ob_end_clean();
        // return http response
        $resp = new \Symfony\Component\HttpFoundation\Response($contents, $status_code, array(
            'Content-Type' => $mime,
            'Cache-Control' => 'max-age=' . 24 * 60 . ', public',
            'Content-Length' => $filesize,
            'Etag' => $etag
        ));
        $resp->send();
    }

}
