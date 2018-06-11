<?php

Route::get('products/slug/{slug}', 'ProductController@slug');
Route::resource('products', 'ProductController')->only('index', 'show', 'store', 'update');
