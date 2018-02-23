Authentication Microservice
======

Phalcon is a web framework delivered as a C extension providing high performance and lower resource consumption.

Microservice is the flexibility architecture.

This is the authentication server that use the Phalcon Framework. This service is provide many api fro register and manage application 
that want to use authentication server for check authrization of user and return the access token.
Please write us if you have any feedback.

Thanks.

NOTE
----
The master branch will always contain the latest stable version. If you wish to check older versions or newer ones currently under development, please switch to the relevant branch.

Get Started
-----------

#### Requirements

We provide the docker compose file to the project so you need the docker-compose in you machine if you want to easily start its.

but if you don't know about docker or you don't want to use it you need

* >= PHP 7.1
* Nginx or apache
* Latest Phalcon Framework extension installed/enabled
* Mongo server



Run the docker for development:
-------------------------------
Check `.docker.env` this fiel contain the config of authentication server you no need to change any thing if you use docker for develop
but if you docker you need to change the config for connect to your database

You can now build, create, start, and attach to containers to the environment for your application. To build the containers use following command inside the project root:

```bash
docker-compose build
```

To start the application and run the containers in the background, use following command inside project root:

```bash
docker-compose up -d
```


Installing Dependencies via Composer
------------------------------------
Eggdigital Phalcon Boilerplate's dependencies must be installed using Composer. Install composer in a common location or in your project:

Run the composer installer:

```bash
docker exec -it authen-ms-api composer install
```
or
```bash
docker exec -it authen-ms-api composer update
```

Postman for access our server
-----------------------------
https://www.getpostman.com/collections/5bf88366d0b46a7ee1eb

Note: you need to add the cost in /etc/hosts

127.0.0.1	authen-ms-api.local


How to use our service
----------------------
1. call api create application authen 
	this api used for register the application and return you application id
	
2. call api encrypt message for encrypt you keyword that you add in the first api
	Don't forget to replace the 5a8f9f0ffdb03a0016205b82 with the id that return from the first api before call

3. call request access token for get the access token
	this api return access token , refresh token exoired time and server no

4. call check access token for validate the token 
	Dont for get add server no the your get from the third api