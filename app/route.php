<?php

Route::get('/', 'main/index');

Route::get('/schedule', 'schedule/list');
Route::get('/schedule/{flightid}', 'schedule/view');

Route::get('/pilots', 'main/pilots');
Route::get('/charts', 'main/charts');

Route::get('/atc', 'atc/form');
Route::post('/atc', 'atc/submit');

Route::get('/contact', 'contact/form');
Route::post('/contact', 'contact/submit');

?>