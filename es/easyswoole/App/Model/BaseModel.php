<?php
namespace App\Model;

use EasySwoole\Component\Di;

class BaseModel
{
    public function __construct()
    {
        if(empty($this->tableName)){
            throw new \Exception("table error");
        }
        $db = Di::getInstance()->get("MYSQL");
        if($db instanceof \MysqliDb){
            $this->db=$db;
        }else{
            throw new \Exception('db error');
        }
    }

    /** 插入一条数据
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public function add($data){
        if(empty($data) || !is_array($data)){
            return false;
        }

        return $this->db->insert($this->tableName,$data);
    }

    /** 通过id获得一条数据
     * @param $id
     * @return array|\MysqliDb|string
     * @throws \Exception
     */
    public function getById($id) {
        $id = intval($id);
        if(empty($id)) {
            return [];
        }

        $this->db->where ("id", $id);
        $result = $this->db->getOne($this->tableName);
        return $result ?? [];
    }
}