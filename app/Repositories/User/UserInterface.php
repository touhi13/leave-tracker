<?php

namespace App\Repositories\User;

use App\Models\User;

interface UserInterface
{
    public function all(array $data);
    public function updateUserStatus(array $data): ?User;    
}
