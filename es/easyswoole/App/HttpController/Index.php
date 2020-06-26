<?php


namespace App\HttpController;


use App\Lib\AliyunSdk\AliVod;
use EasySwoole\Component\Di;
use EasySwoole\Http\AbstractInterface\Controller;
use Elasticsearch\ClientBuilder;

class Index extends Controller
{


    public function index()
    {
        $file = EASYSWOOLE_ROOT.'/vendor/easyswoole/easyswoole/src/Resource/Http/welcome.html';
        if(!is_file($file)){
            $file = EASYSWOOLE_ROOT.'/src/Resource/Http/welcome.html';
        }
        $this->response()->write(file_get_contents($file));
    }

    public function es()
    {
        $params = [
            "index" => "es",
            "type" => "video",
            'body' => [
                'query' => [
                    'match' => [
                        'name' => '名字'
                    ],
                ],
            ],
        ];
        $client = ClientBuilder::create()
            ->setHosts(["es01"])->build();

        $result = $client->search($params);
        $this->response()->write(json_encode($result));
    }

    function test()
    {
        $this->response()->write('this is test');
    }

    function testAli()
    {
        $obj = new AliVod();
        $title = "etststs";
        $obj->create_upload_video();
    }

    protected function actionNotFound(?string $action)
    {
        $this->response()->withStatus(404);
        $file = EASYSWOOLE_ROOT.'/vendor/easyswoole/easyswoole/src/Resource/Http/404.html';
        if(!is_file($file)){
            $file = EASYSWOOLE_ROOT.'/src/Resource/Http/404.html';
        }
        $this->response()->write(file_get_contents($file));
    }
}