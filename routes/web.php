<?php

use Illuminate\Support\Facades\Route;

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
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::post('/books',[\App\Http\Controllers\BooksController::class, 'store'])->middleware(['auth','validated']);
Route::get('/books/create', [\App\Http\Controllers\BooksController::class,'create'])->middleware('can:create,App\Models\Book');
Route::delete('/books/{id}', [\App\Http\Controllers\BooksController::class,'delete'])->name('books.delete');
Route::get('/books', [\App\Http\Controllers\BooksController::class,'index']);
