#!/bin/sh

docker run --rm --name gameoflife -p 80:80 -v $(pwd)/src:/var/www gameoflife
