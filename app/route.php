<?php

Route::get('/', 'main/index', 'index');

Route::get('/auth', 'auth/login', 'login', true);
Route::get('/auth/logout', 'auth/logout', 'logout', true);
Route::get('/auth/callback', 'auth/callback', 'auth/callback', true);

Route::get('/schedule', 'schedule/list', 'schedule');
Route::get('/schedule/my', 'schedule/user', 'schedule/user');
Route::get('/schedule/{flightid}', 'schedule/view', 'schedule/view');
Route::post('/schedule/{flightid}', 'schedule/book');
Route::delete('/schedule/{flightid}', 'schedule/unbook');

Route::get('/pilots', 'main/pilots', 'pilots');
Route::get('/charts', 'main/charts', 'charts');

Route::get('/atc', 'atc/form', 'atc');
Route::post('/atc', 'atc/submit');

Route::get('/contact', 'contact/form', 'contact');
Route::post('/contact', 'contact/submit');

Route::get(ADMIN_PATH, 'admin/index', 'admin', true);
Route::get(ADMIN_PATH . '/insertmass', 'admin/insertmass', 'admin/insertmass', true);
Route::post(ADMIN_PATH . '/insertmass', 'admin/doinsertmass', '', true);
Route::get(ADMIN_PATH . '/atc', 'admin/atc', 'admin/atc', true);
