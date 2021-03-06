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

Route::get('/', [
    'as'=>'/',
    'uses'=>'HomeController@index'
]);

Route::get('/category/{ctname}',[
    'as'=>'/category/{ctname}',
    'uses'=>'CategoryController@index'
]);
Route::get('/b/{id}/{url}',[
    'as'=>'/b/{id}/{url}',
    'uses'=>'ProductController@index'
]);

Route::group(['prefix'=>'signup'],function (){
    Route::get('get-captcha','Auth\RegisterController@generateCaptcha');
    Route::post('/','Auth\RegisterController@register');
});

Route::post("login",[
    'as'=>'login',
    'uses'=>"Auth\LoginController@login"
]);

Route::post("logout",[
    'as'=>'logout',
    'uses'=>"Auth\LoginController@logout"
]);

Route::get("/registration/{token}/{tokendate}",[
    'as'=>'/registration/{token}/{tokendate}',
    'uses'=>"Auth\RegisterController@confirmSignup"
]);

Route::get('/bn/{id}/{url}',[
    'as'=>'/bn/{id}/{url}',
    'uses'=>'BlogNewsController@index'
]);

Route::get('/blognews',[
    'as'=>'blognews',
    'uses'=>'BlogNewsController@indexblog'
]);

Route::post('/blognews',[
    'as'=>'blognews',
    'uses'=>'BlogNewsController@indexPostBlog'
]);

//Route::get('contact',[
//    'as'=>'contact',
//    'uses'=>'ContactController@index'
//]);

Route::group(['prefix'=>'contact'],function (){
    Route::get("/",[
        'as'=>'contact',
        'uses'=>'ContactController@index'
    ]);

    Route::post("/send",[
        "as"=>'contact.send',
        'uses'=>'ContactController@sendEmailFromUser'
    ]);
});

Route::get('/search-ajax',[
    'as'=>'search-ajax',
    'uses'=>'SearchController@searchAjax'
]);

Route::get('search',[
    'as'=>'search',
    'uses'=>'SearchController@index'
]);

Route::group(['prefix'=>'checkout','middleware'=>'not-reader'],function (){
    Route::get("addcart",[
        "as"=>"checkout.addcart",
        "uses"=>"CheckoutController@addCart"
    ]);

    Route::get("getcontent",[
        'as'=>"checkout.getcontent",
        'uses'=>'CheckoutController@viewCartContent'
    ]);

    Route::get("deletecart",[
        "as"=>"checkout.deletecart",
        "uses"=>"CheckoutController@deleteCart"
    ]);

    Route::get("acceptcart",[
        "as"=>"checkout.acceptcart",
        "uses"=>"CheckoutController@acceptCartToDB"
    ]);

    Route::get("cart",[
        "as"=>"checkout.cart",
        "uses"=>"CheckoutController@index"
    ]);
});

Route::group(['prefix' => 'adminer/bait', 'middleware' => 'admin'], function() {
    // your CRUD resources and other admin routes here
    Route::get("/dashboard","Admin\DashboardController@loadViewAction");
    Route::post("/borrow_detail/update","Admin\Borrow_detailCrudController@updateByAjax");

    CRUD::resource('book_type','Admin\Book_typeCrudController');
    CRUD::resource('book','Admin\BookCrudController');
    CRUD::resource('reader','Admin\ReaderCrudController');
    CRUD::resource('blog_news','Admin\Blog_newsCrudController');
    CRUD::resource('borrow_detail','Admin\Borrow_detailCrudController');
    CRUD::resource('comment_blog','Admin\Comment_blogCrudController');
    CRUD::resource('comment_book','Admin\Comment_bookCrudController');
    CRUD::resource('repcomment_blog','Admin\Repcomment_blogCrudController');
    CRUD::resource('repcomment_book','Admin\Repcomment_bookCrudController');
    CRUD::resource('user-read','Admin\UserCrudController');


    Route::group(['prefix'=>'export-excel'],function (){
        Route::get('book_types','Admin\Book_typeCrudController@ExportExcelAction');
        Route::get('books','Admin\BookCrudController@ExportExcelAction');
        Route::get('readers','Admin\ReaderCrudController@ExportExcelAction');
        Route::get('borrow_details','Admin\Borrow_detailCrudController@ExportExcelAction');
        Route::get('user-reads','Admin\UserCrudController@ExportExcelAction');
        Route::get('blog_news','Admin\Blog_newsCrudController@ExportExcelAction');
    });

    Route::group(['prefix'=>'import-excel'],function (){
        Route::post('book_types','Admin\Book_typeCrudController@ImportExcelAction');
        Route::post('books','Admin\BookCrudController@ImportExcelAction');
        Route::post('readers','Admin\ReaderCrudController@ImportExcelAction');
        Route::post('blog_news','Admin\Blog_newsCrudController@ImportExcelAction');
    });
});