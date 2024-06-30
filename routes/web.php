<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\FileDownloadController;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Mail;
use App\Mail\CampaignEmailMailable;
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
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    });
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('campaign', CampaignController::class);
    Route::get('/download-file/{type}/{fileName}/{delete}', [FileDownloadController::class, 'download'])->name('download.file');
    Route::post('/campaign/{campaign}', [CampaignController::class, 'update'])->name('campaign.update');
    Route::post('campaigns/{id}/process', [CampaignController::class, 'process'])->name('campaigns.process');

    Route::get('/send-test-email', function () {
        // Define the recipient email address
        $recipientEmail = 'ashfouryou@gmail.com';
    
        // Send the test email
        Mail::to($recipientEmail)->send(new CampaignEmailMailable('Test', 'Test', 'Test'));
    
        // Return a response to indicate the email was sent
        return response()->json(['message' => 'Test email sent successfully.']);
    });

    
});




require __DIR__.'/auth.php';