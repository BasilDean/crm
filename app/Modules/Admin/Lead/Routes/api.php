<?php

Route::group(['prefix' => 'leads', 'middleware' => []], function () {
    Route::get('/', 'Api\LeadController@index')->name('api.leads.index');
    Route::post('/', 'Api\LeadController@store')->name('api.leads.store');
    Route::get('/{lead}', 'Api\LeadController@show')->name('api.leads.read');
    Route::put('/{lead}', 'Api\LeadController@update')->name('api.leads.update');
    Route::delete('/{lead}', 'Api\LeadController@destroy')->name('api.leads.delete');

    Route::get('archive/index', 'Api\LeadController@archive')->name('api.archive.index');
    Route::post('create/check', 'Api\LeadController@checkIfExist')->name('api.leads.check');
    Route::put('update/quality/{lead}', 'Api\LeadController@setAsQualityLead')->name('api.leads.updateQuality');
});
