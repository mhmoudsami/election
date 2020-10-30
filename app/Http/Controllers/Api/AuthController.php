<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $request->validate([
            'email' => ['required' , 'email'],
            'password' => ['required']
        ]);

		$credentials = $request->only('email', 'password');

        if ($id = \Auth::attempt($credentials)) {
            $user = User::find($id);
            // dd($user);
            // dd($user->createToken('Auth Token'));
            $token = $user->createToken('Auth Token')->accessToken;

            return [
            	'success' => true,
                'isSupervisor' => $user->getIsSupervisor(),
                'super_id' => $user->getSupervisorId(),
            	'payload' => $user,
                'token' => $token,
            ];
        }

        return [
        	'success' => false,
        ];
    }
}
