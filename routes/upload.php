<?php
Route::group(['prefix' => 'upload', 'as' => 'upload_'], function (){

    Route::any('ajax', 'UploadController@ajax')->name('ajax');
});