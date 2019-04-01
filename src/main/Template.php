<?php

namespace TemplateEngine;

use cli;
use Mustache_Engine;
use Symfony\Component\Yaml\Yaml;

class Template {

	private $config;
	private $templateSrc;

	public function __construct(Config $config, $templateSrc) {
		$this->config = $config;
		$this->templateSrc = $templateSrc;
	}

	public function writeRenderedOutput($fileName) {
		$tpl = $this->getRenderedOutput();

		if (!is_null($tpl)) {
			cli\line("Generating $fileName");
			file_put_contents(
				$fileName,
				$tpl
			);
		}
	}

	public function getRenderedOutput(): string {
		if (!is_file($this->templateSrc)) {
			cli\err("%YSource template($this->templateSrc) file not found%n");
			return null;
		}

		$values = $this->config->getConfig();
		$pathinfo = pathinfo($this->templateSrc);
		$isYamlTemplate = $pathinfo['extension'] === 'yaml' || $pathinfo['extension'] === 'yml';
		$isJsonTemplate = $pathinfo['extension'] === 'json';

		if ($isYamlTemplate) {
			$values = $this->convertTopLevelArrayToYaml($values);
		}

		if ($isJsonTemplate) {
			$values = $this->convertTopLevelArrayToJson($values);
		}

		$tpl = file_get_contents($this->templateSrc);

		$output = (new Mustache_Engine())->render(
			$this->replaceDoubleToTripleBrackets($tpl),
			$values
		);

		// pretify yaml
		if ($isYamlTemplate) {
			$output = Yaml::dump(Yaml::parse($output));
		}

		return $output;
	}

	private function convertTopLevelArrayToYaml($values) {
		foreach ($values as $key => $value) {
			// is numeric array
			if (is_array($value) && isset($value[0])) {
				$values[$key] = "\n".Yaml::dump($value)."\n";
			}
		}

		return $values;
	}

	private function convertTopLevelArrayToJson($values) {
		foreach ($values as $key => $value) {
			// is numeric array
			if (is_array($value) && isset($value[0])) {
				$values[$key] = json_encode($value);
			}
		}

		return $values;
	}

	private function replaceDoubleToTripleBrackets($template) {
		return preg_replace("/(?<!{){{(?![{\#\/])(.*?|(?R))(?<!})}}(?!})/", '{{{$1}}}', $template);
	}
}

