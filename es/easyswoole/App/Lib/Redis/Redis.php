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

    public function set($key, $value, $time = 0) {
		if(empty($key)) {
			return '';
		}
		if(is_array($value)) {
			$value = json_encode($value);
		}
		if(!$time) {
			return $this->redis->set($key, $value);
		}
		return $this->redis->setEx($key, $time, $value);
	}

    public function get($key){
        if(empty($key)){
            return '';
        }
        return $this->redis->get($key);
    }

    /** 返回有序集中指定区间内的成员，通过索引，分数从高到底
     * @param $key
     * @param $start
     * @param $stop
     * @param $withScores
     * @return bool|string
     */
    public function zRevRange($key, $start, $stop, $withScores='true'){
        if(empty($key)){
            return false;
        }
        return $this->redis->zRevRange($key, $start, $stop, $withScores);
    }

    /** 有序集合中对指定成员的分数加上增量 increment
     * @param $key
     * @param $number
     * @param $member
     * @return bool|string
     */
    public function zincrby($key, $number, $member){
        if(empty($key) || empty($member)){
            return false;
        }
        return $this->redis->zInCrBy($key, $number, $member);
    }

    /** 当类中不存在该方法时候，直接调用call实现调用底层redis相关的方法
     * @param $name
     * @param $arguments
     */
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        // ... 为可变个数参数
        $this->redis->$name(...$arguments);
    }
}