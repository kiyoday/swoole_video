<?php


namespace App\HttpController\Api;


class Search extends BaseController
{

    public function index()
    {
        $keyword = trim($this->params['keyword']);
        
    }
}