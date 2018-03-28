<?php

use App\Device;

Route::get('/', function () {
    return response()->json(['The api says hi']);
});

Route::get('/ticker', 'DevicesController@ticker');
Route::get('/devices', 'DevicesController@devices');
Route::get('/device/types', 'DeviceTypesController@index');
Route::get('/device/assigner/data', 'AssignerController@index');
Route::put('/device/assign', 'AssignerController@store');
Route::delete('/assign/{uuid}', 'AssignerController@destroy');
Route::delete('/device/{uuid}', 'DevicesController@destroy');
