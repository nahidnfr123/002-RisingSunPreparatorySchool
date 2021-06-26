<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('visitor.index');
})->name('index');*/



Route::get('/', 'HomeController@index')->name('index');
Route::get('/index', 'HomeController@index')->name('index');

Route::get('/post', 'HomeController@post')->name('post');
Route::get('/post-details/{title}/{id}', 'HomeController@postDetails')->name('post-details');
Route::get('/post-search', 'HomeController@postSearch')->name('post-search');

Route::get('/event', 'HomeController@event')->name('event');
Route::get('/event-details/{title}/{id}', 'HomeController@eventDetails')->name('event-details');
Route::get('/event-search', 'HomeController@eventSearch')->name('event-search');

Route::get('/gallery', 'HomeController@gallery')->name('gallery');
Route::get('/about', 'HomeController@about')->name('about');

Route::get('/contact', 'ContactUsController@index')->name('contact');
Route::post('/contact/send', 'ContactUsController@store')->name('contact.send');

View::composer(['*'], function($view) {
    $ContactDetails = \App\Models\ContactDetails::first();
    $view->with('ContactDetails', $ContactDetails);
});

//Auth::routes();
Auth::routes(['register' => false, 'validate' => true]);


//Admin area ....
//Route::prefix('admin')->group(function (){
Route::group(['as'=>'admin.', 'prefix'=>'admin'], function () {
    Route::namespace('Admin\Auth')->group(function () {
        Route::get('/login', 'AdminLoginController@showAdminLoginForm')->name('login');
        Route::post('/login', 'AdminLoginController@login')->name('login.submit');
        // Send password reset link ....
        Route::get('/password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request'); // Show Send Email Form ....
        Route::post('/password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email'); // Send Email ....
        // Reset the password ....
        Route::post('/password/reset', 'ResetPasswordController@resetPassword')->name('password.reset.submit');
        Route::get('/password/reset/{token}', 'ResetPasswordController@showResetPasswordForm')->name('password.reset');
        // Logout User ....
        Route::post('/logout', 'AdminLoginController@logout')->name('logout');

        Route::get('/verification/{id}/{token}', 'VerifyAccount@verification')->name('users.verification');
    });

    //Admin Dashboard Route should be at last ....
    //Route::namespace('Admin')->group(function (){
    Route::group(['namespace'=>'Admin', 'middleware' => ['auth:admin', 'verified', 'isAdmin', 'status']], function () {

        View::composer(['*'], function($view) {

            $MsgCount = count(\App\Models\ContactUs::where('seen', '=', 0)->get());
            $Msgsss = \App\Models\ContactUs::where('sender', 0)->latest()->take(3)->get();
            $view->with('MsgCount', $MsgCount)->with('Msgsss', $Msgsss);

        });

        // Dashboard ...
        Route::get('/', 'DashboardController@index')->name('dashboard');


        // Admin Profile page ....
        Route::get('/profile', 'ProfileController@index')->name('profile');
        Route::post('/profile/update_avatar', 'ProfileController@updateAvatar')->name('profile.update_avatar');
        Route::post('/profile/update_Password', 'ProfileController@updatePassword')->name('profile.update_Password');
        Route::post('/profile/update/super', 'ProfileController@updateSuper')->name('profile.update.super');
        Route::post('/profile/update/admin', 'ProfileController@updateAdmin')->name('profile.update.admin');
        Route::post('/profile/chkPassword', 'ProfileController@chkPassword')->name('profile.chkPassword');
        Route::get('/profile/test', 'ProfileController@test')->name('profile.test');
        Route::post('/profile/delete', 'ProfileController@delete')->name('profile.delete');


        // Manage users ....
        Route::get('/users', 'ManageUsersController@index')->name('users');
        Route::get('/users/details/{id}', 'ManageUsersController@show')->name('users.details');
        Route::post('/users/add', 'ManageUsersController@store')->name('users.add');
        Route::get('/users/edit/{id}', 'ManageUsersController@edit')->name('users.edit');
        Route::post('/users/update', 'ManageUsersController@update')->name('users.update');
        Route::get('/users/delete/{id}', 'ManageUsersController@delete')->name('users.delete');
        Route::get('/users/block/{id}', 'ManageUsersController@block')->name('users.block');
        Route::get('/users/unblock/{id}', 'ManageUsersController@unblock')->name('users.unblock');
        Route::get('/users/trash', 'ManageUsersController@trash')->name('users.trash');
        Route::get('/users/restore/{id}', 'ManageUsersController@restore')->name('users.restore');
        Route::post('/users/search', 'ManageUsersController@search')->name('users.search');


        // Post ... Done ...
        Route::get('/post', 'ManagePostController@index')->name('post');
        Route::get('/post/all', 'ManagePostController@postAll')->name('post.all');
        Route::post('/post/add', 'ManagePostController@store')->name('post.add');
        Route::get('/post/edit/{id}', 'ManagePostController@edit')->name('post.edit');
        Route::post('/post/update/{id}', 'ManagePostController@update')->name('post.update');
        Route::get('/post/delete/{id}', 'ManagePostController@delete')->name('post.delete');
        Route::get('/post/restore/{id}', 'ManagePostController@restore')->name('post.restore');
        Route::get('/post/destroy/{id}', 'ManagePostController@destroy')->name('post.destroy');
        Route::post('/post/images/show', 'ManagePostController@showImages')->name('post.images.show'); // Ajax ....
        Route::get('/post/image/download/{id}', 'ManagePostController@downloadImage')->name('post.image.download');
        Route::get('/post/image/delete/{id}', 'ManagePostController@deleteImage')->name('post.image.delete');
        Route::get('/post/trash', 'ManagePostController@postTrash')->name('post.trash');
        Route::post('/post/trashed/images/show', 'ManagePostController@showTrashedImages')->name('post.trashed.images.show'); // Ajax ....
        Route::get('/post/{title}/{id}', 'ManagePostController@show')->name('post.read-more');
        Route::post('/post/search', 'ManagePostController@postSearch')->name('post.search');
        // Post End ...


        // Event ... Done ...
        Route::get('/events', 'ManageEventsController@index')->name('events');
        //Route::get('/events/all', 'ManageEventsController@eventAll')->name('events.all');
        Route::post('/events/add', 'ManageEventsController@store')->name('events.add');
        Route::get('/events/edit/{id}', 'ManageEventsController@edit')->name('events.edit');
        Route::post('/events/update/{id}', 'ManageEventsController@update')->name('events.update');
        Route::get('/events/delete/{id}', 'ManageEventsController@delete')->name('events.delete');
        Route::get('/events/restore/{id}', 'ManageEventsController@restore')->name('events.restore');
        Route::get('/events/destroy/{id}', 'ManageEventsController@destroy')->name('events.destroy');
        Route::post('/events/images/show', 'ManageEventsController@showImages')->name('events.images.show'); // Ajax ....
        Route::get('/events/image/download/{id}', 'ManageEventsController@downloadImage')->name('events.image.download');
        Route::get('/events/image/delete/{id}', 'ManageEventsController@deleteImage')->name('events.image.delete');
        Route::get('/events/trash', 'ManageEventsController@eventTrash')->name('events.trash');
        Route::post('/events/trashed/images/show', 'ManageEventsController@showTrashedImages')->name('events.trashed.images.show'); // Ajax ....
        Route::get('/events/{title}/{id}', 'ManageEventsController@show')->name('events.read-more');
        Route::post('/events/search', 'ManageEventsController@eventSearch')->name('events.search');
        // Event End ...


        // Gallery ... Done ...
        Route::get('/gallery', 'GalleryController@index')->name('gallery');
        Route::post('/gallery/image/upload', 'GalleryController@store')->name('gallery.image.upload');
        Route::get('/gallery/delete/{id}', 'GalleryController@destroy')->name('gallery.destroy');
        Route::post('/gallery/image/add/{id}', 'GalleryController@addImageToGallery')->name('gallery.image.add');
        Route::post('/gallery/image/edit', 'GalleryController@edit')->name('gallery.image.edit');
        Route::post('/gallery/image/update/{id}', 'GalleryController@update')->name('gallery.image.update');
        Route::post('/gallery/search', 'GalleryController@search')->name('gallery.search');
        // Gallery End ...


        // Contact Us ...
        Route::get('/contact-us/inbox', 'ContactUsController@inbox')->name('contact-us.inbox');
        Route::get('/contact-us/sent', 'ContactUsController@sentMsg')->name('contact-us.sent');
        Route::get('/contact-us/read/{id}', 'ContactUsController@show')->name('contact-us.read');
        Route::get('/contact-us/msg/delete/{id}', 'ContactUsController@delete')->name('contact-us.msg.delete');
        Route::get('/contact-us/msg/delete-multiple', 'ContactUsController@multipleDelete')->name('contact-us.msg.delete-multiple');
        Route::get('/contact-us/trash', 'ContactUsController@trash')->name('contact-us.trash');
        Route::get('/contact-us/msg/destroy/{id}', 'ContactUsController@destroy')->name('contact-us.msg.destroy');
        Route::get('/contact-us/msg/destroy-multiple', 'ContactUsController@multipleDestroy')->name('contact-us.msg.destroy-multiple');
        Route::get('/contact-us/restore', 'ContactUsController@restore')->name('contact-us.restore');
        Route::get('/contact-us/compose', 'ContactUsController@compose')->name('contact-us.compose');
        Route::get('/contact-us/msg/reply/{id}', 'ContactUsController@reply')->name('contact-us.msg.reply');
        Route::post('/contact-us/msg/replyMail', 'ContactUsController@sendMail')->name('contact-us.msg.replyMail');
        Route::post('/contact-us/msg/sendMail', 'ContactUsController@sendMail')->name('contact-us.msg.sendMail');
        Route::get('/contact-us/details', 'ContactUsController@details')->name('contact-us.details');
        Route::post('/contact-us/details-add', 'ContactUsController@details_add')->name('contact-us.details-add');
        Route::post('/contact-us/details-update', 'ContactUsController@details_update')->name('contact-us.details-update');


        // About
        Route::get('/about', 'ManagePagesController@about')->name('about');
        Route::post('/about/add', 'ManagePagesController@about_add')->name('about.add');
        Route::post('/about/update', 'ManagePagesController@about_update')->name('about.update');

        // Pages settings ...
        Route::post('/pages/image/add', 'ManagePagesController@pages_image_add')->name('pages.image.add');
        Route::post('/pages/image/update', 'ManagePagesController@pages_image_update')->name('pages.image.update');

        Route::resource('/home_settings','ManagePagesController');
        Route::get('/home/settings', 'ManagePagesController@home_settings')->name('home.settings');
        Route::get('/home/settings/show', 'ManagePagesController@home_settings_show')->name('home.settings.show');
        Route::post('/home/settings/status/edit', 'ManagePagesController@home_settings_status_edit')->name('home.settings.status.edit');
        Route::post('/home/settings/status/update', 'ManagePagesController@home_settings_status_update')->name('home.settings.status.update');
        Route::post('/home/settings/status/delete', 'ManagePagesController@home_settings_status_delete')->name('home.settings.status.delete');

    });
});



// Admin area ....
Route::group(['as'=>'admin.', 'prefix'=>'admin', 'namespace'=>'Admin', 'middleware' => ['verified', 'admin', 'blocked_usr']], function () {

});
