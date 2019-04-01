[![Build Status](https://travis-ci.org/philipwhitt/yaml-json-templates.svg?branch=master)](https://travis-ci.org/philipwhitt/yaml-json-templates)

YAML/JSON Template Engine
=========================
This turns YAML/JSON files into templates that allows the user to pipe in params. 

**Example YAML template:**
```
foo: {{ foo }}
```
**Example params file:**
```
{
	"foo" : "bar"
}
```
**Output:** 
```
foo: bar
```
**Universal Usage:** 
```
docker run -v ${workingdir}:/config \
	-e "TEMPLATEENGINE_TEMPLATEFILE=example/template.yaml" \
	-e "TEMPLATEENGINE_OUTPUTFILE=example/output.yaml" \
	-e "TEMPLATEENGINE_PARAMS=example/params.json" \
	-i philipw/yamltemplates
```
**Bash Function:**
Put this bash function in your bash profile and you will be able to generate a templated yaml from anywhere on the CLI
```
function generateYaml() {
	TEMPLATEENGINE_TEMPLATEFILE=$0
	TEMPLATEENGINE_OUTPUTFILE=$1
	TEMPLATEENGINE_PARAMS=$2
	TEMPLATEENGINE_MIXIN=$3
	workingdir=$(pwd)

	docker run -v ${workingdir}:/config \
		-e "TEMPLATEENGINE_TEMPLATEFILE=${TEMPLATEENGINE_TEMPLATEFILE}" \
		-e "TEMPLATEENGINE_OUTPUTFILE=${TEMPLATEENGINE_OUTPUTFILE}" \
		-e "TEMPLATEENGINE_PARAMS=${TEMPLATEENGINE_PARAMS}" \
		-e "TEMPLATEENGINE_MIXIN=${TEMPLATEENGINE_MIXIN}" \
		-i philipw/yamltemplates
}
```
See examples folder for a more complete example.

PHP Lib Usage
=============

Install via composer: `philipw/yaml-template-generator`
```
use TemplateEngine\Config;
use TemplateEngine\Template;

$config = new Config(__DIR__.'/example/params.json');
$template = new Template(
	$config, 
	__DIR__.'/example/example.yaml'
);

$template->writeRenderedOutput(__DIR__.'/output.yaml');
```