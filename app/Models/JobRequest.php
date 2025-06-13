<?php

namespace App\Models;

use App\Enums\QueueJobRequestStatus;
use Illuminate\Database\Eloquent\Model;

class JobRequest extends Model
{
    protected $table = 'queue_job_requests';
    public $guarded = [];
    public $casts = [
        'job_status' => QueueJobRequestStatus::class,
    ];

    public function reference()
    {
        return $this->morphTo(__FUNCTION__, 'job_reference', 'job_reference_id');
    }
}
