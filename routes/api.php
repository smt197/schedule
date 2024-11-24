<?php

use App\Http\Controllers\ScheduledTransferController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

Route::get('/schedule-status', function () {
    $lastRun = Cache::get('last_schedule_run');
    $status = $lastRun && $lastRun > now()->subMinutes(2) 
        ? 'running' 
        : 'delayed';
        
    return response()->json([
        'status' => $status,
        'last_run' => $lastRun
    ]);
});


Route::post('/schedule-transfer', [ScheduledTransferController::class, 'scheduleTransfer']);


