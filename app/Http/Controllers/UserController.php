<?php

namespace  App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use  Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller implements HasMiddleware
{
    // public function __construct()
    // {
    //     $this->middleware('permission:view user', ['only' => ['index']]);
    //     $this->middleware('permission:create user', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:update user', ['only' => ['update', 'edit']]);
    //     $this->middleware('permission:delete user', ['only' => ['destroy']]);
    // }

    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:view user', only: ['index', 'data']),
            new Middleware('permission:create user', only: ['create', 'store']),
            new Middleware('permission:update user', only: ['update', 'edit']),
            new Middleware('permission:delete user', only: ['destroy']),
        ];
    }

    public function index()
    {
        // $users = User::get();
        // $roles = Role::pluck('name', 'name')->all();
        return view('role-permission.user.index');
    }

    public function data()
    {
        $users = User::with('roles')->get();

        return DataTables::of($users)
            ->rawColumns(['roles'])
            ->make(true);
    }

    public function show($id)
    {
        $users = User::find($id);
        return $users;
    }

    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('role-permission.user.create', ['roles' => $roles]);
    }

    public function store(UserRequest $request)
    {
        DB::beginTransaction();
        try {
            // $request->validate([
            //     'name' => 'required|string|max:255',
            //     'email' => 'required|email|max:255|unique:users,email',
            //     'password' => 'required|string|min:8|max:20',
            //     'roles' => 'required'
            // ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->syncRoles($request->roles);

            DB::commit();

            return response()->json([
                'data' => $user
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => $e
            ], 500);
        }
    }

    public function uploadSignature($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'signature' => 'required|file|mimes:png,jpg|max:512'
            ]);

            $user = User::find($id);
            $path = $request->file('signature')->store('signature');

            $user->signature = $path;
            $user->save();

            DB::commit();
            return response()->json([
                'data' => $user
            ], 201);
        } catch (\Throwable $e) {
            DB::rollback();
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit(User $user)
    {
        $roles = Role::pluck('name', 'name')->all();
        $userRoles = $user->roles->pluck('name', 'name')->all();
        return view('role-permission.user.edit', [
            'user' => $user,
            'roles' => $roles,
            'userRoles' => $userRoles
        ]);
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $user = User::find($id);
            // dd($request->name);
            $request->validate([
                'name' => 'required|string|max:255',
                'password' => 'nullable|string|min:8|max:20',
                'roles' => 'required'
            ]);

            $data = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            if (!empty($request->password)) {
                $data += [
                    'password' => Hash::make($request->password),
                ];
            }

            $user->update($data);
            $user->syncRoles($request->roles);

            DB::commit();

            return response()->json([
                'data' => $user
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => $e
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $user = User::findOrFail($id);
            $user->delete();

            DB::commit();

            return response()->json([
                'data' => $user
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => $e
            ], 500);
        }
    }
}
