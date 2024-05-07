<?php

use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::post('/message-sent', function (\Illuminate\Http\Request $request){
//     if(!session()->has('username'))
//         session()->put('username', Faker\Factory::create()->userName);
//     \App\Events\MessageSent::dispatch(session()->get('username'), $request->message);
//     // \App\Events\MessageSent::dispatch('Swfsdfds', 'hello!');
//     return response()->json(['error'=> false, 'message'=> 'Message sent!']);
// });

Route::get('/', [UserController::class,'getUser'])->name('get-users');
Route::get('/chatroom/{id?}',[UserController::class,'chatroom'])->where('id', '[0-9]+')->name('chatroom');
Route::post('/receiver',[UserController::class,'receiver'])->name('receiver');

Route::post('/message-sent', function (\Illuminate\Http\Request $request){
    if(!session()->has('username'))
        session()->put('username', $request->senderName);
    \App\Events\MessageSent::dispatch($request->senderName, $request->message);
    // \App\Events\MessageSent::dispatch('Swfsdfds', 'hello!');
    return response()->json(['error'=> false, 'message'=> 'Message sent!']);
});