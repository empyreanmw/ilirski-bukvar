<?php
namespace App\Jobs;
use App\Interfaces\InteractsWithDatabase;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Throwable;

abstract class BaseJob implements ShouldQueue, InteractsWithDatabase
{
    use Queueable, Dispatchable, InteractsWithQueue;
}
