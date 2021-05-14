# DNA Mutation API

API to detect DNA mutation sequences. This is a coding challenge project for Guros development team application. It doesn't have real application or usability.

  - [Requirements](#requirements)
  - [Installation](#installation)
    - [Get the code](#get-the-code)
    - [Project installation](#project-installation)
    - [Create .env file](#create-env-file)
    - [Database](#database)
    - [Document root](#document-root)
  - [API Usage](#api-usage)
    - [Auth](#auth)
    - [Mutation](#mutation)
    - [Stats](#stats)
  - [Unit testing](#unit-testing)

This is a PHP/Laravel application project

## Requirements

- PHP 7.3^
- MySQL 5.6^
- Laravel 8
- Composer


## Installation

### Get the code

The first step is to clone the project to get the code from the Gitlab repository:

[https://gitlab.com/abiside/dna-mutation](https://gitlab.com/abiside/dna-mutation)

Or clone directly from:

```
https://gitlab.com/abiside/dna-mutation.git
```

### Project installation

Once you have downloaded the code into your local environment, go to that directory and run the composer installer.

```
composer install --prefer-dist
```

When the process ends your project should be ready to run.

### Create .env file

To provide the project with the environment custom settings you have to clone the already existing `.env.example` file into a new one `.env` on the root of the project.
### Database

To get the project running we are gonna need a couple of similar MySQL databases. One for the application operation and a second one for the unit tests.

Once you have the databaes created, go to the `.env` file and update the database settings with your current ones.

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=database-name
DB_USERNAME=username
DB_PASSWORD=password
```

If you are gonna run the unit tests you have to set the tests database name as well.

```
DB_TESTING_DATABASE=testing_database
```

If the testing database is on a different MySQL instance then you should define a complete set of database settings for the unit tests database as it is shown.

```
DB_TESTING_HOST=127.0.0.1
DB_TESTING_PORT=3306
DB_TESTING_DATABASE=testing_database
DB_TESTING_USERNAME=username
DB_TESTING_PASSWORD=password
```

And to starting using the app with the database you should run the database migrations. From the project root directory:

```
php artisan migrate --seed
```

It's importante to use the `--seed` paramenter to get the user data to make the auth token for the first time.

### Document root

To enable the application on the web server you have to point the document root to the `/public` folder.
## API Usage

To use the API it's importante to include the next header on every request.

```
Accept: application/json
```

and the base url for the API is

```
https://dna-mutation.lovlisoft.com/api/v1
```

For local environment replace the domain with the one that you defined in your local server.
### Auth

To make any request you need an access token that you can get through the next endpoint.

```
[POST] /auth
```
*Request: POST*

```
{
    "user": "demo@guros.com",
    "password": "demo123"
}
```

*Response: 200*

```
{
    "token": "1|jkvOJoKKBqoztkfEAWAHjqoxhREBEOYBocPviZ4A"
}
```
By default the app includes automatically the user `demo@guros.com` with the password `demo123`, so should make the auth request with that data to get the access token to consume the next endpoints.

The access token needs to be included as a Bearer Authorization header.

```
Authorization: Bearer 1|jkvOJoKKBqoztkfEAWAHjqoxhREBEOYBocPviZ4A
```
### Mutations

The main endpoint is the one that receives the DNA code strings to analyze and define if they have code mutations.

```
[POST] /mutation
```
*Request: POST*

```
{
    "dna":["ATGCGA", "CAGTGC", "TTAAGT", "AGAAGG", "CCCCTA", "TTACTG"]
}
```
*Response: 200*

```
Empty response with 200 status when
find mutations on the code
```

*Response: 403*

```
Empty response with 403 status when it doesn't find mutations
```
### Stats

The main endpoint is the one that receives the DNA code strings to analyze and define if they have code mutations.

```
[GET] /stats
```


*Response: 200*

```
{
    "count_mutations": "2",
    "count_no_mutations": "1",
    "ratio": "0.67"
}
```

## Unit testing

To run the unit tests you just have to go to the project root folder and run the next command.

```
php artisan test
```

