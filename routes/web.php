<?php 

Route::get('/login/facebook_poster/authenticate_facebook_application', 'SocialMediaAuthController@authenticate_facebook_application');
Route::get('/login/linkedin_poster/authenticate_linkedin_application', 'SocialMediaAuthController@authenticate_linkedin_application');
Route::get('/login/facebook_poster/callback', 'SocialMediaAuthController@handleProviderCallback_facebook_poster');
Route::get('/login/linkedin-callback-poster', 'SocialMediaAuthController@handleProviderCallback_linkedin_poster');