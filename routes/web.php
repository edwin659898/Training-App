<?php

use App\Http\Controllers\TrainingRequestController;
use App\Http\Controllers\PDFController;
use App\Models\TrainingRequest;
use App\Models\User;
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
    return redirect('admin/login');
});

Route::get('/test', function () {
    return TrainingRequest::find(6)->trainings;
});

Route::get('/update/numbers', function () {
    $users = User::all();
    foreach ($users as $user) {
        if ($user->phone_number[0] != '+') {
            $user->update(['phone_number' => '+' . $user->phone_number]);
        }
    }
    return 'success';
});

Route::get(
    '/submit/training-request/{record}/for-review/',
    [TrainingRequestController::class, 'submitForReview']
)
    ->name('training.request.submission')
    ->middleware('auth');

Route::get('write-data', [PDFController::class,'fillPDFFile']);
Route::view('/import','users-upload');
Route::post('/import',[PDFController::class,'import'])->name('import');