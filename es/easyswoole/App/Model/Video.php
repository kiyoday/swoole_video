<?php


namespace App\Model;


use EasySwoole\Component\Di;

class Video extends BaseModel
{
    public $tableName = "video";

    public function getVideoData($condition = [], $page = 1, $size = 10){
        if(!empty($size)){
            $this->db->pageLimit = $size;
        }

        $this->db->paginate($this->tableName,$page);
        echo $this->db->getLastQuery();
    }
}