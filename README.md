# Recipe
This is a backend service responsible for handling request around Recipes and its related entities such as 
ingredients, and steps. 

#WIP
## Getting started
To get started with development there are two options:

### Option 1 - Docker
If you are running with Docker, setup is easy as 
`docker-compose up --build`

### Option 2 - Local


#### Prerequisites

- PHP 7.4+
- Composer  


#### Steps

- `composer install`
- `php artisan migrate:fresh --seed`
- `php artisan jwt:secret`
- `php artisan ide-helper:generate` (optional if you want your IDE to understand Laravel's magic)
- `php artisan ide-helper:models` (optional if you want your IDE to understand Laravel's magic)

## Making changes

Pull requests must be raised against develop.
A version update must be included in `config/api.php` and in the `composer.json` file 

### Adding DB migrations
To add new database migrations, run the following command.
```shell script
    php artisan make:migration <your-table-name>
```
