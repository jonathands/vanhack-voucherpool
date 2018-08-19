

#About the Setup 
This contains both a Docker setup for PHP + Nginx taken from https://github.com/mtxr/docker-php-nginx and a Slim App inside the files/www/vhtest directory

`docker build -t server .`

`docker run -p 80:80 -it server /bin/sh` or `docker run -d -p 80:80 -it server /bin/sh` to run as daemon.
