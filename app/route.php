<?php

Route::get('/', 'main/index', 'index');

Route::get('/auth', 'auth/login', 'login');
Route::get('/auth/logout', 'auth/logout', 'logout');
Route::get('/auth/callback', 'auth/callback', 'auth/callback');

Route::get('/schedule', 'schedule/list', 'schedule');
Route::get('/schedule/my', 'schedule/user', 'schedule/user');
Route::get('/schedule/{flightid}', 'schedule/view');

Route::get('/pilots', 'main/pilots', 'pilots');
Route::get('/charts', 'main/charts', 'charts');

Route::get('/atc', 'atc/form', 'atc');
Route::post('/atc', 'atc/submit');

Route::get('/contact', 'contact/form', 'contact');
Route::post('/contact', 'contact/submit');
