<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
   
    public function login(Request $request){
        $user=\App\User::where("mobile_number",$request->input("mobile_number"))->first();
            if(empty($user)){
                    return redirect()->back()->withErrors(["message"=>"Mobile Number doesn't exist"])->withInput($request->all());
            }
        $validator=\Validator::validate($request->all(),[
            "mobile_number"=>"required",
            "code"=>"required"
        ]);
    $response=$this->verifyCode($request->input("mobile_number"),$request->input("code"));
    $response=json_decode($response);
            if($response->type=="error"){
                return redirect()->back()->withErrors(["message"=>"Wrong Code detected try again"])->withInput($request->all());
            }        
            
        $result=\Auth::login($user);
        return redirect("/login");
    }
    private function verifyCode($number,$otp){
        
$curl = curl_init();
$mobile_no=str_replace("+","",$number);
$dataArray=[
    "authkey"=>"239123AbX5fzb05ba874bc",
    "otp"=>$otp,
    "mobile"=>$mobile_no
];
$data = http_build_query($dataArray);
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.msg91.com/api/v5/otp/verify?$data",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "",
  CURLOPT_SSL_VERIFYHOST => 0,
  CURLOPT_SSL_VERIFYPEER => 0,
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
//   echo "cURL Error #:" . $err;
exit;
} else {
  return $response;
}
    }
}
