#!/bin/sh

docker build -t gameoflife .
docker run --rm --name gameoflife -p 80:80 -v $(pwd)/src:/var/www gameoflife
