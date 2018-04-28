<p align="center"><img src="https://raw.githubusercontent.com/matical/himekawa/master/public/favicon.png"></p>
<p align="center">
    <a href="https://styleci.io/repos/110443572"><img src="https://styleci.io/repos/103241043/shield?branch=master" alt="Style CI Status" title="Style CI Status"></a>
    <a href="https://travis-ci.org/kbkyzd/shiraishi"><img src="https://img.shields.io/travis/matical/himekawa.svg?style=flat-square" alt="Build Status" title="Build Status"></a>
    <a href="https://coveralls.io/github/kbkyzd/shiraishi?branch=master"><img src="https://img.shields.io/coveralls/github/matical/himekawa/master.svg?style=flat-square" alt="Test Coverage" title="Test Coverage"></a>
</p>

<p align="center">** incomplete **</p>

# Dependencies
* PHP >7.1
* Laravel 5.6 [Requirements](https://laravel.com/docs/5.6#installation)
* Redis
* [`google-play-cli`](https://github.com/dweinstein/node-google-play-cli)
* `aapt` (Available standalone, check your package manager)

## Bootstrapping a local environment
You will need a \*nix environment (WSL works) as [horizon](https://laravel.com/docs/5.5/horizon) requires ext-pcntl (\*nix only PHP extension).

* `git clone https://github.com/kbkyzd/shiraishi` - Clone the repo
* `composer install` - Install PHP dependencies
* `yarn` - Install frontend assets
* `yarn run dev` - Compile frontend assets
* `cp .env.example .env` - Fill in your secrets here. Should be pretty self explantory

## Setting up google-play-cli
`google-play-cli` is just a very simplistic wrapper around the [`gpapi`](https://github.com/dweinstein/node-google-play) node librry. Feed it a package name and it'll return json.

## What google-play-cli does
* Fetch GP's

## What himekawa does
`himekawa` is built around parsing output from the node app.

### Region restricted apps
Since this focuses on scraping weeb apps, here's some tips to get things running.

#### Google Account


## Example Crontab config
```sh
* * * * * php /path/to/project/artisan schedule:run >> /dev/null 2>&1
```

If you're using nvm you might run into funny issues with getting the commands to run properly.
```sh
SHELL=/bin/zsh
* * * * * . $HOME/.zshrc && php $HOME/path/to/project/artisan schedule:run >> /dev/null 2>&1
```
