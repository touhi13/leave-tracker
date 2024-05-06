<?php

namespace App\Http\Controllers;

use App\Repositories\User\UserInterface;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponseTrait;

    private UserInterface $repository;

    public function __construct(UserInterface $repository)
    {
        $this->repository = $repository;
    }

    public function all(Request $request)
    {
        $filters = $request->only(['search_text', 'status', 'start_date', 'end_date', 'per_page', 'page']);

        $users = $this->repository->all($filters);

        return $this->ResponseSuccess($users, null, 'user', 200, 'success');
    }

    public function updateUserStatus(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'user_id' => 'required|integer',
            'status'  => 'required|string|in:Active,Inactive,Blocked',
        ]);

        $user = $this->repository->updateUserStatus($validatedData);

        if ($user !== null) {
            return $this->ResponseSuccess($user, 'User status updated successfully');
        } else {
            return $this->ResponseError(false, 'Failed to update user status', 500);
        }
    }
}
