# Mobile Phone Manufacturers And Models Database
Two database tables whiches includes mobilephone manufacturers and their models.

## How to re-scrape the updated data?

### Requirements

1. Php 8.x
2. Composer
3. Git
4. Mysql

### Steps

#### Run the command to clone repository:

    git clone https://github.com/ilyasozkurt/mobilephone-brands-and-models

#### Go to the scrapper directory:

    cd scrapper

#### Run the command to install dependencies:
    
    composer install

#### Copy .env.example to .env and update the database credentials:

    cp .env.example .env

#### Edit .env file and update the database credentials:

    DB_CONNECTION=mysql
    DB_HOST=
    DB_PORT=
    DB_DATABASE=
    DB_USERNAME=
    DB_PASSWORD=
    ....

#### Run the data to migrate to database:

    php artisan migrate

_This command will create required tables in your database:_

#### Run the command to scrape the data:

    php artisan scrape:devices

## Data Summary

**brands.sql** (116 manufacturer) -> Includes brands

**devices.sql** (10633 model) -> Models related with brand_id

Data mined at **10/09/2021** from gsmarena.com

Sponsored by [offday.app](https://trustlocale.com "Discover the best days off to maximize your holiday!")
