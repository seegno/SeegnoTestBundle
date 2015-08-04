# SeegnoTestBundle
[![Latest Version](https://img.shields.io/packagist/v/seegno/test-bundle.svg)](https://packagist.org/packages/seegno/test-bundle)
[![Build Status](https://travis-ci.org/seegno/test-bundle.svg?branch=master)](https://travis-ci.org/seegno/test-bundle)
[![License](https://img.shields.io/packagist/l/seegno/test-bundle.svg)](https://packagist.org/packages/seegno/test-bundle)

## Introduction

This Bundle provides base classes for unit and integration tests in order to assist in setting test databases and data fixtures.

## Installation

#### 1. Download SeegnoTestBundle using composer

Add SeegnoTestBundle by running the command:

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

#### Integration tests

```php
use Seegno\TestBundle\TestCase\IntegrationTestCase;

class SomeClassTest extends IntegrationTestCase
{
    //...
}
```

#### Web tests

```php
use Seegno\TestBundle\TestCase\WebTestCase;

class SomeClassTest extends WebTestCase
{
    //...
}
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
