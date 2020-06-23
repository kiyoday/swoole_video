<?php
namespace App\Lib\Cache;

use EasySwoole\EasySwoole\Config;
use App\Model\Video as videoModel;

class Video
{
    public function setIndexVideo(){
        $catIds = array_keys(Config::getInstance()->getconf('CATEGORY')) ;
        array_unshift($catIds,0);

        $videoModelObj = new videoModel();
        foreach ($catIds as $catId) {
            $condiction = [];
            if(!empty($catId)){
                $condiction['cat_id'] = $catId;
            }
            try {
                $data = $videoModelObj->getVideoCacheData($condiction,1);
            }catch (\Exception $e){
                $data = [];
            }

            if (empty($data)){
                continue;
            }
            foreach ($data as &$list) {
                $list['create_time'] = date("Ymd H:i:s",$list['create_time']);
                //tips: 转为合适时间格式 "%H:%M:%S"
                $list['video_duration'] = gmstrftime("%M:%S", $list['video_duration']);
            }

            //json形式写入文件当中
            //TODO 文件不存在新建文件夹
            $flag = file_put_contents(EASYSWOOLE_ROOT."/webroot/video/json/{$catId}.json",json_encode($data));
            if(empty($flag)){
                //TODO 缓存失效报警
                echo "cat_id:{$catId} put data error".PHP_EOL;
            }else{
                echo "cat_id:{$catId} put data success".PHP_EOL;
            }
        }
    }
}