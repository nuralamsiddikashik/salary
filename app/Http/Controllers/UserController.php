<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {
    public function index() {
        $users       = User::with( 'permissions' )->latest()->get();
        $permissions = Permission::all();
        return view( 'users.index', compact( 'users', 'permissions' ) );
    }

    public function create() {
        $permissions = Permission::all();
        return view( 'users.create', compact( 'permissions' ) );
    }

    public function store( Request $request ) {
        $request->validate( [
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,editor',
        ] );

        $user = User::create( [
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make( $request->password ),
            'role'      => $request->role,
            'is_active' => true,
        ] );

        $user->permissions()->sync( $request->permissions ?? [] );

        return redirect()->route( 'users.index' )->with( 'success', 'User Created' );
    }

    public function update( Request $request, $id ) {
        $user = User::findOrFail( $id );

        $data = [
            'role'      => $request->role,
            'is_active' => (int) $request->input( 'is_active', 0 ),
        ];

        // 🔥 Password only update if provided
        if ( $request->filled( 'password' ) ) {
            $request->validate( [
                'password' => 'min:6|confirmed',
            ] );

            $data['password'] = Hash::make( $request->password );
        }

        $user->update( $data );

        $user->permissions()->sync( $request->permissions ?? [] );

        return back()->with( 'success', 'User Updated Successfully' );
    }
}