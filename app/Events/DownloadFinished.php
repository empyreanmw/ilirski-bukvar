<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

class DownloadFinished implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets;
    /**
     * Create a new event instance.
     */
    public function __construct(public readonly array $content, public readonly string $url, public readonly float $size)
    {
        //
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('nativephp'),
        ];
    }
}
