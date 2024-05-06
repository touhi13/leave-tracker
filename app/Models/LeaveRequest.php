<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'leave_type', // Add leave_type to the fillable attributes
        'start_date',
        'end_date',
        'reason',
        'status',
        'admin_comment'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
