

# About the Setup 

This contains both a Docker setup for PHP + Nginx taken from https://github.com/mtxr/docker-php-nginx and a Slim App inside the files/www/vhtest directory

`docker build -t server .`

`docker run -p 80:80 -it server /bin/sh` or `docker run -d -p 80:80 -it server /bin/sh` to run as daemon.

# The App and API

This App is a Slim App and has a single page meant to test the 3 API endpoints

* GET /vouchers - list all vouchers 
* GET /vouchers/customer/{id} - list all vouchers used by  the client id
* POST /voucher/save - saves offer and customer , generate 8 digit voucher codes and returns a list of voucher
* POST /voucher/use - takes an email and a code , checks if it has a usage date , else it sets the date and return the discount


SQL dump of the database is under /files/www/vhtest/vhtest-postgre-schema.sql
