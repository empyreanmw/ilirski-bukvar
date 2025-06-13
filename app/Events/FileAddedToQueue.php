<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use App\Dtos\DownloadOutput as DownloadOutputDto;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class FileAddedToQueue implements ShouldBroadcastNow
{
    public function __construct(public readonly DownloadOutputDto $downloadOutput)
    {}

    public function broadcastOn(): array
    {
        return [
            new Channel('nativephp'),
        ];
    }
}
