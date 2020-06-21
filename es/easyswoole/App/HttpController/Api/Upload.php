<?php


namespace App\HttpController\Api;

use App\Lib\ClassArr;

class Upload extends BaseController
{
    public function file(){
        $request = $this->request();
        $files = $request->getSwooleRequest()->files;
        if(empty($files)){
            return $this->writeJson(400,"上传文件为空",[]);
        }
        $types = array_keys($files);
        $type = $types[0];
        try{
            $classObj = new ClassArr();
            $classStats = $classObj->uploadClassStat();
            $uploadObj = $classObj->initClass($type,$classStats,[$request,$type]);
            $file = $uploadObj->upload();
        }catch (\Exception $e){
            return $this->writeJson(400,$e->getMessage(),[]);
        }
        if(empty($file)){
            return $this->writeJson(400,"上传失败",[]);
        }
        $data = [
            'url' => $file,
        ];
        return $this->writeJson(200,"OK",$data);
    }
}