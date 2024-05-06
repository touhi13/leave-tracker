<?php

namespace App\Repositories\LeaveRequest;

use App\Models\LeaveRequest;
use App\Repositories\LeaveRequest\LeaveRequestInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeaveRequestRepo implements LeaveRequestInterface
{
    protected LeaveRequest $model;

    public function __construct(LeaveRequest $model)
    {
        $this->model = $model;
    }
    public function all($filters = [])
    {

        $query = $this->model->with('user')
            ->orderBy('created_at', 'desc')
            ->when(isset($filters['search_text']), function ($query) use ($filters) {
                $query->whereHas('user', function ($subQuery) use ($filters) {
                    $subQuery->where('name', 'like', '%' . $filters['search_text'] . '%');
                });
            })
            ->when(isset($filters['leave_type']), function ($query) use ($filters) {
                $query->where('leave_type', $filters['leave_type']);
            })
            ->when(isset($filters['status']), function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            })
            ->when(isset($filters['start_date']) && isset($filters['end_date']), function ($query) use ($filters) {
                $query->whereBetween('created_at', [$filters['start_date'], $filters['end_date']]);
            })
            ->when(auth()->user()->role === 'employee', function ($query) {
                $query->where('user_id', auth()->id());
            });

        return $query->paginate($filters['per_page'] ?? 10);
    }

    public function save(array $data)
    {
        $leaveRequest = new LeaveRequest();
        $leaveRequest->fill($data);
        $leaveRequest->save();
        // dd($leaveRequest);
        $leaveRequest->load('user');
        return $leaveRequest;
    }

    public function manage($data, $id)
    {
        $leaveRequest = LeaveRequest::with('user')->find($id);

        if (!$leaveRequest) {
            return null; // Return null if leave request not found
        }

        if ($data['action'] === 'Approved' || $data['action'] === 'Rejected') {
            $leaveRequest->update([
                'status'        => $data['action'],
                'admin_comment' => $data['admin_comment'] ?? null,
            ]);
        } else {
            return null;
        }

        return $leaveRequest;
    }

    public function leaveRequestsCounts()
    {
        $result = DB::table('leave_requests')
            ->selectRaw('COUNT(*) as total_count,
                 SUM(CASE WHEN status = "Pending" THEN 1 ELSE 0 END) as pending_count,
                 SUM(CASE WHEN status = "Approved" THEN 1 ELSE 0 END) as approved_count,
                 SUM(CASE WHEN status = "Rejected" THEN 1 ELSE 0 END) as rejected_count')
            ->first();

            return $result;
    }

}
