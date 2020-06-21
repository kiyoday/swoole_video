<?php
namespace App\Lib\Redis;
use EasySwoole\Component\Singleton;
use EasySwoole\EasySwoole\Config;
use EasySwoole\Redis\Config\RedisConfig;

class Redis
{
    use Singleton;

    public $redis='';

    private function __construct()
    {
        if (!extension_loaded('redis')){
            throw new \Exception('redis 扩展无法加载');
        }
        try{
            $config = Config::getInstance()->getConf("REDIS");
            $this->redis = new \EasySwoole\Redis\Redis(
                new RedisConfig($config)
            );
        }catch (\Exception $e){
            throw new \Exception('redis 服务异常');
        }
    }

    public function get($key){
        if(empty($key)){
            return '';
        }
        return $this->redis->get($key);
    }
}