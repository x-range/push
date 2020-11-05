<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::post('/subscription/', 'SubscriptionController@store')->name('subscription');

Route::group(['prefix' => 'statistic', 'as' => 'statistic.'], function (){
    Route::post('notification', 'StatisticController@notification')->name('notification');
    Route::post('click', 'StatisticController@click')->name('click');
    Route::post('close', 'StatisticController@close')->name('close');
});

Route::group(['prefix' => 'queue', 'as' => 'queue.'], function(){
    Route::post('/', 'QueueController@store')->name('store');
});

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function(){
    Route::group(['prefix' => 'stat', 'as' => 'stat.'], function(){
        Route::group(['prefix' => 'site', 'as'=>'site.'], function (){
            Route::get('/', 'Admin\Stat\SiteController@index');
            Route::get('/{site}', 'Admin\Stat\SiteController@show');
        });
        Route::group(['prefix' => 'message', 'as'=>'message.'], function (){
            Route::get('/', 'Admin\Stat\MessageController@index');
            Route::get('/{message}', 'Admin\Stat\MessageController@show');
        });
    });
});
