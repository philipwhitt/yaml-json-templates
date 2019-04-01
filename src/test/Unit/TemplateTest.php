<?php

namespace TemplateEngine;

use PHPUnit\Framework\TestCase;

class TemplateTest extends TestCase {

	public function testRenderedOutput_ForYaml() {
		// given
		$expected = 
<<<EOF
foo: bar
nestedFoo: bar
fruit:
    - apple
    - banana

EOF;
		$config = new Config(__DIR__.'/params.json');
		$sut = new Template($config, __DIR__.'/template.yaml');

		// when
		$rendered = $sut->getRenderedOutput();


		// then
		$this->assertEquals($expected, $rendered);
	}

	public function testRenderedOutput_ForJson() {
		// given
		$expected = 
<<<EOF
{
	"foo": "bar",
	"fruit": ["apple","banana"]
}
EOF;
		$config = new Config(__DIR__.'/params.json');
		$sut = new Template($config, __DIR__.'/template.json');

		// when
		$rendered = $sut->getRenderedOutput();

		// then
		$this->assertEquals($expected, $rendered);
	}

}
