<?php

namespace App\Repositories\LeaveRequest;

interface LeaveRequestInterface
{
    public function all(array $data);
    public function save(array $data);
    public function manage($data, $id);
    public function leaveRequestsCounts();
}
