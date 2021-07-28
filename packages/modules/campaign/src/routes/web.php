<?php



Route::group(['prefix' => 'campaign', 'middleware' => ['web', 'check.auth'], 'namespace' => 'Modules\Campaign\controllers'], function()
{
    Route::get('/list', 'CampaignController@index')->name('campaign')->middleware('check.permission');
    Route::get('/view-details/{id?}', 'CampaignController@show')->name('campaign.show')->middleware('check.permission');

    Route::get('/create', 'CampaignController@create')->name('campaign.create')->middleware('check.permission');
    Route::post('/store', 'CampaignController@store')->name('campaign.store')->middleware('check.permission:campaign.create');

    Route::get('/{campaign_id}/create-new-lead/{id}', 'CampaignController@createNewLead')->name('campaign.create_new_lead')->middleware('check.permission:campaign.create');
    Route::post('/store-new-lead', 'CampaignController@storeNewLead')->name('campaign.store_new_lead')->middleware('check.permission:campaign.create_new_lead');

    Route::get('/edit/{id?}', 'CampaignController@edit')->name('campaign.edit')->middleware('check.permission');

    Route::post('/update/{id}', 'CampaignController@update')->name('campaign.update')->middleware('check.permission:campaign.edit');
    Route::post('/update-lead-details/{campaign_id?}', 'CampaignController@updateLeadDetails')->name('campaign.update_lead_details')->middleware('check.permission');
    Route::post('/update-sub-allocations/{campaign_id?}', 'CampaignController@updateSubAllocations')->name('campaign.update_sub_allocations')->middleware('check.permission');
    Route::any('/attach-specification/{campaign_id?}', 'CampaignController@attachSpecification')->name('campaign.attach_specification')->middleware('check.permission');
    Route::any('/remove-specification/{specification_id?}', 'CampaignController@removeSpecification')->name('campaign.remove_specification')->middleware('check.permission');

    //Route::any('/destroy/{id}', 'CampaignController@destroy')->name('campaign.destroy')->middleware('check.permission');

    Route::any('/validateVMailCampaignId', 'CampaignController@validateVMailCampaignId')->name('campaign.validate.v_mail_campaign_id');
    Route::any('/getCampaigns', 'CampaignController@getCampaigns')->name('campaign.get_campaings');
    Route::any('/getLeadDetails/{campaign_id?}', 'CampaignController@getLeadDetails')->name('campaign.get_lead_details');
    Route::any('/getSubAllocations/{lead_id?}', 'CampaignController@getSubAllocations')->name('campaign.get_sub_allocations');
    Route::any('/getCampaignHistory/{campaign_id?}', 'CampaignController@getCampaignHistory')->name('campaign.get_campaign_history');


    Route::get('/export/{campaignId?}', 'CampaignController@export');

    Route::group(['prefix' => 'settings/campaign-type'], function()
    {
        Route::get('/list', 'CampaignTypeController@index')->name('campaign_type')->middleware('check.permission');
        Route::get('/create', 'CampaignTypeController@create')->name('campaign_type.create')->middleware('check.permission');
        Route::post('/store', 'CampaignTypeController@store')->name('campaign_type.store')->middleware('check.permission:campaign_type.create');
        Route::get('/edit/{id}', 'CampaignTypeController@edit')->name('campaign_type.edit')->middleware('check.permission');
        Route::post('/update/{id}', 'CampaignTypeController@update')->name('campaign_type.update')->middleware('check.permission:campaign_type.edit');
        Route::any('/destroy/{id}', 'CampaignTypeController@destroy')->name('campaign_type.destroy')->middleware('check.permission');

        Route::any('/validateName', 'CampaignTypeController@validateName')->name('campaign_type.validate_name');

    });

    Route::group(['prefix' => 'settings/campaign-filter'], function()
    {
        Route::get('/list', 'CampaignFilterController@index')->name('campaign_filter')->middleware('check.permission');
        Route::get('/create', 'CampaignFilterController@create')->name('campaign_filter.create')->middleware('check.permission');
        Route::post('/store', 'CampaignFilterController@store')->name('campaign_filter.store')->middleware('check.permission:campaign_filter.create');
        Route::get('/edit/{id}', 'CampaignFilterController@edit')->name('campaign_filter.edit')->middleware('check.permission');
        Route::post('/update/{id}', 'CampaignFilterController@update')->name('campaign_filter.update')->middleware('check.permission:campaign_filter.edit');
        Route::any('/destroy/{id}', 'CampaignFilterController@destroy')->name('campaign_filter.destroy')->middleware('check.permission');

        Route::any('/validateName', 'CampaignFilterController@validateName')->name('campaign_filter.validate_name');
    });

    //Dummy Routes
    Route::any('/view_sub_allocations', function (){ return redirect()->route('campaign');})->name('campaign.view_sub_allocations');

});




