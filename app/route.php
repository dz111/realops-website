<?php

Route::get('/', 'main/index');
Route::get('/schedule', 'schedule/list');
Route::get('/schedule/{flightid}', 'schedule/view');

?>