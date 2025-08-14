<?php

namespace App\Controllers\admin;
use App\Controllers\BaseController;

class DashboardAdminController extends BaseController
{
    public function __construct()
    {
        if (!session()->get('logged_in') && session()->get('rol')==1) {
            return redirect()->to('/auth');

        }
    }
    
    public function index()
    {
        $data = [
            'title' => 'Panel de Control | ITSI',
            'description' => 'Dashboard del sistema ITSI',
            'author' => 'Yamilex & Ana '
        ];

        return view('admin/dashboard/dashboardAdmin', $data);
    }
}
