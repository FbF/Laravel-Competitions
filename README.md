Laravel Competitions
====================

A Laravel 4 package for adding multiple, content managed, multiple-choice competitions to a website.

## Features

* Paginated index view with configurable results per page, ordered by sticky bit on the competition and closing date (desc)
* Competitions have title, main image, summary, content, question, multiple choice answers
* Draft/Approved status and published date fields for approvals and scheduled publishing of competitions
* Set a closing date for them to automatically close for entries
* Currently, entries are tied to a logged in user, so require a logged in user session in order to enter

## Includes

* Bundled migration for building the database schema for the competition and entries table
* Faker seed to seed your site with loads of good test data
* Administrator config file for use with FrozenNode's Administrator package, which includes functions to download the entries as CSV
* Controller, models and views that are easily extensible for use in your own app

## Installation

Add the following to you composer.json file (Recommend swapping "dev-master" for the latest release)

    "fbf/laravel-competitions": "dev-master"

Run

    composer update

Add the following to app/config/app.php

    'Fbf\LaravelCompetitions\LaravelCompetitionsServiceProvider'

Run the package migration

    php artisan migrate --package=fbf/laravel-competitions

Publish the config

    php artisan config:publish fbf/laravel-competitions

Optionally tweak the settings in the many config files for your app

Optionally copy the administrator config file (`src/config/administrator/competitions.php`) to your administrator model config directory.

Create the relevant image upload directories that you specify in your config, e.g.

    public/uploads/packages/fbf/laravel-competitions/main_image/original
    public/uploads/packages/fbf/laravel-competitions/main_image/thumbnail
    public/uploads/packages/fbf/laravel-competitions/main_image/resized

## Faker seed

The package comes with a seed that can populate the table with a whole bunch of sample posts. There are some configuration options for the seed in the config file. To run it:

    php artisan db:seed --class="Fbf\LaravelCompetitions\FakeCompetitionsSeeder"

## Configuration

See the many configuration options in the files in the config directory

## Administrator

You can use the excellent Laravel Administrator package by FrozenNode to administer your competitions.

http://administrator.frozennode.com/docs/installation

A ready-to-use model config file for the Post model (competitions.php) is provided in the src/config/administrator directory of the package, which you can copy into the app/config/administrator directory (or whatever you set as the model_config_path in the administrator config file).

## Usage

The package should work out the box (provided you have a master blade layout file, since the out-of-the-box views extend this)
 but if you want to add other content to the pages, such as your own header, logo, navigation, sidebar etc, you'll want to
 override the views provided.

The package views declare several sections that you may want to `yield` in your `app/views/layouts/master.blade.php` file, e.g.:

```html
<!DOCTYPE html>
<html>
<head>
	<title>@yield('title')</title>
	<meta name="description" content="@yield('meta_description')">
	<meta name="keywords" content="@yield('meta_keywords')">
</head>
<body>
<div class="content">
	@yield('content')
</div>
</body>
</html>
```

The package's views are actually really simple, and most of the presentation is done in partials. This is deliberate so you
 can override the package's views in your own app, so you can include your own chrome, navigation and sidebars etc, yet
 you can also still make use of the partials provided, if you want to.

To override any view in your own app, just create the following directories and copy the file from the package into it, then hack away
* `app/views/packages/fbf/laravel-competitions/competitions`
* `app/views/packages/fbf/laravel-competitions/partials`

## Extending the package

This can be done for the purposes of say, relating the Competition model to a Category model and allowing filtering by category,
 or relating the Competition model to a User model to add and Author to a Competition, or simply just for overriding the functionality
 in the bundled Competition model.

### Basic approach

(See the example below for more specific information.)

To override the `Competition` model in the package, create a model in you app/models directory that extends the package model.

Finally, update the IoC Container to inject an instance of your model into the `Fbf\LaravelCompetitions\CompetitionsController`,
instead of the package's model, e.g. in `app/start/global.php`

```php
App::bind('Fbf\LaravelCompetitions\CompetitionsController', function() {
    return new Fbf\LaravelCompetitions\CompetitionsController(new Competition);
});
```