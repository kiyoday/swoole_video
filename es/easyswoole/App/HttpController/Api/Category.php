<?php


namespace App\HttpController\Api;


use EasySwoole\Http\AbstractInterface\Controller;

class Category extends BaseController
{

//    protected function onRequest(?string $action): ?bool
//    {
//        return true;
//    }

    protected function onException(\Throwable $throwable): void
    {
        $this->writeJson(400,'请求错误');
    }

    public function video()
    {
        $data = Array(
            'id'=> 1,
            'name'=>'kiyo',
            'params'=>$this->request()->getRequestParam(),
        );
        $this->writeJson(200,$data,'这个是成功消息');
    }
    public function index(){

    }
}