<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Dtos\DownloadOutput as DownloadOutputDto;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

class DownloadOutput implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets;
    public function __construct(public DownloadOutputDto $downloadOutput)
    {}

    public function broadcastOn(): array
    {
        return [
            new Channel('nativephp'),
        ];
    }
}
