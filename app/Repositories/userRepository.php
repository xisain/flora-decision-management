<?php

namespace App\Repositories;
use Illuminate\Http\Request;
use App\Models\User;

class userRepository
{
    public function __construct()
    {
        //
    }
    public function getFilteredUsers(Request $request)
{
    return User::with(['roles', 'collectorInfo'])
        ->when($request->search, function ($query) use ($request) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            });
        })
        ->when($request->role, function ($query) use ($request) {
            $query->where('roles_id', $request->role);
        })
        ->when($request->filled('status'), function ($query) use ($request) {
            $query->where('account_status', $request->status);
        })
        ->paginate(10)
        ->withQueryString();
}
}
