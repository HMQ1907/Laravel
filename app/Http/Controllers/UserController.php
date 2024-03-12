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
        $users = User::where('is_delete', 0)->paginate(20);
        return view('users.index', ['users' => $users]);
    }

    public function search(Request $request)
    {
        $user_name = $request->input('user_name');
        $user_email = $request->input('user_email');
        $user_group_role = $request->input('user_group_role');
        $user_is_active = $request->input('user_is_active');

        $query = User::select('id','name', 'email', 'group_role', 'is_active')->where('is_delete', '!=', '1');

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

        $users = $query->paginate(20);

        $paginationHtml = $users->links()->toHtml();

        return response()->json(['users' => $users, 'pagination' => $paginationHtml]);

    }

    public function getUserById($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'Không tỉm thấy user'], 404);
        }
        return response()->json($user);
    }
    public function create(UserFormRequest $request)
    {
        dd($request->all());
    }
}
