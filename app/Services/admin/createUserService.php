<?php

namespace App\Services\admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\CollectorInfo;

class createUserService
{
    public function __construct()
    {
        //
    }
    public function register(array $data)  : User {
        return  DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'roles_id' => $data['roles_id'],
                'phone_number' => $data['phone_number'],
                'account_status' => $data['account_status'] === 'active' ? true : false,
            ]);
            if($data['is_collector'] != null){
                CollectorInfo::create([
                    'user_id'=> $user->id,
                    'full_name' => $user->name,
                    'initial_collector_name' => $data['collector_initial_name'],
                    'is_manual' => false,
                    'last_Sequence'=> 0,
                ]);
            }
        });
    }
}
