<?php

namespace App\Lib\AliyunSdk;

require_once EASYSWOOLE_ROOT.'/App/Lib/AliyunSdk/aliyun-php-sdk-core/Config.php';   // 假定您的源码文件和aliyun-php-sdk处于同一目录。
require_once EASYSWOOLE_ROOT.'/App/Lib/AliyunSdk/aliyun-oss-php-sdk-master/autoload.php';

use EasySwoole\EasySwoole\Config;
use vod\Request\V20170321 as vod;
use OSS\OssClient;
use OSS\Core\OssException;

class AliVod{

    public $regionId = 'cn-shanghai';
    public $client;
    public $ossClient;
    //初始化Vod客户端
    public function __construct() {
        $accessKey = Config::getInstance()->getConf("Aliyun");
        $profile = \DefaultProfile::getProfile($this->regionId
            ,$accessKey['accessKeyId']
            ,$accessKey['accessKeySecret']);
        $this->client =  new \DefaultAcsClient($profile);
    }

    //获取视频上传地址和凭证
    public function create_upload_video($title,$videoFileName,$other=[]) {
        $request = new vod\CreateUploadVideoRequest();
        $request->setTitle($title);        // 视频标题(必填参数)
        $request->setFileName($videoFileName); // 视频源文件名称，必须包含扩展名(必填参数)
        key_exists('description',$other)?$request->setDescription($other['description']): null;
        key_exists('coverURL',$other)?$request->setcoverURL($other['coverURL']): null;
        key_exists('tags',$other)?$request->setTags($other['tags']): null;

//        $request->setDescription($other['description']);  // 视频源文件描述(可选)
//        $request->setCoverURL($other['coverURL']); // 自定义视频封面(可选)
//        $request->setTags($other['tags']); // 视频标签，多个用逗号分隔(可选)
        $result = $this->client->getAcsResponse($request);
        if(empty($result)||empty($result->VideoId)){
            throw new \Exception("获取上传凭证不合法");
        }
        return $result;
    }

    //使用上传凭证和地址初始化OSS客户端（注意需要先Base64解码并Json Decode再传入）
    function init_oss_client($uploadAuth, $uploadAddress) {
        $this->ossClient = new OssClient($uploadAuth['AccessKeyId']
            , $uploadAuth['AccessKeySecret']
            , $uploadAddress['Endpoint']
            , false, $uploadAuth['SecurityToken']);
         // 设置请求超时时间，单位秒，默认是5184000秒, 建议不要设置太小，如果上传文件很大，消耗的时间会比较长
        $this->ossClient->setTimeout(86400*7);
        $this->ossClient->setConnectTimeout(10);  // 设置连接超时时间，单位秒，默认是10秒
        return $this->ossClient;
    }

    //上传本地文件
    function upload_local_file($uploadAddress, $localFile) {
        return $this->ossClient->uploadFile($uploadAddress['Bucket']
                                            , $uploadAddress['FileName']
                                            , $localFile);
    }

}