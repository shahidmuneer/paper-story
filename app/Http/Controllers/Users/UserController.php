<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use \App\Models\Modules\UserTransaction;
use Avatar;

use App\User;

class UserController extends Controller
{
    public function filter (Request $request)
    {
        $query = User::query();

        if($request->search) {
            $query->where('name', 'LIKE', '%'.$request->search.'%');
        }

        $users = $query->orderBy($request->input('orderBy.column'), $request->input('orderBy.direction'))
                    ->paginate($request->input('pagination.per_page'));

        $users->load('roles');

        return $users;
    }
    public function transactions(Request $request){
        $query = UserTransaction::query();
        // if($request->search) {
        //     $query->where('name', 'LIKE', '%'.$request->search.'%');
        // }
        if(!empty($request->to_date) && !empty($request->from_date)) {
            $query->whereBetween('created_at',
                    [$request->from_date,$request->to_date]
                             );
        }
        if(!\Auth::user()->hasRole("admin")){
            $query->where("user_id",\Auth::user()->id);
        }
        
        $transactions = $query->orderBy($request->input('orderBy.column'), $request->input('orderBy.direction'))
                    ->paginate($request->input('pagination.per_page'));
        return $transactions;
    }
    public function show ($user)
    {
        return User::findOrFail($user);
    }

    public function store (Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
            'roles' => 'required|array',
            "balance"=>"numeric"
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            "balance"=>$request->balance,
            'password' => Hash::make($request->password)
        ]);

        $rolesNames = array_pluck($request->roles, ['name']);
        $user->assignRole($rolesNames);

        $avatar = Avatar::create($user->name)->getImageObject()->encode('png');
        Storage::put('avatars/'.$user->id.'/avatar.png', (string) $avatar);
        return $user;
    }

    public function update (Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,'.$request->id,
            'password' => 'string|nullable',
            'roles' => 'required|array',
            "balance"=>"numeric"
        ]);

        $user = User::find($request->id);

        if ($user->name != $request->name) {
            $avatar = Avatar::create($request->name)->getImageObject()->encode('png');
            Storage::put('avatars/'.$user->id.'/avatar.png', (string) $avatar);
            $user->name = $request->name;
        }
        if(!empty($request->balance)){
            $user->balance=$request->balance;
        }
        if ($user->email != $request->email) {
            $user->email = $request->email;
        }
        if ($request->password != '') {
            $user->password = Hash::make($request->password);
        }
        $user->save();
        $rolesNames = array_pluck($request->roles, ['name']);
        $user->syncRoles($rolesNames);

        return $user;
    }

    public function destroy ($user)
    {
        return User::destroy($user);
    }

    public function count ()
    {
        return User::count();
    }

    public function countBalance(){
        return \App\User::find(\Auth::user()->id)->balance??0;
    }
    public function getUserRoles ($user)
    {
        $user = User::findOrFail($user);
        $user->getRoleNames();

        return $user;
    }
    public function sendOtp(Request $request){
        $curl = curl_init();
        $dataArray=[
            "authkey"=>"239123AbX5fzb05ba874bc",
            "template_id"=>"5f32446bd6fc05550028011b",
            "mobile"=>$request->input("mobile")
        ];
        $data = http_build_query($dataArray);

    curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.msg91.com/api/v5/otp?$data",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json"
            ),
            ));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}
    }
    public function verifyOtp(Request $request){
$curl = curl_init();
$mobile_no=str_replace("+","",$request->input("mobile"));
$dataArray=[
    "authkey"=>"239123AbX5fzb05ba874bc",
    "otp"=>$request->input("otp"),
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
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}
    }


}
