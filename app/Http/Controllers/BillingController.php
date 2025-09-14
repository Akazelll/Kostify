<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Penghuni;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BillingController extends Controller
{
    /**
     * Menampilkan daftar tagihan dengan filter bulan dan tahun.
     */
    public function index(Request $request)
    {
        // Ambil bulan dan tahun dari request, jika tidak ada, gunakan bulan dan tahun saat ini.
        $selectedYear = $request->input('year', Carbon::now()->year);
        $selectedMonth = $request->input('month', Carbon::now()->month);

        // Ambil data tagihan berdasarkan filter bulan dan tahun pada due_date
        $billings = Billing::with('penghuni.room')
            ->whereYear('due_date', $selectedYear)
            ->whereMonth('due_date', $selectedMonth)
            ->get();
        
        // Ambil data semua penghuni untuk form tambah tagihan
        $penghunis = Penghuni::with('room')->get();

        return view('billings.index', compact('billings', 'penghunis', 'selectedYear', 'selectedMonth'));
    }

    /**
     * Menyimpan tagihan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'penghuni_id' => 'required|exists:penghunis,id',
            'due_date' => 'required|date',
        ]);

        $penghuni = Penghuni::with('room')->find($request->penghuni_id);
        
        // Cek apakah sudah ada tagihan untuk penghuni ini di bulan yang sama
        $existingBilling = Billing::where('penghuni_id', $request->penghuni_id)
            ->whereYear('due_date', Carbon::parse($request->due_date)->year)
            ->whereMonth('due_date', Carbon::parse($request->due_date)->month)
            ->exists();

        if ($existingBilling) {
            return redirect()->route('billings.index')
                             ->withErrors(['msg' => 'Tagihan untuk penghuni ini di bulan tersebut sudah ada.']);
        }

        Billing::create([
            'penghuni_id' => $request->penghuni_id,
            'amount' => $penghuni->room->price, // Ambil harga dari kamar penghuni
            'due_date' => $request->due_date,
            'status' => 'unpaid',
        ]);

        return redirect()->route('billings.index')
                         ->with('success', 'Tagihan berhasil dibuat.');
    }

    /**
     * Menandai tagihan sebagai lunas.
     */
    public function markAsPaid(Billing $billing)
    {
        $billing->update([
            'status' => 'paid',
            'paid_at' => Carbon::now(),
        ]);

        return redirect()->route('billings.index')
                         ->with('success', 'Tagihan telah ditandai lunas.');
    }

    /**
     * Menghapus tagihan.
     */
    public function destroy(Billing $billing)
    {
        $billing->delete();

        return redirect()->route('billings.index')
                         ->with('success', 'Tagihan berhasil dihapus.');
    }
}