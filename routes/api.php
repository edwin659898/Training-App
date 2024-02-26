<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register',function(Request $request){
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'email' => 'required|email',
        'password' => 'required|confirmed',
        'deviceName' => 'required'
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 401);
    }

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'device_name' => $request->deviceName,
        'password' => Hash::make($request->password)
    ]);

    //$token =  $user->createToken('authToken')->plainTextToken;
    $token =  'XndjjGjdkkdutjGTNjdkdkdk';

    return response()->json(
        ['message' => 'You are logged in', 'token' => $token, 'user_id' => $user->id,
         'name' => $user->name,'deviceName' => $user->deviceName],
        200
    );

});


Route::post('/login',function(Request $request){
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required',
        //'deviceName' => 'required'
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 401);
    }

    $user = User::where('email',$request->email)->first();

    if($user){
        if (Hash::check($request->password, $user->password)) {
            //$token =  $user->createToken('authToken')->plainTextToken;
            $token =  'XndjjGjdkkdutjGTNjdkdkdk';
            return response()->json(
                ['message' => 'You are logged in', 'token' => $token, 'user_id' => $user->id,'name' => $user->name,],
                200
            );
        } else {
            $response = ["message" => "Password mismatch"];
            return response($response, 422);
        }
    }else {
        $response = ["message" => 'unable to find user with such credentials'];
        return response($response, 422);
    }


});