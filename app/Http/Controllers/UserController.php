<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Log the user in.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!auth()->attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid credentials.',
            ], 401);
        }

        $user = auth()->user()->load('role');

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User logged in successfully.',
            'token' => $token,
            'user' => $user,
        ]);
    }

    /**
     * Verify the user's bearer token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify()
    {
        $user = User::with('role')->findOrFail(auth()->id());
        return response()->json([
            'message' => 'User token verified successfully.',
            'user' => $user,
        ]);
    }

    /**
     * Log the user out.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'User logged out successfully.',
        ]);
    }

    /**
     * Get all users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = User::with('role')->get();

        return response()->json([
            'message' => 'Users retrieved successfully.',
            'users' => $users,
        ]);
    }

    /**
     * Get a user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = User::with('role')->findOrFail($id);

        return response()->json([
            'message' => 'User retrieved successfully.',
            'user' => $user,
        ]);
    }

    /**
     * Create a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone' => 'required|unique:users',
            'password' => 'required|string',
            'role_id' => 'exists:user_roles,id',
        ]);

        // Check if the user is authenticated and is an admin
        if (auth()->check() && auth()->user()->role_id == 1) {
            // Admin can create any type of user
            $user = User::create($request->all());
        } else {
            // Unauthenticated users can only create users with 'user' role
            $user = User::create(array_merge(
                $request->except('role_id'), ['role_id' => 3]
            ));
        }

        return response()->json([
            'message' => 'User created successfully.',
            'user' => $user,
        ]);
    }

    /**
     * Update a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'string',
            'email' => 'email|unique:users,email,' . $id,
            'phone' => 'unique:users,phone,' . $id,
            'password' => 'string',
            'role_id' => 'exists:user_roles,id',
        ]);

        $user = User::findOrFail($id);

        // If user is updating their own record, ensure they are not changing their role
        if ($id == auth()->id() && $request->has('role_id')) {
            return response()->json([
                'message' => 'You cannot change your role.',
            ], 403);
        }

        $user->update($request->all());

        return response()->json([
            'message' => 'User updated successfully.',
            'user' => $user,
        ]);
    }

    /**
     * Delete a user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(int $id)
    {
        // Prevent the deletion of self
        if ($id == auth()->id()) {
            return response()->json([
                'message' => 'You cannot delete yourself.',
            ], 403);
        }

        $user = User::findOrFail($id);

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully.',
        ]);
    }
}
