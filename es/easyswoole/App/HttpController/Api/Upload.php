<?php


namespace App\HttpController\Api;

use App\Lib\ClassArr;
use EasySwoole\Http\Message\Status;

class Upload extends BaseController
{
    //上传文件方法
    public function file(){
        $request = $this->request();
        $files = $request->getSwooleRequest()->files;
        if(empty($files)){
            return $this->writeJson(Status::CODE_BAD_REQUEST,"上传文件为空",[]);
        }
        $types = array_keys($files);
        $type = $types[0];
        try{
            $classObj = new ClassArr();
            $classStats = $classObj->uploadClassStat();
            $uploadObj = $classObj->initClass($type,$classStats,[$request,$type]);
            $file = $uploadObj->upload();
        }catch (\Exception $e){
            return $this->writeJson(Status::CODE_BAD_REQUEST,$e->getMessage(),[]);
        }
        if(empty($file)){
            return $this->writeJson(Status::CODE_BAD_REQUEST,"上传失败",[]);
        }
        $data = [
            'url' => $file,
        ];
        return $this->writeJson(Status::CODE_OK,"OK",$data);
    }
}