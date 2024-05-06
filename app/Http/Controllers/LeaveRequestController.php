<?php

namespace App\Http\Controllers;

use App\Http\Resources\LeaveRequestResource;
use App\Jobs\SendEmail;
use App\Repositories\LeaveRequest\LeaveRequestInterface;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    use ApiResponseTrait;

    private LeaveRequestInterface $repository;

    public function __construct(LeaveRequestInterface $repository)
    {
        $this->repository = $repository;
    }

    public function all(Request $request)
    {
        $filters = $request->only(['search_text', 'leave_type', 'status', 'start_date', 'end_date', 'per_page', 'page']);
        // dd($filters);
        $leaveRequests = $this->repository->all($filters);

        $items          = LeaveRequestResource::collection($leaveRequests);
        $paginationData = $leaveRequests->toArray();

        $paginator = new LengthAwarePaginator(
            $items,
            $paginationData['total'],
            $paginationData['per_page'],
            $paginationData['current_page'],
            ['path' => $request->url()]
        );

        return $this->ResponseSuccess($paginator, null, 'leave request', 200, 'success');

    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date'   => 'required|date',
            'reason'     => 'required|string',
        ]);

        $userId = Auth::id();

        $validatedData['user_id'] = $userId;
        $validatedData['status']  = 'Pending';

        $leaveRequest = $this->repository->save($validatedData);

        // try {
        $emailData = [
            'subject'    => "Leave Request Submitted",
            'email_body' => view('mails.leave_request_submitted', compact('leaveRequest'))->render(),
            'to'         => [$leaveRequest->user->email],
        ];

        SendEmail::dispatch($emailData);
        // } catch (\Throwable $th) {
        //     // Handle email sending error
        //     // You can log the error or take any other appropriate action
        // }

        return $this->ResponseSuccess(new LeaveRequestResource($leaveRequest), null, 'leaveRequest created successfully', 201, 'success');
    }

    public function manage(Request $request, $id)
    {
        $validatedData = $request->validate([
            'action'        => 'required|string|in:Approved,Rejected',
            'admin_comment' => 'required|string',
        ]);

        $leaveRequest = $this->repository->manage($validatedData, $id);

        if (!$leaveRequest) {
            return $this->ResponseError('Leave request not found', null, 'Leave request not found', 404);
        }

        $data = new LeaveRequestResource($leaveRequest);
        try {
            $emailData = [
                'subject'    => "Leave Request Verification",
                'email_body' => view('mails.leave_request_verification', compact('leaveRequest'))->render(),
                'to'         => [$leaveRequest->email],
            ];

            SendEmail::dispatch($emailData);
        } catch (\Throwable $th) {
            //hi
        }

        return $this->ResponseSuccess(new LeaveRequestResource($leaveRequest), null, 'Leave request updated successfully', 200);
    }

    public function leaveRequestsCounts()
    {
        $data = $this->repository->leaveRequestsCounts();

        return $this->ResponseSuccess($data, null, 'leaveRequest created successfully', 200, 'success');

    }

}
