<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Di sini Anda bisa menambahkan logika untuk mengambil data statistik
        // seperti pada file `dashboard/page.tsx`
        return view('dashboard.index');
    }
}