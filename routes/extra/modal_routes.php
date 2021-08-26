<?php


Route::any('/get-campaign-import-modal', 'Extra\ModalController@getModal')->name('modal.campaign.import');
Route::any('/campaign-user-assign-modal', 'Extra\ModalController@getModal')->name('modal.campaign_user_assign');
