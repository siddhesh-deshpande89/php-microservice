# php-microservice
Handling  api requests using PHP, RabbitMQ Message Broker and Supervisor in Docker container

# Context
We need to create an application that will receive between 100,000 and 1,000,0000 transactions per minute.

Every transaction is going to happen in a different request, so this means that we can receive up to 1,000,000 different requests per minute.

# Architecture Design
<img src="../master/architechture.png" width="550" height="450">

# Getting Started
To get started first clone the repository from github.
``` git clone git@github.com:siddhesh-deshpande89/php-microservice.git ```

### Docker Container
Go to terminal and start the docker container with the following command
``` docker compose up -d ```

Now you should have a docker container running

<img src="https://i.imgur.com/b1MsbKn.png" widith="300" height="200" />

To make any changes edit the ```docker-composer.yml``` file.

### Commands
We will run CLI commands in our docker container. Go to CLI of "APP" container either from dashboard or give following
command based on your container ID.

``` docker exec -it [container_hash] /bin/sh; exit```

To get your App container hash give the following command ```docker ps``` and you will see list of
containers. Choose the ```app``` container id.

Please wait for composer installer service to finish installing all dependencies. Then proceed.

### Migrations and Seeders
Once you are in the App container, go to src folder and execute migrations

``` cd src/ ```

``` vendor/bin/phinx migrate ```

``` vendor/bin/phinx seed:run ```

For Database management go to ```localhost:8081``` we are using Adminer as it is light weight and a standalone library.
Use the following credentials:

```
host: db
username: root
password: demo1234
database: db_mystore (optional)
```

Your database should look something like this now.

<img src="https://i.imgur.com/yPpoLqt.png" widith="300" height="200" />

### Storage
Provide write permissions to the storage folder

``` chmod 777 -R storage/ ```

## Challenge 1: /transactions api 
Pass the following parameters in POST request

```
id: (Valid UUID Eg: 73be5270-cdd5-399d-9b1c-c3381f36e59a) (Required)
sku: (Integer)(Required)
title: (String)(Required)
variant_id: (Valid UUID)(Required)
```
### Message Broker
For message broker service we are using RabbitMQ message broker

Go to ```http://localhost:15672``` and login with the following credentials.
```
username: siddhesh
password: demo1234
```

Once logged in you will see the pending transactions in queue. You can change
the credentials from docker-compose.yml file.

<img src="https://i.imgur.com/G5mbo2N.png" widith="300" height="200" />

## Challenge 2: Transaction Processing Worker
We need to process the message queue. For this we use a process manager named Supervisor.

<img src="https://i.imgur.com/nH2TR9w.png" widith="300" height="200" />

Edit the ```numprocs=1``` config in ```docker/supervisor/config.d/supervisord.conf``` file if you want to increase or decrease number of workers.

Although unnecessary, to test the worker manually, simply run ```php src/worker.php``` in the CLI of your App docker container.

<img src="https://i.imgur.com/dv8m2Mn.png" widith="300" height="200" />


## Transaction Duplication Check: Filesystem Cache
Since we will process more than 100000 transactions per minute, we must avoid querying the database as much as possible.
For this purpose, I have used custom filesystem caching. The cache file exists in ```src/storage/cache```

The worker interacts with Cache layer rather than Database layer. In the future we can move filesystem to Redis cache as well.

## Logging
All log files are in ```src/storage/logs``` folder

For logging, I used custom Logger class which is very light weight and writes to custom channel file.

## Challenge 3: Stress Testing Script

For stress testing I used Guzzle library for HTTP requests which allows asynchronous requests in PHP. 
<img src="https://i.imgur.com/mshR9c8.png" widith="300" height="200" />

To increase concurrency and number of requests simply edit the ```src/worker.php```

In the future I will improve this to use multithreaded approach using python script.

### Unit Tests and Integration Tests

To run tests go to CLI of App container and type ```vendor/bin/phpunit tests --testdox```

<img src="https://i.imgur.com/shPFFjl.png" widith="300" height="200" />

