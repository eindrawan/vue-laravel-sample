### Starts the Backend

This backend module will not have authentication,
thus for security it will have JWT instead of Laravel Passport

Please make sure that you already have PHP v7+ and MySQL installed,
also all the necessary plugins like php-mysql, php-slite, zip and unzip.
After that you can starts the server by installing the dependencies first:

```sh
$ cd api
$ composer install
$ yarn install
```

then, change the storage folder permission (if needed):

```sh
$ chmod -R o+w storage/
```

then, setup config file

```sh
$ cp .env.example .env
$ php artisan key:generate
$ php artisan jwt:secret
```

after you create the database, and change database connection in the config file
run this command:

```sh
$ php artisan migrate
$ php artisan db:seed
```

finally, starts the server:
(the server will run on port 8000 by default)

```sh
$ php artisan serve
```

and run this command to run the test:

```sh
$ vendor/bin/phpunit
```

### Consume the BackendAPI

| 1.     | Get Account Detail                                       |
| ------ | -------------------------------------------------------- |
| Method | GET                                                      |
| Url    | /api/accounts/{id}                                       |
| Return | { success:true, data:[..account..], token: <jwt_token> } |

| 2.     | Get Transactions                              |
| ------ | --------------------------------------------- |
| Method | GET                                           |
| Url    | /api/accounts/{id}/transactions               |
| Return | { success:true, data:[..transaction_data..] } |

| 3.      | Make Payment/Transfer                                                     |
| ------- | ------------------------------------------------------------------------- |
| Method  | POST                                                                      |
| Url     | /api/accounts/{id}/transactions                                           |
| Payload | { from:<id:number>, to:<id:number>, amount:<:number>, details:<:string> } |
| Return  | { success:true } or { success:false, error:<message>}                     |
