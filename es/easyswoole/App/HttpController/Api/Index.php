<?php


namespace App\HttpController\Api;


use App\Lib\AliyunSdk\AliVod;
use App\lib\Redis\Redis;
use EasySwoole\Component\Di;
use EasySwoole\Http\AbstractInterface\Controller;
use App\Model\Video as videoModel;
use EasySwoole\Http\Message\Status;
use App\Lib\Cache\Video as videoCache;
use EasySwoole\FastCache\Cache;

class Index extends BaseController
{
    /**
     *直接从mysql中读取
     */
    public function lists0(){
        //写入baseController作为公共处理
//        $params = $this->request()->getRequestParam();
//        $page = $params['page']??1;
//        $size = $params['size']??5;
        $page = $this->params['page'];
        $size = $this->params['size'];
        try {
            $videoModel = new videoModel();
            $data = $videoModel->getVideoData([],$page,$size);
        }catch (\Exception $e){
            return $this->writeJson(Status::CODE_BAD_REQUEST
                ,'error',$e->getMessage());
        }

        if (!empty($data['lists'])){
            foreach ($data['lists'] as &$list) {
                $list['create_time'] = date("Ymd H:i:s",$list['create_time']);
                //tips: 转为合适时间格式 "%H:%M:%S"
                $list['video_duration'] = gmstrftime("%M:%S", $list['video_duration']);
            }
        }
        return $this->writeJson(Status::CODE_OK,'OK',$data);
    }

    public function lists(){
        $catId = !empty($this->params['cat_id']) ? intval($this->params['cat_id']) : 0;

        try {
            $videoData = (new videoCache())->getCache($catId);
        }catch(\Exception $e) {
            return $this->writeJson(Status::CODE_BAD_REQUEST , "请求失败");
        }

        $count = count($videoData);
        //切割分页
        $videoData = array_splice($videoData
            , $this->params['from']
            , $this->params['size']);

        return $this->writeJson(Status::CODE_OK,'OK'
            ,$this->getPagingData($count,$videoData));
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