<?php
namespace EasySwoole\EasySwoole;

use App\Lib\Cache\Video as VideoCache;
use App\lib\Redis\Redis;
use App\staticApi;
use EasySwoole\Component\Di;
use EasySwoole\Component\Timer;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\FastCache\Cache;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\EasySwoole\Crontab\Crontab;

class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        // TODO: Implement initialize() method.
        date_default_timezone_set('Asia/Shanghai');
    }

    public static function mainServerCreate(EventRegister $register)
    {
        // TODO: Implement mainServerCreate() method.

        //注册缓存事件
        Cache::getInstance()->setTempDir(EASYSWOOLE_TEMP_DIR)->attachToServer(ServerManager::getInstance()->getSwooleServer());
        //mysql单例模式
        Di::getInstance()->set('MYSQL',\MysqliDb::class,Array
        	(
            'host' => 'db',
            'username' => 'root',
            'password' => 'root',
            'db'=> 'es',
            'port' => 3306,
            'charset' => 'utf8')
        );
        Di::getInstance()->set('REDIS',Redis::getInstance());

        // 开始一个定时任务计划
//        Crontab::getInstance()->addTask(staticApi::class);

        // 每隔 2 秒执行一次
//        Timer::getInstance()->loop(2 * 1000, function () {
//            echo "this timer runs at intervals of 2 seconds\n";
//        });
        $videoCacheObj = new VideoCache();

        $register->add(EventRegister::onWorkerStart, function (
            $server , $workerId) use ($videoCacheObj) {
            if($workerId==0){
                Timer::getInstance()->loop(10 * 1000, function() use($videoCacheObj, $workerId) {
                    $videoCacheObj->setIndexVideo();
                });
            }
         });}

    public static function onRequest(Request $request, Response $response): bool
    {
        // TODO: Implement onRequest() method.
        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
}