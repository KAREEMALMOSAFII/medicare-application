<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\ProductWarehouseController;

use Validator;
use Hash;
class authController extends Controller
{



   public function register_warehouse(Request $request)//done
   {
        $request->validate([
            'name'=>'required',
            'email'=>'required|min:10|unique:users,email',
            'password'=>'required|min:8',
            'number'=>'required|digits:10|unique:users,number',
        ]
        ,['name.required'=>'name is required'],
            ['email.required'=>'email is required'],
            ['email.unique'=>'email already taken'],
            [ 'email.min'=>'email must be 10 characters at least'],
           [ 'password.required'=>'password is required'],
           [ 'password.min'=>'password must be 8 characters at least'],
           ['number.required'=>'number is required'],
           ['number.unique'=>'number already taken'],
           ['number.digits'=>'number must be 10 characters.'],

    );


    $input = $request->all();
    $input['password'] = Hash::make($input['password']);
    $input['admin'] = true;
    $user = User::create($input);
    $success['token'] = $user->createToken('kareem')->accessToken;
    $success['name'] = $user->name;

    $message = "Registration completed";
    return response()->json([
        'status' => "200",
        'message' => $message,
        'data' => $success,
    ]);

   }



   public function register_pharmacy(Request $request)//done
   {
    $request->validate([
        'name'=>'required',
        'email'=>'required|min:10|unique:users,email',
        'password'=>'required|min:8',
        'number'=>'required|digits:10|unique:users,number',
    ]
         ,['name.required'=>'name is required'],
        ['email.required'=>'email is required'],
        ['email.unique'=>'email already taken'],
        [ 'email.min'=>'email must be 10 characters at least'],
       [ 'password.required'=>'password is required'],
       [ 'password.min'=>'password must be 8 characters at least'],
       ['number.required'=>'number is required'],
       ['number.unique'=>'number already taken'],
       ['number.digits'=>'number must be 10 characters.'],

);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $input['admin'] = false;
        $user = User::create($input);
        $success['token'] = $user->createToken('kareem')->accessToken;
        $success['name'] = $user->name;

        $message = "Registration completed";
        return response()->json([
            'status' => "200",
            'message' => $message,
            'data' => $success,
        ]);
   }





   public function login_warehouse(Request $request)//done
   {
    if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        $user = Auth::user();
        $success['token'] = $user->createToken('kareem')->accessToken;
        $success['name'] = $user->name;


        $message="Login completed";
        return response()->json(
            [
                'status'=>1,
                'message'=>$message,
                'data'=>$success,
            ]);
    }

   else{
    $message="wrong email or password ";
    return response()->json(
        [
            'status'=>0,
            'message'=>$message,
            'data'=>[],
        ]
        ,500 );
    }
   }







   public function login_pharmacy(Request $request)//done
{



 $request->validate([
     'number'=>'required|min:10',
     'password'=>'required|min:8'
 ]
,
     ['number.required'=>'mobile number is required',],
    [ 'password.required'=>'password is required']
);

if (Auth::attempt(['number' => $request->number, 'password' => $request->password])) {
     $user = Auth::user();
     $success['token'] = $user->createToken('kareem')->accessToken;
     $success['name'] = $user->name;

     $message="Login completed";
     return response()->json(
         [
             'status'=>1,
             'message'=>$message,
             'data'=>$success,

         ]);
 }

else{
 $message="wrong email or password ";
 return response()->json(
     [
         'status'=>0,
         'message'=>$message,
         'data'=>[],
     ]
 );
 }
}



public function logout(Request $request)//done
{

$token= Auth::user()->token();
$token->revoke();


 return response()->json(
     [
         'status'=>200,
         'message'=>'logout success',
         'data'=>[],

     ]
 );

}



   public function warehouse_forget(Request $request)//done
{
    $user = User::where('email', $request->input('email'))
                ->where('number', $request->input('number'))
                ->first();

    if($user){
        $token = $user->createToken('kareem')->accessToken;
        $message = "check the user";
        return response()->json([
            'status' => 1,
            'message' => $message,
            'token' => $token,
        ]);
    } else {
        $message = "wrong inputs";
        return response()->json([
            'status' => 0,
            'message' => $message,
            'token' => null,
        ]);
    }
}





public function pharmacy_forget(Request $request)//done
{
    $user = User::where('email', $request->input('email'))
    ->where('number', $request->input('number'))
    ->first();

    if($user){
        $token = $user->createToken('kareem')->accessToken;
        $message = "check the user";
        return response()->json([
            'status' => 1,
            'message' => $message,
            'token' => $token,
        ]);
    } else {
        $message = "wrong inputs";
        return response()->json([
            'status' => 0,
            'message' => $message,
            'token' => null,
        ]);
    }
}



public function reset_password(Request $request)
{
    $user = auth()->user();

    $newPassword = $request->input('new_password');
    $user->password = bcrypt($newPassword);
    $user->save();

    $token = $user->createToken('kareem')->accessToken;
        $message = "Login completed";
        return response()->json([
            'status' => 1,
            'message' => $message,
            'token' => $token,
        ]);
    }



    public function edit_info(Request $request){
        $user = auth()->user();

    if($request->has('name')){
        $user->name = $request->input('name');
    }

    if($request->has('password')){
        $user->password = bcrypt($request->input('password'));
    }

    if($request->has('number')){
        if(User::where('number', $request->input('number'))->where('id', '!=', $user->id)->exists()){
            return response()->json([
                'status' => 0,
                'message' => 'Phone number already exists',
            ]);
        }
        $user->number = $request->input('number');
    }

    if($request->has('email')){
        if(User::where('email', $request->input('email'))->where('id', '!=', $user->id)->exists()){
            return response()->json([
                'status' => 0,
                'message' => 'Email already exists',
            ]);

            
        }
        $user->email = $request->input('email');
    }

    $user->save();

    $message = "the information updated successfully";
    return response()->json([
        'status' => 1,
        'message' => $message,
    ]);
}





    public function delete_the_user(Request $request){
        $user = auth()->user();
        $user->delete();
        $message = "The user deleted successfully.";
         return response()->json([
        'status' => 1,
        'message' => $message,
        'data' => $user,
    ]);

    }

 }
