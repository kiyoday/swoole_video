<?php


namespace App\HttpController\Api;


use App\Lib\AliyunSdk\AliVod;
use App\lib\Redis\Redis;
use EasySwoole\Component\Di;
use EasySwoole\Http\AbstractInterface\Controller;
use App\Model\Video as videoModel;

class Index extends Controller
{
    public function lists(){
        $params = $this->request()->getRequestParam();

        $videoModel = new videoModel();
        $videoModel->getVideoData([],1,2);
    }

    public function getVideo(){
        $videoId = 'b6d456f4119144db9844d771c04df7e3';
        $obj = new AliVod();
        print_r($obj->getPlayInfo($videoId)) ;
    }

    public function getMysql(){
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

    public function testAli(){
        $obj = new AliVod();
        $title = "testVideo";
        $videoName = 'testvideo111.mp4';
        $result = $obj->create_upload_video($title,$videoName);
//        $videoName =json_decode(base64_decode($result->videoName,true));
        $uploadAddress = (array)json_decode(base64_decode($result->UploadAddress,true));
        $uploadAuth = (array)json_decode(base64_decode($result->UploadAuth,true));

        $obj->initOssClient($uploadAuth, $uploadAddress);

        $videoFile= '/easyswoole/webroot/testvideo111.mp4';
        $result = $obj->upload_local_file($uploadAddress, $videoFile);
        print_r($result);
    }
}