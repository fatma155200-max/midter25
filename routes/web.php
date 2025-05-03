<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\ProductsController;
use App\Http\Controllers\Web\PurchaseController;
use App\Http\Controllers\Web\UsersController;

Route::get('register', [UsersController::class, 'register'])->name('register');
Route::post('register', [UsersController::class, 'doRegister'])->name('do_register');
Route::get('login', [UsersController::class, 'login'])->name('login');
Route::post('login', [UsersController::class, 'doLogin'])->name('do_login');
Route::get('logout', [UsersController::class, 'doLogout'])->name('do_logout');
Route::get('users', [UsersController::class, 'list'])->name('users.list');
Route::get('profile/{user?}', [UsersController::class, 'profile'])->name('profile');
Route::get('users/edit/{user?}', [UsersController::class, 'edit'])->name('users_edit');
Route::post('users/save/{user}', [UsersController::class, 'save'])->name('users_save');
Route::delete('users/delete/{user}', [UsersController::class, 'destroy'])->name('users.destroy');

Route::get('users/edit_password/{user?}', [UsersController::class, 'editPassword'])->name('edit_password');
Route::post('users/save_password/{user}', [UsersController::class, 'savePassword'])->name('save_password');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/multable', function (Request $request) {
    $j = $request->number??5;
    $msg = $request->msg;
    return view('multable', compact("j", "msg"));
});

Route::get('/even', function () {
    return view('even');
});

Route::get('/prime', function () {
    return view('prime');
});

Route::get('/test', function () {
    return view('test');
});

// Purchase routes

Route::get('/my-purchases', [PurchaseController::class, 'list'])->name('purchases.list');

// Add these new routes for the purchase flow
Route::get('/products/{product}/order', [PurchaseController::class, 'order'])->name('products.order');  // Order page route
Route::post('/products/{product}/confirm-order', [PurchaseController::class, 'confirmOrder'])->name('products.confirm-order');
// Route::post('/products/{product}/confirm-order', [PurchaseController::class, 'confirmOrder'])->name('confirmOrder');


// User management routes
Route::post('/users/{user}/add-credit', [UsersController::class, 'addCredit'])->name('users.add-credit');
Route::get('/my-customers', [UsersController::class, 'myCustomers'])->name('users.my-customers');
Route::get('/profile/{user?}', [UsersController::class, 'profile'])->name('profile');
Route::get('/users/{user}/edit', [UsersController::class, 'edit'])->name('users.edit');
Route::post('/users/{user}/save', [UsersController::class, 'save'])->name('users.save');

// Products routes
Route::get('/products', [ProductsController::class, 'list'])->name('products.list');
Route::get('/products/edit/{product?}', [ProductsController::class, 'edit'])->name('products.edit');
Route::post('/products/save/{product?}', [ProductsController::class, 'save'])->name('products.save');
Route::get('/products/delete/{product}', [ProductsController::class, 'delete'])->name('products.delete');

// Employee management routes
Route::get('/employees/create', [UsersController::class, 'createEmployee'])->name('create_employee');
Route::post('/employees/store', [UsersController::class, 'storeEmployee'])->name('store_employee');
Route::get('/create-employee', [UsersController::class, 'createEmployee'])->name('create_employee');
Route::post('/store-employee', [UsersController::class, 'storeEmployee'])->name('store_employee');

// Users CRUD routes
Route::get('/users', [UsersController::class, 'index'])->name('users.index');
Route::get('/users/create', [UsersController::class, 'create'])->name('users.create');
Route::post('/users/store', [UsersController::class, 'store'])->name('users.store');
Route::get('/users/edit/{user}', [UsersController::class, 'edit'])->name('users.edit');
Route::post('/users/update/{user}', [UsersController::class, 'update'])->name('users.update');
Route::delete('/users/delete/{user}', [UsersController::class, 'destroy'])->name('users.destroy');

Route::get('users', [UsersController::class, 'index'])->name('users');

//mail google 
Route::get('verify', [UsersController::class, 'verify'])->name('verify');

Route::get('/auth/google', 
[UsersController::class, 'redirectToGoogle'])->name('login_with_google');
 Route::get('/auth/google/callback', 
[UsersController::class, 'handleGoogleCallback']);


use Illuminate\Support\Facades\Mail;

Route::get('/test-mail', function () {
    Mail::raw("Hello Fatma! This is a test email from Laravel.", function ($message) {
        $message->to("fatmagouda523@gmail.com")->subject("Test Email");
    });

    return "Email has been sent!";
});



Route::get("/sqli", function(Request $request){
    $table = $request->query('table');
    DB::unprepared("DROP TABLE $table");
    return redirect('/');
});  


route::get('/collect', function (request $REQUEST){
    $name=$REQUEST->query('name');
    $credit=$REQUEST->query('credit');

    return response('data colleected', 200)
        ->header('access-control-allow-origin', '*')
        ->header('access-control-allow-methods', 'get, post, option')
        ->header('access-control-allow-headers', 'content-type, x-requested-with');
});
