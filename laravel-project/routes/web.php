<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TwoFactorAuthController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/two_factor_auth/login_form', [TwoFactorAuthController::class, 'login_form'])->middleware(['guest']);
Route::post('/ajax/two_factor_auth/first_auth', [TwoFactorAuthController::class, 'first_auth']);
Route::post('/ajax/two_factor_auth/second_auth', [TwoFactorAuthController::class, 'second_auth']);

require __DIR__ . '/auth.php';
