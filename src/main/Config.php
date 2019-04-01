<?php

namespace TemplateEngine;

class Config { 

	private $params;
	private $mixins = [];

	public function __construct($params=null) {
		$this->params = $params ?? [];
	}

	public function addMixinsFile($filePath, $topLevelFilter=null) {
		if (is_file($filePath)) {
			$this->mixins = $this->getValues($filePath, $topLevelFilter);
		}
	}

	public function getConfig($topLevelFilter=null): Array {
		$params = $this->getValues($this->params, $topLevelFilter);

		return array_merge($params, $this->mixins);
	}

	private function getValues($path, $topLevelFilter) {
		$rawJson = file_get_contents($path);

		$json = json_decode($rawJson, true) ?? [];

		if (!is_null($topLevelFilter)) {
			foreach ($json as $key => $value) {
				if ($key != $topLevelFilter) {
					unset($json[$key]);
				}
			}
		}

		return $json;
	}

}