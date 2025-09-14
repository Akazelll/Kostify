<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Penghuni;
use App\Models\Billing;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'kamar_tersedia' => Room::where('status', 'available')->count(),
            'kamar_terisi' => Room::where('status', 'occupied')->count(),
            'total_penghuni' => Penghuni::count(),
            'tagihan_belum_lunas' => Billing::where('status', 'unpaid')
                ->whereMonth('due_date', Carbon::now()->month)
                ->sum('amount'),
        ];

        $penghuni_terbaru = Penghuni::with('room')->latest()->take(5)->get();

        return view('dashboard.index', compact('stats', 'penghuni_terbaru'));
    }
}
