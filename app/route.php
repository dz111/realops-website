<?php

Route::get('/', 'main/index', 'index');

Route::get('/schedule', 'schedule/list', 'schedule');
Route::get('/schedule/{flightid}', 'schedule/view');

Route::get('/pilots', 'main/pilots', 'pilots');
Route::get('/charts', 'main/charts', 'charts');

Route::get('/atc', 'atc/form', 'atc');
Route::post('/atc', 'atc/submit');

Route::get('/contact', 'contact/form', 'contact');
Route::post('/contact', 'contact/submit');

?>