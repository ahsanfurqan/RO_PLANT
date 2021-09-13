<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Mail\Message;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Notifications\ResetPassword;
use PhpParser\Node\Expr\Cast\Array_;
use Illuminate\Auth\Passwords\DatabaseTokenRepository;

class ForgotPasswordApiController extends Controller
{
      /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */
    use SendsPasswordResetEmails;
    public function sendResetLinkEmail(Request $request)
    {
        // Validate user input
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'max:255' ]
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        //$tokenData = DB::table('password_resets')->where('email', $request->email)->first();
        // // Attempt to send the password reset email to user.  
        // if ($this->sendResetEmail($request->email, $tokenData->token)) {
        //     return redirect()->back()->with('status', trans('A reset link has been sent to your email address.'));
        // } else {
        //     return redirect()->back()->withErrors(['error' => trans('A Network Error occurred. Please try again.')]);
        // }
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );
        // $response = Password::sendResetLink($data, function (Message $message) {
        //     $message->subject($this->getEmailSubject());
        // });
        // After attempting to send the link, we can examine the response to see 
        // // the message we need to show to the user and then send out a 
        // // proper response.

        return $response == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse($request, $response)
                    : $this->sendResetLinkFailedResponse($request, $response);
    }
     /**
     * Send the response for a successful password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\JsonResponse
     */
    // private function sendResetEmail($email, $token)
    // {
    //     //Retrieve the user from the database
    //     $user = DB::table('users')->where('email', $email)->select('email')->first();
    //     //Generate, the password reset link. The token generated is embedded in the link
    //     $link = config('base_url') . 'password/reset/' . $token . '?email=' . urlencode($user->email);
    //     $user=array(
    //         'email'=>$user->email
    //     );
    //     try {
    //     //Here send the link with CURL with an external email API 
    //     $response = Password::sendResetLink($user, function (Message $message) {
    //             $message->subject($this->getEmailSubject());
    //         });   
    //        return true;
    //     } catch (\Exception $e) {
    //         return false;
    //     }
    // }
    protected function sendResetLinkResponse(Request $request, $response)
    {
        
        // On success, a string $response is returned with value of RESET_LINK_SENT 
        // from the Password facade (the default is "passwords.sent") 
        // Laravel trans() function translates this response to the text  
        // designated in resources/lang/en/passwords.php

        return response()->json(['status_message' => trans($response)] , 200);   
    }
    /**
     * Send the response for a failed password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {        
        return response()->json(['error' => ["message" => trans($response)] ], 422);    
    }   

}
