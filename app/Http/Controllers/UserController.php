<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required'
        ]);

        if ($validator->fails())
            return response(['errors'=> $validator->errors()->all()], 422);

        $email = $request->get('email');
        $password = $request->get('password');

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $user = Auth::user();
            $token = $user->createToken("token");
            return response(["message" => $user, "token" => $token->plainTextToken], 200)->cookie(
                'token', $token->plainTextToken, 20, '/', request()->getHost(), false, true
            );
        }
        
        return response(["message" => "falhou"], 422);
    }
}
