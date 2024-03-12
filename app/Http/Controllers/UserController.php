<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserFormRequest;
class UserController extends Controller
{
    //
    public function index()
    {
        $users = User::all()->where('is_delete', 0);
        return view('users.index', ['users' => $users]);
    }

    public function search(Request $request)
    {
        $user_name = $request->input('user_name');
        $user_email = $request->input('user_email');
        $user_group_role = $request->input('user_group_role');
        $user_is_active = $request->input('user_is_active');

        $query = User::select('name', 'email', 'group_role', 'is_active')->where('is_delete', '!=', '1');

        if ($user_name) {
            $query->where('name', 'like', "%$user_name%");
        }
        if ($user_email) {
            $query->where('email', 'like', "%$user_email%");
        }
        if ($user_group_role) {
            $query->where('group_role', $user_group_role);
        }
        if ($user_is_active !== null) {
            $query->where('is_active', $user_is_active);
        }

        $users = $query->get();

        return response()->json(['users' => $users]);
    }
    public function create(UserFormRequest $request)
    {
        dd($request->all());
    }
}
