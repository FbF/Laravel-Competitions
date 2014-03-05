<?php

// Main default listing e.g. http://domain.com/competitions
Route::get(Config::get('laravel-competitions::routes.base_uri'), 'Fbf\LaravelCompetitions\CompetitionsController@index');

// Competition detail page e.g. http://domain.com/competitions/my-competition
Route::get(Config::get('laravel-competitions::routes.base_uri').'/{slug}', 'Fbf\LaravelCompetitions\CompetitionsController@view');

// The enter action on the competition detail page
Route::post(Config::get('laravel-competitions::routes.base_uri').'/{slug}', 'Fbf\LaravelCompetitions\CompetitionsController@enter');