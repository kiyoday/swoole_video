<?php


namespace App\HttpController\Api;


use EasySwoole\EasySwoole\Config;
use App\Model\Video as VideoModel;
use EasySwoole\EasySwoole\Logger;
use EasySwoole\Http\Message\Status;
use EasySwoole\Validate\Validate;

class Video extends BaseController
{
    public function add(){
        $params = $this->request()->getRequestParam();
        $status = Config::getInstance()->getConf("STATUS");
        //Todo 加入日志级别显示
        Logger::getInstance()->log('add'.json_encode($params));

        //Todo 校验封装
        $Validate = new Validate();
        $Validate->addColumn('name', 'name错误')
            ->required('名字不为空')->lengthMin(10,'最小长度不小于10位');
        $Validate->addColumn('url', 'url错误')
            ->required('名字不为空')->url('url格式');
        $Validate->addColumn('image', 'image错误')
            ->required('image不为空');
        $ret = $this->validate($Validate);
        if($ret == false){
            $this->writeJson(Status::CODE_BAD_REQUEST
                ,$Validate->getError()->getFieldAlias()
                ,$Validate->getError()->getErrorRuleMsg()
//                ,"{$Validate->getError()->getField()}
//                    @{$Validate->getError()->getFieldAlias()}
//                    :{$Validate->getError()->getErrorRuleMsg()}"
            );
            return false;
        }

        $data = [
            'name'=>$params['name'],
            'url'=>$params['url'],
            'image'=>$params['image'],
            'content'=>$params['content'],
            'cat_id'=>intval($params['cat_id']),
            'create_time'=>time(),
            'uploader' => 'kiyo',
            'status'=>$status['normal']
        ];
        print_r($data);

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
                ,'提交视频有误',['id'=>0]);
        }


    }
}