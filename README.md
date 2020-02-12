<p align="center"><img src="https://raw.githubusercontent.com/matical/himekawa/master/public/favicon.png"></p>
<p align="center">
    <a href="https://travis-ci.org/matical/himekawa"><img src="https://img.shields.io/travis/matical/himekawa.svg?style=flat-square" alt="Build Status" title="Build Status"></a>
    <a href="https://coveralls.io/github/matical/himekawa?branch=master"><img src="https://img.shields.io/coveralls/github/matical/himekawa/master.svg?style=flat-square" alt="Test Coverage" title="Test Coverage"></a>
    <a href="https://styleci.io/repos/103241043"><img src="https://styleci.io/repos/103241043/shield?branch=master" alt="Style CI Status" title="Style CI Status"></a>
</p>

<p align="center">** incomplete **</p>

# Dependencies
* PHP >=7.4 with [L6.0's Requirements (extensions)](https://laravel.com/docs/6.0#installation)
* Node >=v8*
* Composer + Yarn
* Redis
* Any DB supported by [Eloquent](https://laravel.com/docs/5.8/database#introduction)
    - MariaDB, Postgres and SQLite should all work just fine
* `aapt` (Android Asset Packaging Too)
    - Available standalone on most distros, check your package manager

### Installing
* `git clone https://github.com/matical/himekawa` - Clone the repo
* `composer install` - Install PHP dependencies
* `yarn` - Install frontend assets
* `yarn run prod` - Compile frontend assets
* `cp .env.example .env` or `.env.example.streamline` - See [below](#filling-in-env)
* `php artisan key:generate`
* `php artisan migrate`
* `php artisan apk:import` 
* Check out the [GSF generation guide](https://github.com/matical/himekawa/blob/master/docs/GsfGenerationGuideForWeebApps.md) for filling in the 3 fields.

#### Filling in .env
You can choose to use `.env.example.streamline` if you wish to avoid databases and redis configuration. Note, you'll probably need the sqlite pdo extension if it isn't installed yet.
* `BASE_DIR` - Fully qualified path to where you've installed this project.
* `GOOGLE_LOGIN`, `GOOGLE_PASSWORD`, `ANDROID_ID`

### Scheduler
Two important tasks are scheduler in Laravel's console kernel.
- `apk:update`, runs every 15 minutes
- `apk:prund-old`, runs once a day

If nothing goes wrong, this app is basically requires zero intervention. But time hasn't stood still, so GP occasionally breaks stuff.

## Example Crontab config
```sh
* * * * * php /path/to/project/artisan schedule:run >> /dev/null 2>&1
```

## Installing Apps
Multiple DB engines are supported, so watched apps are synced and configured manually through [`resources/apps.json`](https://github.com/matical/himekawa/blob/master/resources/apps.json).

* `name` - Friendly name, shown on main page.
* `slug` - Used by short links (i.e. https://apk.ksmz.moe/deresute)
* `original_title` - Original game title in Japanese
* `package_name` - Raw package name from google play

Once configured, run `apk:import` to sync with the DB.

### JP region specifics and workarounds
In general, you don't need a JP IP and/or a VPN once you've "downloaded" the region locked app at least once.
