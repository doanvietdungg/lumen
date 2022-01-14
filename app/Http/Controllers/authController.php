<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Support\Facades\Password;
class authController extends Controller
{

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
    public function login(request $request)
    {
        $email = $request->email;
        $password = $request->password;

        if (empty($email) || empty($password)) {
            return response()->json(['status' => 'error', 'message' => 'Ban nen dien day du thong tin']);
        }

        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
      else{
          $userStatus=auth()->user()->status;
          if($userStatus==1){
            return response()->json(['status' => 'error', 'message' => 'tk cua ban da bi khoa']);
          }
          else{
            return $this->respondWithToken($token);
          }


      }

    }


    public function register(request $request)
    {
        $this->validate($request, [

            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        // $data['password'] = Hash::make($request->password);
        $user = new User;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json($user);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }


    public function changePassword(request $request)
    {

        $this->validate($request, [
            'old_password' => 'required',
            'new_password' => 'required',
        ]);
        $user = Auth::user();
        if (!Hash::check($request->old_password, $user->password)) {

            return response()->json(['error' => 'You have entered wrong password']);
        } else {
            $user->password = Hash::make($request->new_password);
            $user->save();
            return response()->json(['message' => 'changepassword thanh cong']);
        }
        return response()->json(auth()->user());
    }

    // refresh token
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }



    //forgot password

    // public function resetpassword(request $request){
    //    $credentials =  $this->validate($request, [
    //         'email' => 'required|email',

    //     ]);

    //     Password::sendResetLink($credentials);

    //     return $this->respondWithMessage('Reset password link sent on your email id.');

    // }

    public function BlockUser($id){
           $userStatus=User::find($id);
           $userStatus->status=1;
           $userStatus->save();
           return response()->json(['message' => 'khoa thanh cong']);
}

public function unlockUser($id){
    $userStatus=User::find($id);
    $userStatus->status=0;
    $userStatus->save();
    return response()->json(['message' => 'mo thanh cong']);
}
}
