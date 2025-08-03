<?php

use App\Http\Controllers\VideoChatController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');



Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');


    Route::get('/video-chat', [VideoChatController::class, 'index'])->name('video.chat');
    Route::post('/pusher/auth', [VideoChatController::class, 'pusherAuth']);
    // Signaling routes
    Route::post('/video/call-user/{userToCallId}', [VideoChatController::class, 'callUser'])->name('video.call.user');
    Route::post('/video/accept-call', [VideoChatController::class, 'acceptCall'])->name('video.call.accept');
    Route::post('/video/candidate', [VideoChatController::class, 'sendCandidate'])->name('video.call.candidate');
    Route::post('/video/decline-call', [VideoChatController::class, 'declineCall'])->name('video.call.decline');
    Route::post('/video/end-call', [VideoChatController::class, 'endCall'])->name('video.call.end');


});





require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
