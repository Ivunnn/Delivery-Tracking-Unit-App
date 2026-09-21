<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function admin()
    {
        return view('pages.admin.dashboard', [
            'title' => 'Dashboard Admin',
            'user'  => Auth::user(),
        ]);
    }

    public function driver()
    {
        return view('pages.driver.dashboard', [
            'title' => 'Dashboard Driver',
            'user'  => Auth::user(),
        ]);
    }

    public function customer()
    {
        return view('pages.customer.dashboard', [
            'title' => 'Dashboard Customer',
            'user'  => Auth::user(),
        ]);
    }
}