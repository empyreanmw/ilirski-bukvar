<?php

namespace App\Enums;

enum QueueJobRequestStatus: string
{
    case DONE = 'done';
    case CANCELLED = 'cancelled';
    case FAILED = 'failed';
    case QUEUED = 'queued';
    case IN_PROGRESS = 'in_progress';
}
