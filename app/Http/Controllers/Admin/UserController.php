<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\users\UserCreateRequest;
use App\Http\Requests\admin\users\UserUpdateRequest;
use App\Models\CollectorInfo;
use App\Models\Role;
use App\Models\User;
use App\Repositories\userRepository;
use App\Services\admin\UserService;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(private UserService $us, private userRepository $userRepository)
    {

    }
    public function index(Request $request)
    {
        $users = $this->userRepository->getFilteredUsers($request);
        $roles = Role::all();
        $admin = User::where('roles_id','=',1)->count();
        return view('admin.users.index', compact('users', 'roles','admin'));
    }
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }
    public function store(UserCreateRequest $request)
    {
        $user = $this->us->register($request->toServiceData());
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
    public function update(UserUpdateRequest $request, User $user)
    {
        $this->us->update($request->validated(),$user);

        return redirect()
            ->route('user.index')
            ->with('success', 'User berhasil diupdate');
    }

    public function destroy(Request $request, User $user)
    {
        $user = User::findOrFail($request->user_id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }


        $isAdmin = $user->roles_id != 3;
        $onlyOneAdmin = User::where('roles_id', '!=', 3)->count() === 1;

        if ($isAdmin && $onlyOneAdmin) {
            return back()->with('error', 'Admin terakhir tidak bisa dihapus.');
        }


        $collector_data = CollectorInfo::where('user_id', $user->id)->get();
        if ($collector_data->isNotEmpty()) {
            if ($request->delete_collector == 1) {
                CollectorInfo::where('user_id', $user->id)->delete();
            } else {
                CollectorInfo::where('user_id', $user->id)->update(['user_id' => null]);
            }
        }

        $user->delete();

        return back()->with('success', 'User ' . $user->name . ' berhasil dihapus.');
    }

}
