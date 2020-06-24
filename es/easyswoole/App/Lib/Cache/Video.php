<?php
namespace App\Lib\Cache;

use EasySwoole\Component\Di;
use EasySwoole\EasySwoole\Config;
use App\Model\Video as videoModel;
use EasySwoole\FastCache\Cache;

class Video
{
    public function setIndexVideo(){
        $catIds = array_keys(Config::getInstance()->getconf('CATEGORY')) ;
        array_unshift($catIds,0);
        //获取缓存类型
        $cacheType = Config::getInstance()->getconf('INDEX_CACHE');

        $videoModelObj = new videoModel();

        // 写 json 缓存数据
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

            //三套方案
            switch ($cacheType) {
                case 'file':
                    //json形式写入文件当中  使用缓存实现
                    $flag = file_put_contents($this->getVideoCatIdFile($catId), json_encode($data));
                    break;
                case 'table':
                    $flag = Cache::getInstance()->set($this->getCatKey($catId), json_encode($data));
                    break;
                case 'redis':
                    $res = Di::getInstance()->get("REDIS")->set($this->getCatKey($catId), json_encode($data));
            	 	break;
                default:
            		throw new \Exception("请求不合法");
            		break;
            }

            if(empty($flag)){
                //TODO 缓存失效报警
                echo "cat_id:{$catId} put data error".PHP_EOL;
            }else{
                echo "cat_id:{$catId} put data success".PHP_EOL;
            }
        }
    }

    public function getCache($catId = 0) {
        $cacheType = Config::getInstance()->getconf('INDEX_CACHE') ;

		switch ($cacheType) {
			case 'file':
				$videoFile = $this->getVideoCatIdFile($catId);
				$videoData = is_file($videoFile) ? file_get_contents($videoFile) : [];
				$videoData = !empty($videoData) ? json_decode($videoData, true) : [];
				break;

			case 'table':
				$videoData = Cache::getInstance()->get($this->getCatKey($catId));
				$videoData = !empty($videoData) ? json_decode($videoData, true) : [];
				break;
			case 'redis':
			 	$videoData = Di::getInstance()->get("REDIS")->get($this->getCatKey($catId));
			 	$videoData = !empty($videoData) ? json_decode($videoData, true) : [];
			 	break;
			default:
				throw new \Exception("请求不合法");
				break;
		}

		return $videoData;
	}

    public function getVideoCatIdFile($catId = 0) {
		return EASYSWOOLE_ROOT."/webroot/video/json/".$catId.".json";
	}

    public function getCatKey($catId = 0) {
		return "index_video_data_cat_id_".$catId;
	}
}