<?php

namespace App\Http\Controllers;

use App\Models\UserRole;

class UserRoleController extends Controller
{
    /**
     * Get all user roles.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $roles = UserRole::all();

        return response()->json([
            'message' => 'User roles retrieved successfully.',
            'roles' => $roles,
        ]);
    }
}
