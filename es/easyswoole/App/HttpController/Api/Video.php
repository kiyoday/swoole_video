<?php


namespace App\HttpController\Api;


class Video extends BaseController
{
    public function add(){
        $params = $this->request()->getRequestParam();

        $data = [
            'name'=>$params['name'],
            'url'=>$params['url'],
            'image'=>$params['image'],
            'content'=>$params['content'],
            'cate_id'=>$params['cate_id']
        ];

        return $this->writeJson(200,'OK',$params);
    }
}