<?php

namespace TemplateEngine;

use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase {

	public function testConfig_WithOut_Mixins() {
		// given
		$expected = [
			"foo" => "bar",
			"nested" => [
				"foo" => "bar",
				"fruit" => ["apple", "banana"]
			],
			"fruit" => ["apple", "banana"]
		];

		$sut = new Config(__DIR__.'/params.json');

		// when
		$config = $sut->getConfig();

		// then
		$this->assertEquals($config, $expected);
	}

	public function testConfig_With_Mixins() {
		// given
		$expected = [
			"foo" => "bar",
			"nested" => [
				"foo" => "bar",
				"fruit" => ["apple", "banana"]
			],
			"fruit" => ["apple", "banana"],
			"baz" => "terd"
		];

		$sut = new Config(__DIR__.'/params.json');

		// when
		$sut->addMixinsFile(__DIR__.'/mixin.json');
		$config = $sut->getConfig();

		// then
		$this->assertEquals($config, $expected);
	}

	public function testConfig_FilterTopLevel() {
		// given
		$expected = [
			"foo" => "bar"
		];

		$sut = new Config(__DIR__.'/params.json');

		// when
		$config = $sut->getConfig('foo');

		// then
		$this->assertEquals($config, $expected);
	}

	public function testConfig_FilterTopLevel_WithMixin_ShouldRetainBothFilters() {
		// given
		$expected = [
			"foo" => "bar",
			"baz" => "terd"
		];

		$sut = new Config(__DIR__.'/params.json');

		// when
		$sut->addMixinsFile(__DIR__.'/mixin.json', 'baz');
		$config = $sut->getConfig('foo');

		// then
		$this->assertEquals($config, $expected);
	}

	public function testConfig_FilterTopLevel_Invalid() {
		// given
		$expected = [
		];

		$sut = new Config(__DIR__.'/params.json');

		// when
		$config = $sut->getConfig('nokey');

		// then
		$this->assertEquals($config, $expected);
	}

}