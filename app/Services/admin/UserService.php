<?php

namespace App\Services\admin;

use App\Models\CollectorInfo;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function register(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'roles_id' => $data['roles_id'],
                'phone_number' => $data['phone_number'],
                'account_status' => $data['account_status'] === 'active' ? true : false,
            ]);
            if (! empty($data['is_collector'])) {
                CollectorInfo::create([
                    'user_id' => $user->id,
                    'full_name' => $user->name,
                    'initial_collector_name' => $data['collector_initial_name'],
                    'is_manual' => false,
                    'last_Sequence' => 0,
                ]);
            }

            return $user;
        });
    }

    public function update(array $validated, User $user)
    {
        return DB::transaction(function () use ($validated, $user) {
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'] ?? null,
                'roles_id' => $validated['roles_id'],
                'account_status' => $validated['account_status'] === 'active' ? true : false,
            ]);
            if (!empty($validated['is_collector'])) {
                // dd('ada akun collector');
                $user->collectorInfo()->updateOrCreate(
                    [], // karena 1-1
                    [
                        'full_name' => $validated['collector_display_name'] ?? $validated['name'],
                        'initial_collector_name' => $validated['collector_initial_name'],
                        'is_manual' => $validated['is_manual'] ?? false,
                    ]
                );

            } else {
                // kalau sebelumnya ada → hapus
                $user->collectorInfo()->delete();
            }
        });
    }
}
