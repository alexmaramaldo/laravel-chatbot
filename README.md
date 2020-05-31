# AlexMaramaldo ChatBank

[![Build Status](https://travis-ci.org/joemccann/dillinger.svg?branch=master)](http://chatbot-env.eba-mmc6apjs.us-west-2.elasticbeanstalk.com)

AlexMaramaldo ChatBank is a friendly chat to execute basic operations in your account bank. Through some basic commands, it is possible to deposit, withdraw, even in different currencies.

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

# How to install on Linux Server

-   Clone the project on your web folder `git clone https://asdasdasads`;
-   After you instaled all requirements, you need change the ROOT Folder from Apache to WORKDIR_TO_PROJECT/public, this is because Laravel work with a subfolder to allow the access on the project;
-   Create the database on MySQL;
-   On project, you need copy the `.env.example` to `.env`;
-   Change the values on `.env`, mainly on `database connections`;

```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=default
DB_USERNAME=default
DB_PASSWORD=secret
```

-   You can import the `database/backup.sql` on your database or run the migrate command from the Laravel `php artisan migrate`, you will receive a question if you have shure to run on Production Enviroment, type `yes`;
-   Open your browser `http://yourhost`

# How to runner using docker

You can run the app using docker, for it, you need have a Docker previous installed in your server/computer

-   Download and run the image, change before the params with your connection database

```shell
docker run -d -t -i -e DB_CONNECTION="mysql" \
    -e DB_HOST='host.docker.internal' \
    -e DB_PORT=3306 \
    -e DB_DATABASE="chatbot" \
    -e DB_USERNAME="root" \
    -e DB_PASSWORD="secret" \
    -p 8000:80 \
--name alexmaramaldo-chatbot alexmaramaldo/chatbot
```

-   You can import the `database/backup.sql` on your database or run the migrate command from the Laravel Docker `docker exec -it alexmaramaldo-chatbot php artisan migrate`, you will receive a question if you have shure to run on Production Enviroment, type `yes`;
-   Open your browser `http://yourhost:8000`

##### Plus: Command to run using docker with sqlite(without MySQL)

-   Download and run the image, don't need change the value, only the PORT if you need.

```shell
docker run -d -t -i -e DB_HOST='host.docker.internal' \
    -e DB_CONNECTION="sqlite" \
    -p 8000:80 \
    --name alexmaramaldo-chatbot alexmaramaldo/chatbot
```

-   You need tun the migrate command from the Laravel Docker to create tables on sqlite `docker exec -it alexmaramaldo-chatbot php artisan migrate`, you will receive a question if you have shure to run on Production Enviroment, type `yes`;
-   Open your browser `http://yourhost:8000`

# Videos tutorials

You can also:

-   Import and save files from GitHub, Dropbox, Google Drive and One Drive
-   Drag and drop markdown and HTML files into Dillinger
-   Export documents as Markdown, HTML and PDF

Markdown is a lightweight markup language based on the formatting conventions that people naturally use in email. As [John Gruber] writes on the [Markdown site][df1]

> The overriding design goal for Markdown's
> formatting syntax is to make it as readable
> as possible. The idea is that a
> Markdown-formatted document should be
> publishable as-is, as plain text, without
> looking like it's been marked up with tags
> or formatting instructions.

This text you see here is _actually_ written in Markdown! To get a feel for Markdown's syntax, type some text into the left window and watch the results in the right.

### Tech

Dillinger uses a number of open source projects to work properly:

-   [AngularJS] - HTML enhanced for web apps!
-   [Ace Editor] - awesome web-based text editor
-   [markdown-it] - Markdown parser done right. Fast and easy to extend.
-   [Twitter Bootstrap] - great UI boilerplate for modern web apps
-   [node.js] - evented I/O for the backend
-   [Express] - fast node.js network app framework [@tjholowaychuk]
-   [Gulp] - the streaming build system
-   [Breakdance](https://breakdance.github.io/breakdance/) - HTML to Markdown converter
-   [jQuery] - duh

And of course Dillinger itself is open source with a [public repository][dill]
on GitHub.

### Installation

Dillinger requires [Node.js](https://nodejs.org/) v4+ to run.

Install the dependencies and devDependencies and start the server.

```sh
$ cd dillinger
$ npm install -d
$ node app
```

For production environments...

```sh
$ npm install --production
$ NODE_ENV=production node app
```

### Plugins

Dillinger is currently extended with the following plugins. Instructions on how to use them in your own application are linked below.

| Plugin           | README                                    |
| ---------------- | ----------------------------------------- |
| Dropbox          | [plugins/dropbox/README.md][pldb]         |
| GitHub           | [plugins/github/README.md][plgh]          |
| Google Drive     | [plugins/googledrive/README.md][plgd]     |
| OneDrive         | [plugins/onedrive/README.md][plod]        |
| Medium           | [plugins/medium/README.md][plme]          |
| Google Analytics | [plugins/googleanalytics/README.md][plga] |

### Development

Want to contribute? Great!

Dillinger uses Gulp + Webpack for fast developing.
Make a change in your file and instantaneously see your updates!

Open your favorite Terminal and run these commands.

First Tab:

```sh
$ node app
```

Second Tab:

```sh
$ gulp watch
```

(optional) Third:

```sh
$ karma test
```

#### Building for source

For production release:

```sh
$ gulp build --prod
```

Generating pre-built zip archives for distribution:

```sh
$ gulp build dist --prod
```

### Docker

Dillinger is very easy to install and deploy in a Docker container.

By default, the Docker will expose port 8080, so change this within the Dockerfile if necessary. When ready, simply use the Dockerfile to build the image.

```sh
cd dillinger
docker build -t joemccann/dillinger:${package.json.version} .
```

This will create the dillinger image and pull in the necessary dependencies. Be sure to swap out `${package.json.version}` with the actual version of Dillinger.

Once done, run the Docker image and map the port to whatever you wish on your host. In this example, we simply map port 8000 of the host to port 8080 of the Docker (or whatever port was exposed in the Dockerfile):

```sh
docker run -d -p 8000:8080 --restart="always" <youruser>/dillinger:${package.json.version}
```

Verify the deployment by navigating to your server address in your preferred browser.

```sh
127.0.0.1:8000
```

#### Kubernetes + Google Cloud

See [KUBERNETES.md](https://github.com/joemccann/dillinger/blob/master/KUBERNETES.md)

### Todos

-   Write MORE Tests
-   Add Night Mode

## License

MIT

**Free Software, Hell Yeah!**

[//]: # "These are reference links used in the body of this note and get stripped out when the markdown processor does its job. There is no need to format nicely because it shouldn't be seen. Thanks SO - http://stackoverflow.com/questions/4823468/store-comments-in-markdown-syntax"
[dill]: https://github.com/joemccann/dillinger
[git-repo-url]: https://github.com/joemccann/dillinger.git
[john gruber]: http://daringfireball.net
[df1]: http://daringfireball.net/projects/markdown/
[markdown-it]: https://github.com/markdown-it/markdown-it
[ace editor]: http://ace.ajax.org
[node.js]: http://nodejs.org
[twitter bootstrap]: http://twitter.github.com/bootstrap/
[jquery]: http://jquery.com
[@tjholowaychuk]: http://twitter.com/tjholowaychuk
[express]: http://expressjs.com
[angularjs]: http://angularjs.org
[gulp]: http://gulpjs.com
[pldb]: https://github.com/joemccann/dillinger/tree/master/plugins/dropbox/README.md
[plgh]: https://github.com/joemccann/dillinger/tree/master/plugins/github/README.md
[plgd]: https://github.com/joemccann/dillinger/tree/master/plugins/googledrive/README.md
[plod]: https://github.com/joemccann/dillinger/tree/master/plugins/onedrive/README.md
[plme]: https://github.com/joemccann/dillinger/tree/master/plugins/medium/README.md
[plga]: https://github.com/RahulHP/dillinger/blob/master/plugins/googleanalytics/README.md
