<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;
use App\Mail\NewUserNotification;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Mail\EmailDemo;
use App\Mail\SendMail;

use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
     /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    // public function __construct() {
    //     $this->middleware('auth:api', ['except' => ['login', 'register']]);
    // }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Either email or password is wrong.'], 401);
        }
        // Mail::to('alirezamosavi346@gmail.com')->send(new NewUserNotification()); 
        return $this->createNewToken($token);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
    */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $user = User::create(array_merge(
                    $validator->validated()
                ));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }


   

    


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'User successfully logged out']);
    }




    //   send mail
    public function contactPost(Request $request){
        // $validator = Validator::make($request->all(), [
        //     'email' => 'required|email'
        // ]);
        
               // Mail::to($request->get('email'))->send(new NewUserNotification()); 
             
            $email = $request->get('email');
          

            Mail::to($email)->send(new NewUserNotification()); 

            return response()->json([
                'message' => 'Email has been sent.'
            ]);

    }

    public function sendEmail(Request $request) {
        $email = $request->get('email');
   
        $mailData = [
            'title' => 'Demo Email',
            'url' => 'https://www.positronx.io'
        ];
  
        Mail::to($email)->send(new EmailDemo($mailData));
   
        return response()->json([
            'message' => 'Email has been sent.'
        ]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        return response()->json(auth()->user());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }


    public function sendemai(Request $request) { 
        $title = '[Confirmation] Thank you for your order'; 
        $customer_details = [ 
            'name' => $request->get('name'), 
            'address' => 'kathmandu Nepal', 
            'phone' => '123123123', 
            'email' => $request->get('email') 
        ];
         $order_details = [ 
             'SKU' => 'D-123456', 
             'price' => '10000', 
             'order_date' => '2020-01-22', 
            ]; 
            $sendmail = Mail::to($customer_details['email'])
            ->send(new SendMail($title, $customer_details, $order_details)); 
            if (empty($sendmail)) { 
                return response()->json([
                    'message' => 'Mail Sent Sucssfully'
                ], 200); 
            }else{ 
                return response()->json([
                    'message' => 'Mail Sent fail'], 400); 
                } 
            }


}
