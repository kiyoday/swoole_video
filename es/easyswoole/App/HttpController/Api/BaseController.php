<?php


namespace App\HttpController\Api;
use EasySwoole\Component\Di;
use EasySwoole\Http\AbstractInterface\Controller;

class BaseController extends controller
{
    public $params = [];
    public function index()
    {

    }

     public function onRequest($action):?bool {

        $this->getParams();
        return true;
    }

    public function getParams() {
        $params = $this->request()->getRequestParam();
        $params['page'] = intval($params['page']??1);
        $params['size'] = intval($params['size']??5);

        $params['from'] = ($params['page'] - 1) * $params['size'];

        $this->params = $params;
    }

    /**
     * @param $count [数据总数]
     * @param $data [数据]
     * @param $isSplice [是否需要分页]
     * @return array
     */
    public function getPagingData($count, $data, bool $isSplice){
        $totalPage = ceil($count/$this->params['size']);

        $data = $data??[];
        if($isSplice) {
            $data = array_splice($data, $this->params['from'], $this->params['size']);
        }
        return [
            'total_page' => $totalPage,
            'page_size' => $this->params['page'],
            'count' => intval($count),
            'lists' => $data,
        ];
    }
    
    /**
     * 重写输出方法
     */
    protected function writeJson($statusCode = 200, $message = null, $result = null){
        if(!$this->response()->isEndResponse()){
            $data = Array(
                "code"=>$statusCode,
                "message"=>$message,
                "result"=>$result
            );
            $this->response()->write(json_encode($data,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
            $this->response()->withHeader('Content-type','application/json;charset=utf-8');
            $this->response()->withStatus($statusCode);
            return true;
        }else{
            trigger_error("response has end");
            return false;
        }
    }
    
}