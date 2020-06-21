<?php


namespace App\Lib\Upload;

use App\Lib\Utils;
use EasySwoole\Http\Request;

class BaseType
{
    //上传文件的 file - key
    public $type = '';
    private $ClientMediaType;

    public function __construct(Request $request,$type = null)
    {
        $this->request = $request;
        if(empty($type)){
            $files = $this->request->getSwooleRequest()->files;
            $types = array_keys($files);
            $this->type = $types[0];
        }else{
            $this->type = $type;
        }


    }

    public function upload(){
        if($this->type != $this->fileType){
            return false;
        }

        $videos = $this->request->getUploadedFile($this->type);
        $this->size = $videos->getSize();
        $this->checkSize();

        $fileName = $videos->getClientFileName();
        //video/mp4形式
        $this->ClientMediaType = $videos->getClientMediaType();
        $this->checkMediaType();//拆分检查

        $file = $this->getFile($fileName);
        $result = $videos->moveTo($file);
        if(!empty($result)){
            return $this->file;
        }
        return false;
    }

    public function getFile($fileName){
        $pathinfo = pathinfo($fileName);
        $extentsion = $pathinfo['extension'];
        //  /video/2020/06
        $dirname = '/'.$this->type.'/'.date("Y").'/'.date("m");
        //  /easyswoole/webroot/video/2020/06
        $dir = EASYSWOOLE_ROOT.'/webroot'.$dirname;
        if(!is_dir($dir)){
            mkdir($dir,0777,true);
        }
        //  /9c5a2c47376e21ae.mp4
        $basename = '/'.Utils::getFileKey($fileName).'.'.$extentsion;
        //返回给前端的  /video/2020/06/9c5a2c47376e21ae.mp4
        $this->file = $dirname.$basename;
        //  保存用的绝对路径
        //  /easyswoole/webroot/video/2020/06/9c5a2c47376e21ae.mp4
        $realpath = $dir.$basename;
        return $realpath;
    }
    
    public function checkMediaType(){
        $clientMediaType = explode("/", $this->ClientMediaType);
        $clientMediaType = $clientMediaType[1] ?? "";
        if(empty($clientMediaType)||
            !in_array($clientMediaType,$this->fileExtTypes)){
            throw new \Exception("上传{$this->type}文件不合法");
        }
    }

    public function checkSize(){
        if(empty($this->size)){
            return false;
        }
    }
}