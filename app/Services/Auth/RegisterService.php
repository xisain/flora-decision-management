<?php

namespace App\Services\Auth;

use App\Models\CollectorInfo;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterService
{
    public function __construct()
    {

    }
    public function store(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'phone_number' => $data['phone_number'],
                'roles_id' => 1,
            ]);

            if (filled($data['collector_initial'])) {
                $initial = strtoupper(trim($data['collector_initial']));

                CollectorInfo::create([
                    'user_id' => $user->id,
                    'full_name' => $user->name,
                    'initial_collector_name' => $initial,
                    'is_manual' => false,
                    'last_sequence' => 0,
                ]);
            }

            return $user;
        });
    }
}
