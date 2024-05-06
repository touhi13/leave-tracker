<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\User\UserInterface;

class UserRepo implements UserInterface
{
    protected User $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function all($filters = [])
    {
        $query = $this->model->query()
            ->where('role', 'employee')
            ->orderBy('created_at', 'desc')
            ->when(isset($filters['search_text']), function ($query) use ($filters) {
                $query->where(function ($query) use ($filters) {
                    $query->where('name', 'like', '%' . $filters['search_text'] . '%')
                        ->orWhere('email', 'like', '%' . $filters['search_text'] . '%');
                });
            })
            ->when(isset($filters['status']), function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            })
            ->when(isset($filters['start_date']) && isset($filters['end_date']), function ($query) use ($filters) {
                $query->whereBetween('created_at', [$filters['start_date'], $filters['end_date']]);
            });

        return $query->paginate($filters['per_page'] ?? 10);

    }

    public function updateUserStatus(array $data): ?User
    {
        try {
            $user         = User::findOrFail($data['user_id']);
            $user->status = $data['status'];
            $user->save();
            return $user;
        } catch (\Exception $e) {
            return null;
        }
    }
}
