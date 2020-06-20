<?php


namespace App\HttpController\Api;


class Upload extends BaseController
{
    public function file(){
        $request = $this->request();
        $video = $request->getUploadedFile("file");
        var_dump($video);
    }
}