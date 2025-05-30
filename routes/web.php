<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('book/{id}', [HomeController::class, 'detail'])->name('book.detail');

Route::group(['prefix' => 'account'], function(){
    Route::group(['middleware' => 'guest'], function() {
        Route::get('register', [AccountController::class, 'register'])->name('account.register');
        Route::post('register', [AccountController::class, 'processRegister'])->name('account.processRegister.post');
        Route::get('login', [AccountController::class, 'login'])->name('account.login');
        Route::post('login', [AccountController::class, 'processLogin'])->name('account.processLogin.post');
    });
    Route::group(['middleware' => 'auth'], function() {
            Route::get('profile', [AccountController::class, 'profile'])->name('account.profile');
            Route::get('logout', [AccountController::class, 'logout'])->name('account.logout');
            Route::post('updateProfile', [AccountController::class, 'updateProfile'])->name('account.updateProfile.post');

        Route::group(['middleware' => 'check-admin'], function() {
            Route::get('books', [BookController::class, 'index'])->name('books.index');
            Route::get('books/create', [BookController::class, 'create'])->name('books.create');
            Route::post('books', [BookController::class, 'store'])->name('books.store.post');
            Route::get('books/edit/{id}', [BookController::class, 'edit'])->name('books.edit');
            Route::post('books/edit/{id}', [BookController::class, 'update'])->name('books.update.post');
            Route::delete('books/delete/{id}', [BookController::class, 'destroy'])->name('books.destroy');
            Route::get('reviews', [ReviewController::class, 'index'])->name('account.reviews');
            Route::get('reviews/{id}', [ReviewController::class, 'edit'])->name('account.reviews.edit');
            Route::post('reviews/edit/{id}', [ReviewController::class, 'update'])->name('account.reviews.update');
            Route::delete('delete-review', [ReviewController::class, 'deleteReview'])->name('account.reviews.deleteReview');
        });

        Route::post('books/review', [ReviewController::class, 'store'])->name('books.review.post');
        Route::get('my-reviews/list', [AccountController::class, 'myReviews'])->name('account.myReviews');
        Route::get('my-reviews/edit/{id}', [AccountController::class, 'edit'])->name('account.myReviews.edit');
        Route::post('my-reviews/edit/{id}', [AccountController::class, 'updateMyReview'])->name('account.myReviews.update');
        Route::delete('delete-review', [ReviewController::class, 'deleteReview'])->name('account.reviews.deleteReview');
    });
});