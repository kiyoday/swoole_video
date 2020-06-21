<?php
namespace App\Lib;

/**
 * 做一些反射机制有关的 处理
 */
class ClassArr {

    //定义类对应命名空间
	public function uploadClassStat() {
		return [
			"image" => "\App\Lib\Upload\Image",
			"video" => "\App\Lib\Upload\Video",
		];
	}
    //获得类名
	public function initClass($type, $supportedClass, $params = [], $needInstance = true) {
		if(!array_key_exists($type, $supportedClass)) {
			return false;
		}

		$className = $supportedClass[$type];

		return $needInstance ? (new \ReflectionClass($className))->newInstanceArgs($params) : $className;
	}

}