<?php

namespace App\Http\Controllers\peneliti;

use App\Http\Controllers\Controller;
use App\Repositories\DashboardRepository;
use App\Services\peneliti\RankingService;

class PenelitiDashboardController extends Controller
{
    public function __construct(private DashboardRepository $dashboardrepo) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('peneliti.dashboard', $this->dashboardrepo->dashboardPeneliti());
    }
}
