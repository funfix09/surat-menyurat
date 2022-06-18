<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DivisonController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\OutgoingMailController;
use App\Http\Controllers\IncomingMailsController;

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

Route::get('/login', function () {
    return (Auth::check()) ? redirect('/') : view('auth.login');
})->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');

Route::middleware(['auth'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/account', [HomeController::class, 'account'])->name('account');
    Route::post('/account', [HomeController::class, 'accountUpdate'])->name('account.update');

    Route::group(['middleware' => ['role:superadmin']], function() {
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
        Route::resource('divisions', DivisonController::class);
        Route::resource('users', UserController::class);
    });

    Route::resource('surat-masuk', IncomingMailsController::class);
    Route::patch('surat-keluar/verifikasi/{id}', [OutgoingMailController::class, 'verification'])->name('surat-keluar.verifikasi');
    Route::resource('surat-keluar', OutgoingMailController::class);

    Route::get('download/{file}', function($file) {
        $file = public_path(). "/uploads/attachment_files/" . $file;

        if (file_exists($file)) {
            return response()->download($file);;
        } else {
            abort(404);
        }
    })->name('download-file');
});