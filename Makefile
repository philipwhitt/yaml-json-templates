NAME=yaml-json-templates

test:
	php ./vendor/bin/phpunit -c src/test/ci.xml

docker:
	docker build --no-cache -t philipw/yamltemplates .
