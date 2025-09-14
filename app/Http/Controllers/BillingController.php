<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BillingController extends Controller
{
    public function index(Request $request)
    {
        $selectedYear = $request->input('year', Carbon::now()->year);
        $selectedMonth = $request->input('month', Carbon::now()->month);

        $billings = Billing::with('penghuni.room')
            ->whereYear('due_date', $selectedYear)
            ->whereMonth('due_date', $selectedMonth)
            ->orderBy('due_date', 'asc')
            ->get();

        return view('billings.index', compact('billings', 'selectedYear', 'selectedMonth'));
    }

    // Menggantikan method 'markAsPaid' yang lama
    public function submitPayment(Request $request, Billing $billing)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Hapus bukti bayar lama jika ada
        if ($billing->payment_proof_path) {
            Storage::delete($billing->payment_proof_path);
        }

        // Simpan bukti bayar baru
        $path = $request->file('payment_proof')->store('public/payment_proofs');

        // Perbarui status tagihan
        $billing->update([
            'status' => 'paid',
            'paid_at' => Carbon::now(),
            'payment_proof_path' => $path,
        ]);

        return redirect()->route('billings.index', ['month' => $request->query('month'), 'year' => $request->query('year')])
                         ->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    public function destroy(Billing $billing)
    {
        if ($billing->payment_proof_path) {
            Storage::delete($billing->payment_proof_path);
        }
        $billing->delete();

        return redirect()->route('billings.index')->with('success', 'Tagihan berhasil dihapus.');
    }
}