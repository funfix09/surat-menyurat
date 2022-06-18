<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('division')
                        ->when($request->has('name'), function($query) use ($request) {
                            $query->where('name', 'LIKE', '%'.$request->name.'%');
                        })
                        ->when($request->has('email'), function($query) use ($request) {
                            $query->where('email', 'LIKE', '%'.$request->email.'%');
                        })
                        ->when($request->has('division_id'), function($query) use ($request) {
                            $query->where('division_id', $request->division_id);
                        })
                        ->orderBy('division_id', 'asc')
                        ->orderBy('name', 'asc')
                        ->paginate(30)
                        ->appends(request()->query());

        // dd($request->all());

        $divisions = Division::select('id','name')->orderBy('name', 'asc')->get();

        return view('users.index', compact('users', 'divisions'));
    }

    public function create()
    {
        $roles = Role::orderBy('id', 'asc')->get();
        $divisions = Division::orderBy('name', 'asc')->get();

        return view('users.create', compact('roles', 'divisions'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role'     => 'required'
        ]);

        $data = [
            'name' => $request->name,
            'email' => strtolower($request->email),
            'password' => Hash::make($request->password)
        ];

        if ($request->role == 'admin-bidang' || $request->role == 'karyawan') {
            $this->validate($request, [
                'division_id' => 'required|numeric'
            ]);

            $data['division_id'] = $request->division_id;
        }

        $new_user = User::create($data);

        $new_user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function edit($id)
    {   
        $user      = User::findOrFail($id);
        $divisions = Division::orderBy('name', 'asc')->get();
        $roles     = Role::pluck('name','name')->all();
        $user_role = $user->roles->pluck('name','name')->first();

        return view('users.edit', compact('user', 'divisions', 'roles', 'user_role'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'  => 'required|string',
            'email' => 'required|email|unique:users,email,'.$id,
            'role'  => 'required'
        ]);

        $data = [
            'name'  => $request->name,
            'email' => strtolower($request->email),
        ];

        if ($request->password != null) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->role == 'admin-bidang' || $request->role == 'karyawan') {
            $this->validate($request, [
                'division_id' => 'required|numeric'
            ]);

            $data['division_id'] = $request->division_id;
        } else {
            $data['division_id'] = NULL;
        }

        $user = User::where('id', $id)->first();
        $user->update($data);

        DB::table('model_has_roles')->where('model_id',$id)->delete();

        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diupdate.');
    }

    public function destroy($id)
    {
        User::where('id', $id)->delete();
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
