<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserFormRequest;
use Illuminate\Support\Facades\Validator;
class UserController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = User::select('id', 'name', 'email', 'group_role', 'is_active')->where('is_delete', 0);

            if ($request->filled('user_name')) {
                $query->where('name', 'like', '%' . $request->input('user_name') . '%');
            }
            if ($request->filled('user_email')) {
                $query->where('email', 'like', '%' . $request->input('user_email') . '%');
            }
            if ($request->filled('user_group_role')) {
                $query->where('group_role', $request->input('user_group_role'));
            }
            if ($request->filled('user_is_active')) {
                $query->where('is_active', $request->input('user_is_active'));
            }

            $users = $query->paginate(20);

            if ($request->ajax()) {
                if ($users->isEmpty()) {
                    return response()->json(['error' => 'Không tìm thấy người dùng phù hợp.']);
                }
                $view = view('users.table', ['users' => $users])->render();
                $paginationLinks = $users->links()->toHtml();
                return response()->json(['html' => $view, 'pagination_links' => $paginationLinks]);
            }
            return view('users.index', ['users' => $users]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getUserById($id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return response()->json(['error' => 'Không tỉm thấy user'], 404);
            }
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        try {
            $userId = $request->input('id');

            $user = User::find($userId);
            if (!$user) {
                return response()->json(['error' => 'Người dùng không tồn tại'], 404);
            }

            $messages = [
                'email.unique' => 'Email này đã được đăng kí',
            ];

            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255|unique:users,email,' . $userId,
                    'password' => 'nullable|string|min:6',
                    'user_group_role' => 'required|string|max:255',
                    'is_active' => 'required|boolean',
                ],
                $messages,
            );

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 400);
            }

            $user->name = $request->input('name');
            $user->email = $request->input('email');
            if ($request->input('password') && $request->input('password') != '') {
                $user->password = bcrypt($request->input('password'));
            }
            $user->group_role = $request->input('user_group_role');
            $user->is_active = $request->input('is_active');
            $user->save();

            return response()->json(['message' => 'Cập nhật người dùng thành công'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function create(UserFormRequest $request)
    {
        try {
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = bcrypt($request->input('password'));
            $user->group_role = $request->input('user_group_role');
            $user->is_active = $request->input('is_active');
            $user->save();
            return response()->json(['message' => 'Thêm người dùng thành công']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return response()->json(['error' => 'Người dùng không tồn tại'], 404);
            }
            $user->is_delete = 1;
            $user->save();
            return response()->json(['message' => 'Xóa người dùng thành công']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function block($id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return response()->json(['error' => 'Người dùng không tồn tại'], 404);
            }
            $user->is_active = $user->is_active == 1 ? 0 : 1;
            $user->save();
            return response()->json(['message' => 'Cập nhật thành công']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
