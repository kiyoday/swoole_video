<?php


namespace App\HttpController;


use EasySwoole\Http\AbstractInterface\Controller;

class Category extends Controller
{

    public function index()
    {
        $this->response()->write('this is cate');
    }
}