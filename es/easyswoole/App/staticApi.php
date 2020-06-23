<?php

namespace App;

use EasySwoole\EasySwoole\Crontab\AbstractCronTask;
use EasySwoole\EasySwoole\Task\TaskManager;
use App\Lib\Cache\Video as VideoCache;


class staticApi extends AbstractCronTask
{

    public static function getRule(): string
    {
        return '*/1 * * * *';
    }

    public static function getTaskName(): string
    {
        return  'staticApi';
    }

    function run(int $taskId, int $workerIndex)
    {
        $videoCacheObj = new VideoCache();

        TaskManager::getInstance()->async(function () use ($videoCacheObj){
           $videoCacheObj->setIndexVideo();
        });
    }

    function onException(\Throwable $throwable, int $taskId, int $workerIndex)
    {
        echo $throwable->getMessage();
    }
}