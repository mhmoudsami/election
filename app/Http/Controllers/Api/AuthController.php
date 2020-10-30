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
            'name' => ['required'],
            'password' => ['required']
        ]);

		$credentials = $request->only('name', 'password');

        if ($id = \Auth::attempt($credentials)) {
            // dd($id);
            $user = \Auth::user();
            // dd($user);
            // dd($user->supervisor);
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
