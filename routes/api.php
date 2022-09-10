<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Chat\ChatController;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Chat\ChatRoomController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->as('v1')->group(function () {
    Route::get('test-connection', function () {
        return response()->json(
            [
                'status' =>  'Connection established',
            ]
        );
    });

    Route::prefix('auth')->group(function () {
        Route::post('/login', [LoginController::class, 'login']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('chat')->as('chat.')->group(function () {
            Route::controller(ChatController::class)->group(function () {
                Route::get('/', 'index')->name('index');
            });
            Route::middleware(['isUserMemberOfChatRoom'])->controller(ChatRoomController::class)->prefix('room')->as('room.')->group(function () {
                Route::get('/{chatRoom}', 'index')->name('index')->missing(function () {
                    return response()->json(
                        [
                            'msg'   =>  'You have never started a conversation in this room',
                        ],
                        404
                    );
                });
                Route::post('/{chatRoom}', 'send')->name('send');
            });
        });
    });
});
