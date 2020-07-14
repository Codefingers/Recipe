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

- PHP 7.4
- Composer


#### Steps

- `composer install`
- `php artisan migrate:fresh --seed`
- `php artisan jwt:secret`
- `php artisan ide-helper:generate` (optional if you want your IDE to understand Laravel's magic)
- `php artisan ide-helper:models` (optional if you want your IDE to understand Laravel's magic)

## Direction
Future plans include:
 - Splitting user management into a separate service to avoid a monolithic service.
