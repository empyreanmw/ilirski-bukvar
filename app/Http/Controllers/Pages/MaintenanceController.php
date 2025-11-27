<?php
namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class MaintenanceController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function __invoke(): Response
    {
        return Inertia::render('Maintenance');
    }
}
