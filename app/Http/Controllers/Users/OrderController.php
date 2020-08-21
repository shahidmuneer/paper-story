<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use \App\Models\Modules\UserTransaction;
use Avatar;

use App\User;

class OrderController extends Controller
{
    public function filter (Request $request)
    {

        
        // $query = \App\Models\Modules\Order::with(["user"])->get();

        // dd($query);
        $query = \App\Models\Modules\Order::query();
        $query->select(["orders.id","source","net_cost","grand_total","status","download_pdf",
        \DB::raw("users.full_name as user_name"),
        "pdf_link",
        \DB::raw("address.address as address")]);
        $query->leftJoin("users","users.id","=","orders.user_id");
        $query->leftJoin("address","address.id","=","orders.address_id");
        if($request->search) {
            $query->where('name', 'LIKE', '%'.$request->search.'%');
        }

        $orders = $query->orderBy($request->input('orderBy.column'), $request->input('orderBy.direction'))
                    ->paginate($request->input('pagination.per_page'));

        return $orders;
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

    public function getWhatsappData(Request $request){
        $order = \App\Models\Modules\Order::where("id",$request->order_id)->first();
        $address=\App\Models\Modules\Address::find($order->address_id);
        
        return response()->json([
          "orderData"=>$order,
          "address"=>$address
        ]);
  
  
      }
      public function updateStatus(Request $request){
       $id=$request->input("orderId");
       $status=$request->input("status");
       \App\Models\Modules\order::where("id",$id)->update([
           "status"=>$status
       ]);
       return response()->json(["message"=>"order updated successfully "]);
      }
    public function checkLoginStatus(){

        if(\Auth::check()){

            return response()->json(["status"=>"loggedin","user_id"=>\Auth::user()->id,"isAdmin"=>\Auth::user()->hasRole("admin")]);
        }else{
            return response()->json(["status"=>"loggedout"]);
            
        }
    }
    public function updateWhatsappOrder(Request $request,$id){

       $address=$request->input("address");
           $address= \App\Models\Modules\Address::where("id",$request->input("addressId"))->update([
              "full_name"=>$address["full_name"],
              "mobile_number"=>$address["mobile_number"],
              "address"=>$address["address"],
              "city"=>$address["city"],
              "state"=>$address["state"],
              "landmark"=>$address["landmark"]
            ]);
        
            $updateOrderData=[
                "source"=>$request->input("source"),
                  "cost_and_cover_details"=>json_encode($request->input("orderData")),
                  "background_design"=>json_encode($request->input("colorData")),
                  "net_cost"=>$request->input("net_cost"),
                  "grand_total"=>$request->input("grand_total"),
                  "download_pdf"=>$request->input("download_pdf")
            ];
            if(!empty($request->input("pdf_link"))){
                $updateOrderData["pdf_link"]=$request->input("pdf_link");
            }
        $order=\App\Models\Modules\Order::where("id",$id)->update($updateOrderData);
        return response()->json(["message"=>"Order updated Successfully !",
                "status"=>200],200);
  }


}
