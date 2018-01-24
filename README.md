*** incomplete ***

## Dependencies
* PHP 7.1
* Laravel 5.5 [Requirements](https://laravel.com/docs/5.5#installation)
* Redis
* [`google-play-cli`](https://github.com/dweinstein/node-google-play-cli)
* `aapt` (Available standalone, check your package manager)

## Frontend Assets
* `yarn run dev/prod`

## Setting up `google-play-cli`
`google-play-cli` is just a very simplistic wrapper around the [`gpapi`](https://github.com/dweinstein/node-google-play) node librry. Feed it a package name and it'll return json. `himekawa` is built around parsing this output.

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
