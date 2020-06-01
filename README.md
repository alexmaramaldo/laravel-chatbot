# AlexMaramaldo ChatBank

[![Build Status](https://travis-ci.org/joemccann/dillinger.svg?branch=master)](http://chatbot-env.eba-mmc6apjs.us-west-2.elasticbeanstalk.com)

AlexMaramaldo ChatBank is a friendly chat to execute basic operations in your account bank. Through some basic commands, it is possible to deposit, withdraw, even in different currencies. You can see a DEMO version running on Amazon AWS ([http://chatbot-env.eba-mmc6apjs.us-west-2.elasticbeanstalk.com](http://chatbot-env.eba-mmc6apjs.us-west-2.elasticbeanstalk.com)), using Elastic Beanstalk and RDS with mysql.

# Introduction

The project was created on Laravel 7.4, using Mysql 5.7, and tested using Apache 2.4 and Artisan Serve(custom server native from laravel), has an integration with AMDOREN(https://www.amdoren.com/api), my token to be work fine, I got the payment license, and I have 10K requestsper month, but, if you have an account, you can use your `API KEY`, this project has main parts beside default from the Laravel, like:

-   App\Services

```
- AccountService.php
- BotCommandService.php
- CurrencyAPIService.php
- TransactionService.php
```

-   App\Repositores

```
- BaseRepository.php
- TransactionRepository.php
- UserRepository.php
```

-   App\Conversations

```
- DepositConversation.php
- LoginConversation.php
- LogoutConversation.php
- RegisterConversation.php
- WithdrawConversation.php

```

#### Commands available:

-   **help** to show all commands
-   **create** new account to register an account on ChatBank
-   **login** to enter on ChatBank
-   **logout** to leave the ChatBank
-   **my** balance to show your current balance
-   **deposit** to deposit a money on ChatBank
-   **withdraw** to withdraw a money on ChatBank
-   **my currency** to show your current set currency
-   **list currencies** to list all allowed currencies on ChatBank
-   **change my currency** to select a new currency on ChatBank

# Requirements

-   PHP >= 7.2.5
-   BCMath PHP Extension
-   Ctype PHP Extension
-   Fileinfo PHP extension
-   JSON PHP Extension
-   Mbstring PHP Extension
-   OpenSSL PHP Extension
-   PDO PHP Extension
-   Tokenizer PHP Extension
-   XML PHP Extension
-   Apache 2.4
-   MySQL 5.7

# How to run using docker

You can run the app using docker, for it, you need have a Docker previous installed in your server/computer

-   Download and run the image, change before the params with your connection database

```sh
$ docker run -d -t -i -e DB_CONNECTION="mysql" \
    -e DB_HOST='host.docker.internal' \
    -e DB_PORT=3306 \
    -e DB_DATABASE="chatbot" \
    -e DB_USERNAME="root" \
    -e DB_PASSWORD="secret" \
    -p 8000:80 \
--name alexmaramaldo-chatbot alexmaramaldo/chatbot:latest
```

-   You can import the `database/chatbot_2020-05-31.sql` on your database or run the migrate command from the Laravel Docker `$ docker exec -it alexmaramaldo-chatbot php artisan migrate`, you will receive a question if you have shure to run on Production Enviroment, type `yes`;
-   Open your browser `http://yourhost:8000`
-   Extra environments vars that you can set, but, not mandatory:

```
AMDOREN_API_SECRET=
AMDOREN_API_URL=
```

# How to install from scratch

-   Clone the project on your web folder `https://github.com/alexmaramaldo/laravel-chatbot`;
-   On project, you need copy the `.env.example` to `.env`;
-   Install the composer packages `$ composer install`
-   Change the values on `.env`, mainly on `database connections`;

```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=default
DB_USERNAME=default
DB_PASSWORD=secret
```

-   Create the database on MySQL;
-   You can import the `database/chatbot_2020-05-31.sql` on your database or run the migrate command from the Laravel `$ php artisan migrate`, you will receive a question if you have shure to run on Production Enviroment, type `yes`;
-   After you instaled all requirements, case you will use the Apache, you need change the ROOT Folder from Apache to WORKDIR_TO_PROJECT/public, this is because Laravel work with a subfolder to allow the access on the project, If you will use the Artisan Serve, is just run: `php artisan serve`;
-   Open your browser `http://yourhost` or `http://yourhost:8000` for artisan serve
-   Extra environments vars that you can set, but, not mandatory:

```
AMDOREN_API_SECRET=
AMDOREN_API_URL=
```

# PLUS with SQLite: Command to run using docker with sqlite(without MySQL)

-   Download and run the image, don't need change the value, only the PORT if you need.

```sh
$ docker run -d -t -i -e DB_CONNECTION="sqlite" \
    -p 8000:80 \
    --name alexmaramaldo-chatbot alexmaramaldo/chatbot:latest
```

-   Open your browser `http://yourhost:8000`
-   Extra environments vars that you can set, but, not mandatory:

```
AMDOREN_API_SECRET=
AMDOREN_API_URL=
```

# Documentations

-   ER Diagram
    ![alt ER Diagram](http://osbox.com.br/alexmaramaldo-chatbot/ERChatbot.png)
-   Use Case  
    ![alt Use Case](http://osbox.com.br/alexmaramaldo-chatbot/UseCaseV1.png)
-   Activity Diagram
    ![alt Activity Diagram](http://osbox.com.br/alexmaramaldo-chatbot/ChatBotDiagram.png)
