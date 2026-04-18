<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\users\UserCreateRequest;
use App\Models\CollectorInfo;
use App\Models\Role;
use App\Models\User;
use App\Repositories\userRepository;
use App\Services\admin\createUserService;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(private createUserService $cus, private userRepository $userRepository)
    {

    }
    public function index(Request $request)
    {
        $users = $this->userRepository->getFilteredUsers($request);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }
    public function store(UserCreateRequest $request)
    {
        // dd($request->all());
        $user = $this->cus->register($request->toServiceData());
        if ($user) {

            return redirect()->route('user.index')->with('success', 'Akun Berhasil Di Daftarkan');
        } else {
            return redirect()->route('user.index')->with('errors', 'Something Wrong');
        }
    }
    public function edit($id)
    {
        $user = User::with('collectorInfo')->findOrFail($id);
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', "unique:users,email,{$user->id}"],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'roles_id' => ['required', 'exists:roles,id'],
            'account_status' => ['required', 'in:active,inactive'],

            // collector (conditional)
            'is_collector' => ['nullable', 'boolean'],
            'collector_initial_name' => ['required_if:is_collector,true', 'max:3', "unique:collector_infos,initial_collector_name,{$user->id}"],
            'collector_display_name' => ['nullable', 'max:255'],
        ]);
        $isCollector = $request->has('is_collector');
        DB::transaction(function () use ($validated, $user, $isCollector) {

            // 🔹 update user
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'] ?? null,
                'roles_id' => $validated['roles_id'],
                'account_status' => $validated['account_status']  === 'active' ? true : false,
            ]);

            // 🔹 handle collector
            if ($isCollector) {

                $user->collectorInfo()->updateOrCreate(
                    [], // karena 1-1
                    [
                        'full_name' => $validated['collector_display_name'] ?? $validated['name'],
                        'initial_collector_name' => $validated['collector_initial_name'],
                    ]
                );

            } else {

                // kalau sebelumnya ada → hapus
                $user->collectorInfo()->delete();
            }
        });

        return redirect()
            ->route('user.index')
            ->with('success', 'User berhasil diupdate');
    }

    public function destroy(Request $request, User $user)
    {
        $user = User::findOrFail($request->user_id);
        // dd($user->name);
        $collector_data = CollectorInfo::where('user_id',$user->id)->get();
        // if($request->)
        dd($collector_data);
    }

}
