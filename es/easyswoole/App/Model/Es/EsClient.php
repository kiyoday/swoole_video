<?php
namespace App\Model\Es;

use EasySwoole\Component\Singleton;
use EasySwoole\EasySwoole\Config;
use Elasticsearch\ClientBuilder;

class EsClient
{
    use Singleton;
    public $esClient = null ;

    private function __construct()
    {
        try{
            $config = Config::getInstance()->getConf("ELASTICSEARCH");
            $this->esClient = ClientBuilder::create()
                ->setHosts([$config['host']])->build();
        }catch (\Exception $e){
            throw new \Exception('elasticsearch 服务异常');
        }
        if (empty($this->esClient)){
            throw new \Exception('elasticsearch 连接失败');
        }
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        // ... 为可变个数参数
        return $this->esClient->$name(...$arguments);
    }
}