<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUser(){
        $data = User::select('id', 'name', 'email')->get()->toArray();
        return view('welcome',['data'=>$data]);
    }

    public function chatroom($id){
        $sender = User::select('name', 'email')->where('id', $id)->get()->first();
        $receiver = User::select('id', 'name', 'email')->get()->toArray();
        return response()->view('chatroom',['sender'=>$sender, 'receiver'=>$receiver]);
    }

    public function receiver(Request $request){
        
        $userId = $request->input('message.userId');

        if ($userId) {
            $receiver = User::select("name", "email")
                ->where("id", $userId)
                ->get()
                ->toArray();

            $response = [
                'code' => 1,
                'msg' => '',
                'data' => [
                    'userId' => $receiver
                ]
            ];

            return response()->json($response);

        } else {
            $response = [
                'code' => 0,
                'msg' => 'receiver data not found.',
                'data' => ''
            ];
            return response()->json($response);
        }
    }
}
