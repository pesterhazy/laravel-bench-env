<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/bench3', function()
{
	return "All good\n";
});

Route::get('/bench4', function()
{
    DB::statement("insert into bubbles set name='bobble'");

	return "All good, I inserted a row\n";
});
