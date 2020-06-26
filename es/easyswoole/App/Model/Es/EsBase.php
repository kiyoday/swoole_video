<?php
namespace App\Model\Es;
use EasySwoole\Component\Di;
class EsBase {
	public $esClient = null;
	public function __construct() {
		$this->esClient = Di::getInstance()->get("ES");
	}

    /** 搜索
     * @param $name
     * @param int $from
     * @param int $size
     * @param string $type
     * @return array
     */
	public function searchByName($name, $from =0, $size = 10, $type = "match") {
		$name = trim($name);
		if(empty($name)) {
			return [];
		}
		$params = [
            "index" => $this->index,
            "type" => $this->type,
            'body' => [
                'query' => [
                    $type => [
                        'name' => $name
                    ], 
                ],
                'from' => $from,
                'size' => $size
            ],
        ];

        $result = $this->esClient->search($params);
        return $result;

	}
}