# Witam w aplikacji licznik!


* Backend - Laravel (PHP)
* Frontend - Vue.js
* DB - sqLite

Aby uruchomic aplickacje:
* copy `.env.example` do `.env`
* run `composer update`
* create `database.sqlite` w database folder (lub skopiuj z \setup_files folderu)
* run `php artisan migrate`
* run `php artisan passport:install`
* run `npm install`
* run `php artisan serve`

kiliknij link `http://127.0.0.1:8000` aby otworzyc frontend 

1 user stworzony przy migration `admin@admin.com` password `admin`

## API ROUTES
'http://127.0.0.1:8000/api'
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
* `app\Http\Controllers\Api`
### Models
* `app\Models`
### Services
* `app\Services`
### Repositories
* `app\Repositories`
### Validators
* `app\Validators`

Aplikacje uzywa repository pattern i factory pattern

Przy twozeniu nowych `Services` albo `Repositories` prosze extend `BaseServices` amd 
`BaseRepository`

# Frontend 

### Login
`admin@admin.com` password `admin`

### Counter:
po zalogowaniu uzytkowniki moze kliknac na `Counter` aby rozpoczac prace licznika.

#### mozliwe akcje:

* START
* RESTART
* ZAPISZ CZAS
* * klikniecie na  'Zapisz Czas' zatrzymuje licznik, i umozliwia zapis czasu do DB

### Dashboard:
po zalogowaniu uzytkowniki moze kliknac na `dashboard`aby zobaczyc swoje wczesniej zapisane czasy.

#### mozliwe akcje:

* FILTRACJA DAT
* DOWNLOAD CSV

### Logout:
Logout 

### Register:
Register 
