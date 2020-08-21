<?php

namespace App\Http\Controllers\Agents;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use Avatar;

use \App\Models\Modules\Topup;
use \App\Models\Modules\UserTransaction;

class AgentController extends Controller
{
    public function topupFilter (Request $request)
    {
        $query = Topup::query();

        if($request->search) {
            $query->where('mobile', 'LIKE', '%'.$request->search.'%');
        }
        if(!empty($request->to_date) && !empty($request->from_date)) {
            $query->whereBetween('created_at',
                    [$request->from_date,$request->to_date]
                             );
        }
        if(!\Auth::user()->hasRole("admin")){
            $query->where("sender_id",\Auth::user()->id);
        }
        

        $users = $query->orderBy($request->input('orderBy.column'), $request->input('orderBy.direction'))
                    ->paginate($request->input('pagination.per_page'));

        $users->load('roles');

        return $users;
    }

    public function show ($user)
    {
        return User::findOrFail($user);
    }

    public function storeTopup (Request $request)
    {
        $user=\App\User::where("mobile","=",$request->mobile)
        ->first();
        $agent=\App\User::find(\Auth::user()->id);
        if($request->amount<=0){
            return response()->json(
                ["errors"=>[
                    "amount"=>["Amount cannot be less than 10"]
                ]],400
            );   
        }
        if($agent->balance<$request->amount || !($agent->balance>0) || $agent->balance==0){
            return response()->json(
                ["errors"=>[
                    "mobile"=>["You don't have enough balance please recharge!"]
                ]],400
            );
        }
        if(empty($user))
        {
            return response()->json(
                ["errors"=>[
                    "mobile"=>["No User found with this mobile number !"]
                ]],400
            );
        }
        $this->validate($request, [
            'mobile' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);
        $topup = Topup::create([
            'mobile' => $request->mobile,
            'amount' => $request->amount,
            "receiver_id"=>$user->id,
            "sender_id"=>\Auth::user()->id
        ]);
        UserTransaction::create(
            [
                "user_id"=>$user->id,
                "credit"=>$request->amount
            ]
        );
        UserTransaction::create(
            [
                "user_id"=>\Auth::user()->id,
                "debit"=>$request->amount
            ]
        );
        $agent->balance=$agent->balance-$request->amount;
        $agent->save();
        $user->balance=$user->balance+$request->amount;
        $user->save();
        return $topup;
    }

    public function update (Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,'.$request->id,
            'password' => 'string|nullable',
            'roles' => 'required|array'
        ]);

        $user = User::find($request->id);

        if ($user->name != $request->name) {
            $avatar = Avatar::create($request->name)->getImageObject()->encode('png');
            Storage::put('avatars/'.$user->id.'/avatar.png', (string) $avatar);
            $user->name = $request->name;
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

    public function getUserRoles ($user)
    {
        $user = User::findOrFail($user);
        $user->getRoleNames();

        return $user;
    }


}
