<p align="center"><img src="https://raw.githubusercontent.com/matical/himekawa/master/public/favicon.png"></p>
<p align="center">
    <a href="https://travis-ci.org/matical/himekawa"><img src="https://img.shields.io/travis/matical/himekawa.svg?style=flat-square" alt="Build Status" title="Build Status"></a>
    <a href="https://coveralls.io/github/matical/himekawa?branch=master"><img src="https://img.shields.io/coveralls/github/matical/himekawa/master.svg?style=flat-square" alt="Test Coverage" title="Test Coverage"></a>
    <a href="https://styleci.io/repos/103241043"><img src="https://styleci.io/repos/103241043/shield?branch=master" alt="Style CI Status" title="Style CI Status"></a>
</p>

<p align="center">** incomplete **</p>

# Dependencies
* PHP >7.1/7.2 with [L5.6's Requirements](https://laravel.com/docs/5.6#installation)
* Composer + Yarn/NPM
* Redis
* Any DB supported by [Eloquent](https://laravel.com/docs/5.6/database#introduction)
    - MariaDB, Postgres and SQLite should all work just fine
* [node-google-play-cli](https://github.com/dweinstein/node-google-play-cli)
* `aapt` (Android Asset Packaging Too)
    - Available standalone on most distros, check your package manager

## Developing
* `git clone https://github.com/matical/himekawa` - Clone the repo
* `composer install` - Install PHP dependencies
* `yarn` - Install frontend assets
* `yarn run dev/watch/prod` - Compile frontend assets

### Bootstrapping
* `cp .env.example .env` - Fill in your secrets here. Should be pretty self explantory
* `php artisan key:generate`
* `php artisan serve`

# Design

## google-play-cli
`google-play-cli` is just a very simplistic wrapper around the [`gpapi`](https://github.com/dweinstein/node-google-play) node librry. Feed it a package name and it'll return json.

`himekawa` is built around parsing output from this node app.

## Example Crontab config
```sh
* * * * * php /path/to/project/artisan schedule:run >> /dev/null 2>&1
```

If you're using nvm you might run into funny issues with getting the commands to run properly.
```sh
SHELL=/bin/zsh
* * * * * . $HOME/.zshrc && php $HOME/path/to/project/artisan schedule:run >> /dev/null 2>&1
```
