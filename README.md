# WTWT
###### What's The Weather Today?

This is a small weather app made for a technical challenge. It is based on the
Laravel framework and acquires weather data from an API and displays the data on
a simple webpage. The API only supplies data for The Netherlands.  

[Live version](https://wtwt.ruudwelten.com)

<img src="https://wtwt.ruudwelten.com/img/wtwt.png" alt="Screenshot">

## Requirements

- PHP 7.4
- Composer

## Setup

Configure the application by creating a `.env` file in the root with the
following app specific fields:  

```
DB_CONNECTION=sqlite
DB_DATABASE=database.sqlite

DB_SECONDARY_CONNECTION=mysql
DB_SECONDARY_DATABASE=wtwt
DB_SECONDARY_HOST=127.0.0.1
DB_SECONDARY_DATABASE=wtwt
DB_SECONDARY_USERNAME=wtwt_dbo
DB_SECONDARY_PASSWORD=correct-horse-battery-staple

WEATHER_API_KEY={API key from weerlive.nl}
WEATHER_LOCATION="Utrecht"

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"

NOTIFICATION_EMAIL=notifyme@mydomain.tld
```

Install the project by running:

    $ composer install --no-dev

Create MySQL database, create the SQLite database (use database name and sqlite
filename specified in .env) and run migrate to create the tables:  

    $ touch database/database.sqlite
    $ php artisan migrate

To run the scheduled commands to retrieve weather data from the API and send
notifications by email add the Laravel Scheduler to the cron table. Run:  

    $ crontab -e

And add the following line:

```
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## Run tests

Before running the tests, be sure to install the development dependencies:  

    $ composer install

Once these are installed, run the tests by running:  

    $ php artisan test
    $ php artisan dusk

## Challenge

Create a Laravel app that fetches weather data from an API with a scheduled
task, calculates both fahrenheit and celsius and saves the weather data in two
different databases of different types. Save only the most recent data in one
and save historic data in the other.  

Display the latest data on the homepage and send an email notification if the
temperature raises above a threshold, you can set this threshold.  

You have four hours. If you have extra time, display the historic data in a
graph or implement Laravel Queues.  

After the [initial four hour challenge](https://github.com/ruudwelten/wtwt/tree/f1d4be764b5585861221f89f50497bde42c05e71)
some refactoring has been done to improve the code quality.  

## Possible improvements

- Save historic high and low values for every day, week and month in a separate
  table. This way they don't have to be calculated every time.  

## Acknowledgements

- [Weerlive.nl](https://weerlive.nl) for the
  [KNMI weather API](https://weerlive.nl/delen.php).  
- [Konrad Michalik](http://konradmichalik.eu) for his
  [weather icons](https://github.com/jackd248/weather-iconic).  

## License

This project and the Laravel framework are open-sourced software licensed under
the [MIT license](https://opensource.org/licenses/MIT).
