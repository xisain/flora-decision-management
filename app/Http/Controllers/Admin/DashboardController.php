<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\DashboardRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class DashboardController extends Controller
{
    public function __construct(private DashboardRepository $dashboardrepo){}
    public function index()
    {
        return view('admin.dashboard',$this->dashboardrepo->dashboardAdmin());
    }
}
