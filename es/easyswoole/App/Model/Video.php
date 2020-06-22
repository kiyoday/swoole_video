<?php


namespace App\Model;


use EasySwoole\Component\Di;

class Video extends BaseModel
{
    public $tableName = "video";

    /** 通过条件获取video数据
     * @param array $condition
     * @param int $page
     * @param int $size
     * @throws \Exception
     */
    public function getVideoData($condition = [], $page = 1, $size = 10){
        if (!empty($condition['cat_id'])){
            $this->db->where("cat_id",$condition['cat_id']);
        }
        $this->db->pageLimit = $size;
        $this->db->orderBy('create_time','desc');
        $res = $this->db->paginate($this->tableName,$page);
        //调试：查看最后执行的SQL语句
        //echo $this->db->getLastQuery();
        $data = [
            'page_size' => $size,
            'count'=>$this->db->count,
            'total_page' => $this->db->totalPages,
            'lists' => $res,
        ];
        return $data;
    }
}