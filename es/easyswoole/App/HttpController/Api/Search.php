<?php


namespace App\HttpController\Api;


use App\Model\Es\EsVideo;
use EasySwoole\Http\Message\Status;

class Search extends BaseController
{

    public function index()
    {
        $keyword = trim($this->params['keyword']);
        if(empty($keyword)) {
            return $this->writeJson(Status::CODE_OK, "OK", $this->getPagingData(0, [], false));
        }

        $esObj = new EsVideo();
        $result = $esObj->searchByName($keyword,$this->params['from'], $this->params['size']);

        if(empty($result)) {
            return $this->writeJson(Status::CODE_OK, "OK", $this->getPagingData(0, [], false));
        }
        //命中数据
        $hits = $result['hits']['hits'];
        //命中个数
        $total = $result['hits']['total'];
        if(empty($total)) {
            return $this->writeJson(Status::CODE_OK, "OK", $this->getPagingData(0, [], false));
        }
        //格式化命中数据
        foreach($hits as $hit) {
            $source = $hit['_source'];
            $resData[] = [
                'id' => $hit['_id'],
                'name' => $source['name'],
                'image' => $source['image'],
                'uploader' => $source['uploader'],
                'create_time' => '', // TODO 在es中添加两个字段
                'video_duration' => '',
                'keywords' => [$keyword]
            ];
        }

        return $this->writeJson(Status::CODE_OK, "OK", $this->getPagingData($total, $resData, false));
    }
}