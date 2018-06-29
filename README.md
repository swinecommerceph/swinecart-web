## SwineCart

SwineCart is an E-Commerce System for Breeder Swine and Boar Semen in the Philippines. It is currently under development in the Institute of Computer Science - University of the Philippines Los Banos (ICS-UPLB). This project is a collaborative effort of the ICS-UPLB, Philippine Council for Agriculture, Aquatic and Natural Resources Research and Development (PCAARRD), and  the Bureau of Animal Industry(BAI) as the secretariat of Accredited Swine Breeders Association of the Philippines (ASBAP).

### Utilizes the following core technologies:

* Laravel v5.3
* Ratchet
* ZeroMQ / needs php-zmq extension
* VueJS v2
* jQuery v2.1
* Materialize CSS v0.99
* Elasticsearch v5.4
* Chikka SMS API

### Configured Laradock environment can be found at:

* https://github.com/swinecommerceph/laradock.git


### Installation
> If errors regarding privilege access occur, prepend the command with __sudo__

##### Installing Docker and docker-compose
Before proceeding to installing Docker and docker-compose. Make sure [Git](www.digitalocean.com/community/tutorials/how-to-install-git-on-ubuntu-16-04) is installed in the operating system.
* [Install Docker](https://docs.docker.com/engine/installation/linux/docker-ce/ubuntu/)
* [Install docker-compose](https://docs.docker.com/compose/install/#alternative-install-options)

##### Setting-up Docker environment
1. Make __Projects__ directory inside the home directory. <br/>
```
  cd ~ && mkdir Projects && cd Projects
```
2. Inside the __Projects__ directory, clone the [swinecommerceph](https://github.com/swinecommerceph/swinecommerceph.git)
and configured [laradock](https://github.com/swinecommerceph/laradock.git) repositories. Note that we are renaming *swinecommerceph* to *swinecart*. <br/>
```
  git clone https://github.com/swinecommerceph/swinecommerceph.git swinecart
  git clone https://github.com/swinecommerceph/laradock.git laradock
```
3. Copy __env-example__ to __.env__ then edit the __.env__ file. For production environment, username and passwords of MySQL and phpMyAdmin should be changed to more secure values. Also, the Certbot configuration should be changed for SSL/TLS certificate issuance. The following variables should at least be configured:
```
  WORKSPACE_INSTALL_NODE=true
  WORKSPACE_INSTALL_ZMQ=true
  WORKSPACE_TIMEZONE=true
  
  PHP_FPM_INSTALL_ZMQ=true
```
4. Make sure that the *applications > volumes* line in __docker-compose.yml__ is configured to be
```
  - ${APPLICATION}/swinecart/:/var/www/swinecart
```
5. Now, move to __nginx/sites__ directory. Rename __swinecart.conf.example__ to __swinecart.conf__. For production environment, uncomment the lines that are in comment to integrate HTTPS protocol for secure client-server communication. More so, server_name should be rewritten from *swinecart.cf* to *www.swinecart.cf* if the reweriting of URL is enabled. The file should look like the ff:
```
  ...
  # For rewriting URL
  server {
      listen 80;

      server_name swinecart.cf;
      return 301 $scheme://www.swinecart.cf$request_uri;
  }

  server {

      listen 80;
      listen [::]:80;

      listen 443;
      listen [::]:443;
      ssl on;
      ssl_certificate /var/certs/cert.pem;
      ssl_certificate_key /var/certs/privkey.pem;

      # Prevents 502 Bad Gateway error
      large_client_header_buffers 8 32k;

      server_name www.swinecart.cf;
      root /var/www/swinecart/public;
      index index.php index.html index.htm;
  ...
```

   - For development environment, make sure to rename *swinecart.cf* to other domain names (e.g. *swinecart.test*). The following should also be appended to __/etc/hosts__ file:
```
  127.0.0.1   swinecart.test
```
6. Move back to __laradock__ root directory and run the following command. Note that those with brackets are optional.
```synbash
  docker-compose up -d nginx mysql elasticsearch [phpmyadmin] [certbot]
```
7. Run the following command and search for the IPv4 address of *laradock_workspace_1*. The address will be used for the communication of our websocket servers and ZMQ messaging library. Take note of the address since it will be used for the application's configuration.
```
  docker network inspect laradock_default
```
  - Disregard subnet mask of the IPv4 address. For example, `172.18.0.3/16` should just be `172.18.0.3` when we put it in the application's configuration.

##### Configuring and Installing Application's library dependencies 
> Before running all the succeeding commands, we have to first enter the workspace container thus run `docker-compose exec --user=laradock workspace bash`. When inside the workspace container move to __swinecart__ directory.

1. Run the following command to install PHP library dependencies
```
  composer install
```
2. Copy _.env.example_ file to _.env_ file then edit variables inside __.env__ configuration file. Variables should be changed according to desired configuration. The following variables should at least be changed:
```
  DB_HOST=mysql
  
  # IPv4 address here from the network inspection of Docker on laradock_default workspace container
  ZMQ_HOST=172.18.0.3
```
3. Now that application configuration is set, we have to generate the application's key for encryption processes
```
  php artisan key:generate
```
4. Migrate tables to database engine
```
  php artisan migrate
```
  - If you want to include dummy data then run the following:
```
  php artisan migrate --seed
```
5. We need to install Javascript library dependencies as well
```
  npm install
```
6. Lastly, check if __*public/images, public/videos, and public/announcements*__ symbolic links are not broken for display of images and videos.

7. Bash terminal in worksace container can now be terminated by running `exit` command.

### Running/Stopping Docker containers
> Be sure to be in __laradock__ working directory

To start the system, just run:
```
  docker-compose up -d nginx mysql elasticsearch [phpmyadmin] [certbot]
```
To stop the system, just run: 
```
  docker-compose down
```
