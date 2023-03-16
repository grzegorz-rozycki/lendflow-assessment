# Lendflow - Backend Assessment

## How to run the project

Prerequisites:

- git,
- composer
- PHP >= 8.1

Steps:

- Clone the repository, then cd into the projects root directory.
- Run `composer install`.
- Run `composer run post-root-package-install`.
- Run `composer run post-create-project-cmd`.
- Set your NYT API key inside `.env`.
- Start development server via `php artisan serve`.
- open [http://localhost:8000/](http://localhost:8000/).

Go to [GET Best-sellers](/api/1/nyt/best-sellers) endpoint.

## How to run tests

You can run the test suite via `php artisan test`.

## About some implementation decisions

Most of my decisions where driven by the YAGNI approach.
I don't like to overcomplicate the code I write.

I did not add any service and DTO classes as it felt like overengineering at this point.
The API call logic lives in a controller method which IMO is fine ATM.
If the requirements would change and we would want to, e.g.

- use a stub for the NYT API during local development,
- add caching to the responses returned by NYT API.

I would move the method into a service, hide it behind an interface and use `Illuminate\Support\Manager`
to select appropriate implementation based on some ENV setting.

Using the `Http` client also allows for testability without actually hitting the NYT API,
so that's another reason for not adding those classes ATM.
