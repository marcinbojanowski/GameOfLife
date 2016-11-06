#!/bin/sh

docker exec -it gameoflife phpunit -c /var/www/tests/unit/phpunit.xml.dist
