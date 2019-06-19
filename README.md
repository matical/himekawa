<p align="center"><img src="https://raw.githubusercontent.com/matical/himekawa/master/public/favicon.png"></p>
<p align="center">
    <a href="https://travis-ci.org/matical/himekawa"><img src="https://img.shields.io/travis/matical/himekawa.svg?style=flat-square" alt="Build Status" title="Build Status"></a>
    <a href="https://coveralls.io/github/matical/himekawa?branch=master"><img src="https://img.shields.io/coveralls/github/matical/himekawa/master.svg?style=flat-square" alt="Test Coverage" title="Test Coverage"></a>
    <a href="https://styleci.io/repos/103241043"><img src="https://styleci.io/repos/103241043/shield?branch=master" alt="Style CI Status" title="Style CI Status"></a>
</p>

<p align="center">** incomplete **</p>

# Dependencies
* PHP >7.2 with [L5.8's Requirements (extensions)](https://laravel.com/docs/5.8#installation)
* Composer + Yarn
* Redis
* Any DB supported by [Eloquent](https://laravel.com/docs/5.8/database#introduction)
    - MariaDB, Postgres and SQLite should all work just fine
* [node-google-play-cli](https://github.com/dweinstein/node-google-play-cli)
* `aapt` (Android Asset Packaging Too)
    - Available standalone on most distros, check your package manager

### Installing
* `git clone https://github.com/matical/himekawa` - Clone the repo
* `composer install` - Install PHP dependencies
* `yarn` - Install frontend assets
* `yarn run prod` - Compile frontend assets
* `cp .env.example .env`
    - Fill in your secrets here. Fields should be pretty self explanatory.
* `php artisan key:generate`
* `php artisan migrate`
* `php artisan apk:import` - Populates the watch list with apps from `resources/apps.json`
* Check out the [GSF generation guide](https://github.com/matical/himekawa/blob/master/docs/GsfGenerationGuideForWeebApps.md) for filling in the 3 fields.

### Scheduler
Two important tasks are scheduler in Laravel's console kernel.
- `apk:update`, runs every 15 minutes
- `apk:prund-old`, runs once a day

If nothing goes wrong, this app is basically requires zero intervention. But time hasn't stood still, so GP occasionally breaks stuff.

## Example Crontab config
```sh
* * * * * php /path/to/project/artisan schedule:run >> /dev/null 2>&1
```
