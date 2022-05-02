# Witam w aplikacji licznik!

Backend Laravel 

Frondend Vue.js

DB sqLite

Aby uruchomic aplickacje:
* copy `.env.example` do `.env`
* run `composer update`
* create `database.sqlite` w database folder
* run `php artisan migrate`
* run `php artisan passport:install`
* run `npm install`
* run `php artisan serve`

kiliknij link `http://127.0.0.1:8000` aby otworzyc frontend 
 
## API ROUTES 
```
+--------+----------+-----------------------------------------+-----------------------------------+---------------------------------------------------------------------------+------------------------------------------------+
| Domain | Method   | URI                                     | Action                                                                    | Middleware                                     |
+--------+----------+-----------------------------------------+-----------------------------------+---------------------------------------------------------------------------+------------------------------------------------+
|        | GET|HEAD | /                                       | Closure                                                                   | web                                            |
|        | POST     | api/login                               | App\Http\Controllers\Api\AuthController@login                             | api                                            |
|        | POST     | api/logout                              | App\Http\Controllers\Api\AuthController@logout                            | api                                            |
|        |          |                                         |                                                                           | App\Http\Middleware\Authenticate:api           |
|        | POST     | api/register                            | App\Http\Controllers\Api\AuthController@register                          | api                                            |
|        | GET|HEAD | api/time                                | App\Http\Controllers\Api\TimeController@getAll                            | api                                            |
|        |          |                                         |                                                                           | App\Http\Middleware\Authenticate:api           |
|        | POST     | api/time                                | App\Http\Controllers\Api\TimeController@create                            | api                                            |
|        |          |                                         |                                                                           | App\Http\Middleware\Authenticate:api           |
|        | GET|HEAD | api/time/$id                            | App\Http\Controllers\Api\TimeController@getSingle                         | api                                            |
|        |          |                                         |                                                                           | App\Http\Middleware\Authenticate:api           |
+--------+----------+-----------------------------------------+-----------------------------------+---------------------------------------------------------------------------+------------------------------------------------+
```
### Controllers 
* `\Http\Controllers\Api`
### Models
* `\Models`
### Services
* `\Services`
### Repositories
* `\Repositories`
### Validators
* `\Validators`

Aplikacje uzywa repository pattern i factory pattern


# Frontend 

Aplikacja pozwala uzytkownikowi na zalogowanie sie lub stworzenie nowego uzytkowinka, 

### Counter:
po zalogowaniu uzytkowniki moze kliknac na `Counter` aby rozpoczac prace licznika.

#### mozlive akcje:

* START
* RESTART
* ZAPISZ CZAS
* * klikniecie na  'Zapisz Czas' zatrzymuje licznik, i umozliwia zapis czasu do DB

### dashboard:
po zalogowaniu uzytkowniki moze kliknac na `dashboard`aby zobaczyc swoje wczesniej zapisane czasy.

#### mozlive akcje:

* FILTRACJA DAT
* DOWNLOAD CSV

### Logout:
Logout 
