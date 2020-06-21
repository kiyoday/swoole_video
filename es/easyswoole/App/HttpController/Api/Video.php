<?php


namespace App\HttpController\Api;


use EasySwoole\EasySwoole\Config;
use App\Model\Video as VideoModel;
use EasySwoole\Http\Message\Status;

class Video extends BaseController
{
    public function add(){
        $params = $this->request()->getRequestParam();
        $status = Config::getInstance()->getConf("STATUS");
        $data = [
            'name'=>$params['name'],
            'url'=>$params['url'],
            'image'=>$params['image'],
            'content'=>$params['content'],
            'cat_id'=>$params['cat_id'],
            'create_time'=>time(),
            'status'=>$status['normal']
        ];

        // 插入
        try{
            $modelObj = new VideoModel();
            $videoId = $modelObj->add($data);
        }catch (\Exception $e){
            return $this->writeJson(Status::CODE_BAD_REQUEST
                ,$e->getMessage());
        }
        if(!empty($videoId)){
            return $this->writeJson(Status::CODE_OK
                ,'OK',['id'=>$videoId]);
        }else{
            return $this->writeJson(Status::CODE_BAD_REQUEST
                ,'提交视频有误',['id'=>$videoId]);
        }


    }
}