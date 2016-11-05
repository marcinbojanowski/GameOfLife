/bin/sh

# run phpunit in /var/www dir 
docker exec -it gameoflife sh -c 'cd /var/www/;  phpunit'

# todo: unit tests bootstraping and execute it like:
# docker exec -it gameoflife phpunit -c /var/www/phpunit.xml.dist