# SeegnoTestBundle
[![Latest Version][packagist-image]][packagist-url]
[![Build Status][travis-image]][travis-url]
[![Code Climate][codeclimate-gpa-image]][codeclimate-url]
[![Test Coverage][codeclimate-coverage-image]][codeclimate-url]
[![License][license-image]][packagist-url]

## Introduction

This Bundle provides base classes for unit and integration tests in order to assist in setting test databases and data fixtures.

## Installation

#### 1. Download SeegnoTestBundle using composer

Install SeegnoTestBundle by running the command:

``` bash
$ composer require --dev seegno/test-bundle
```

#### 2. Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    // ...

    if (in_array($this->getEnvironment(), array('test'))) {
        // ...
        $bundles[] = new Seegno\TestBundle\SeegnoTestBundle();
    }
}
```

#### 3. Prepare your Application for tests

**Integration (functional)**

Please add the following configuration in `config_test.yml`:

```yaml
seegno_test:
    database:
        driver: ORM # Types available: ORM (SQL) and ODM (MongoDB)
```

**Warning!!**

It's very important that you configure a different database for tests in your `config_test.yml` file since all data is purged from the database in integration tests.

## Usage

#### Unit tests

```php
use Seegno\TestBundle\TestCase\BaseTestCase;

class SomeClassTest extends BaseTestCase
{
    //...
}
```

#### Available features:

```php
$this->getFaker(); // Get a faker instance.
$this->setReflectionProperty($class, $propertyName, $propertyValue); // Set a class property using reflection.
```

#### Integration tests

```php
use Seegno\TestBundle\TestCase\IntegrationTestCase;

class SomeClassTest extends IntegrationTestCase
{
    //...
}
```

#### Available features:

```php
$this->getContainer(); // Get an instance of the dependency injection container.
$this->getSession(); // Get session.
$this->get('SERVICE'); // Get services.
$this->initializeDatabase(); // Initialize test database (SQL or MongoDB).
```

#### Web tests

```php
use Seegno\TestBundle\TestCase\WebTestCase;

class SomeClassTest extends WebTestCase
{
    //...
}
```

#### Available features:

```php
$this->authenticateUser($client, $user, $credentials, $roles, $firewall); // Authenticate a user.
$this->getClient(); // Get client that simulates a browser and makes requests to a Kernel object.
$this->assertResponseFields($response, $object, $groups); // Assert that object properties keys are in the response.
```

## Run tests

Before running the tests, make sure you have the test database updated.

```sh
php app/console doctrine:database:drop --env=test --force
php app/console doctrine:database:create --env=test
php app/console doctrine:schema:create --env=test
```

To run the tests on your local machine, just use the phpunit command:

```sh
phpunit
```

[codeclimate-coverage-image]: https://codeclimate.com/github/seegno/SeegnoTestBundle/badges/coverage.svg
[codeclimate-gpa-image]: https://codeclimate.com/github/seegno/SeegnoTestBundle/badges/gpa.svg
[codeclimate-url]: https://codeclimate.com/github/seegno/SeegnoTestBundle
[license-image]: https://img.shields.io/packagist/l/seegno/test-bundle.svg
[packagist-image]: https://img.shields.io/packagist/v/seegno/test-bundle.svg
[packagist-url]: https://packagist.org/packages/seegno/test-bundle
[travis-image]: https://travis-ci.org/seegno/SeegnoTestBundle.svg
[travis-url]: https://travis-ci.org/seegno/SeegnoTestBundle
