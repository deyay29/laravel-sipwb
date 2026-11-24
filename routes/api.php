<?php

use App\Http\Controllers\Api\TehAiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Teh AI
|--------------------------------------------------------------------------
|
| Endpoint ini dipakai oleh widget chat lokal dan dapat dipanggil oleh
| asisten eksternal untuk mengambil konteks publik secara real-time.
| Semua data berasal dari tabel yang ditampilkan di situs publik.
|
*/

Route::prefix('teh-ai')->name('api.teh-ai.')->group(function () {
    // GET /api/teh-ai/context?q=kuliner+ramah+anak&limit=5
    Route::get('/context', [TehAiController::class, 'context'])->name('context');

    // GET /api/teh-ai/topics
    Route::get('/topics', [TehAiController::class, 'topics'])->name('topics');

    // POST /api/teh-ai/chat  { "message": "..." }
    Route::post('/chat', [TehAiController::class, 'chat'])->name('chat');
});
