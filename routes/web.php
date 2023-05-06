<?php

use App\Http\Controllers\Admin\AdminHomeController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*
 * HOME PAGES ROUTES
 */
Route::get('/', function () {
    return view('client_manikur.home');
})->name('main.home');

/*
* ADMIN PAGES ROUTES
*/
Route::prefix('admin')->name('admin.')
->middleware(['auth', 'verified'])
->group(function () {
    // Admins home route same for admin, moder, user
    Route::get('/', [AdminHomeController::class, 'getview'])->name('home');

    // ADMINS routes
    Route::middleware('isadmin')->group(function () {
        Route::controller(UsersController::class)
            ->prefix('user')
            ->name('user.')
            ->group(function () {
                Route::get('/add', 'add')->name('add');
                Route::get('/remove', 'list')->name('remove');
                Route::post('/remove', 'remove')->name('post_remove');
                Route::get('/change', 'list')->name('change');
                Route::post('/change', 'show')->name('show');
                Route::post('/change/store', 'store')->name('store');
            });
        /*
        Route::controller(PageController::class)
            ->prefix('page')
            ->name('page.')
            ->group(function () {
                Route::get('/add', 'add')->name('add');
                Route::get('/remove', 'list')->name('remove');
                Route::post('/remove', 'remove')->name('post_remove');
            });
        */
        Route::get('/logs', function () {
            return view('admin_manikur.adm_pages.logs');
        })->name('logs');
    });
    /*
    * ADMIN AND MODER ROUTES
    */
    Route::middleware('ismoder')->group(function () {
        Route::controller(ContactsController::class)
        ->prefix('contacts')
        ->name('contacts.')
        ->group(function () {
            Route::get('/', 'index')->name('list');
            Route::get('/add', 'add')->name('add');
            Route::get('/remove', 'list')->name('remove');
            Route::post('/remove', 'remove')->name('post_remove');
            Route::get('/change', 'list')->name('change');
            Route::post('/change', 'show')->name('show');
            Route::post('/change/store', 'store')->name('store');
        });
        Route::get('/page_edit', function () {
            return view('admin_manikur.admin_moder_pages.page_edit');
        })->name('page_edit');
    });
    /*
    * ADMIN AND MODER AND USER ROUTES
    */
    /*
            Route::prefix('user')->name('user.')->group(function () {
                Route::get('/recall_list', function () {
                    return view('admin_manikur.admin_moder_user_pages.recall_list');
                })->name('recall_list');
            });

    */
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('register', [RegisteredUserController::class, 'create'])
->middleware(['auth', 'verified'])
->name('register');

Route::post('register', [RegisteredUserController::class, 'store'])
->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.DIRECTORY_SEPARATOR.'auth.php';
require __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'functions'.DIRECTORY_SEPARATOR.'func.php';
