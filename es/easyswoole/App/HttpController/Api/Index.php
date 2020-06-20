<?php


namespace App\HttpController\Api;


use App\lib\Redis\Redis;
use EasySwoole\Component\Di;
use EasySwoole\Http\AbstractInterface\Controller;

class Index extends Controller
{

    public function getVideo(){
        $db = Di::getInstance()->get("MYSQL");
        $result = $db->where("id", 1)->getOne("video");
        return $this->writeJson(200, 'OK', $result);
    }
    public function getRedis(){
//        $redis->set("key",'value');
//        $result = $redis->get('key');
        $redis = Di::getInstance()->get("REDIS");
        $result = $redis->get('key');
        return $this->writeJson(200, 'OK', $result);
    }

    public function index()
    {
        $file = EASYSWOOLE_ROOT.'/vendor/easyswoole/easyswoole/src/Resource/Http/welcome.html';
        if(!is_file($file)){
            $file = EASYSWOOLE_ROOT.'/src/Resource/Http/welcome.html';
        }
        $this->response()->write(file_get_contents($file));
    }

    function test()
    {
        $this->response()->write('this is test');
    }

    protected function actionNotFound(?string $action)
    {
        $this->response()->withStatus(404);
        $file = EASYSWOOLE_ROOT.'/vendor/easyswoole/easyswoole/src/Resource/Http/404.html';
        if(!is_file($file)){
            $file = EASYSWOOLE_ROOT.'/src/Resource/Http/404.html';
        }
        $this->response()->write(file_get_contents($file));
    }
}