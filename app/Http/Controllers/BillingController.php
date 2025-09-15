<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Payment; // <-- Pastikan ini ditambahkan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class BillingController extends Controller
{
    public function index(Request $request)
    {
        $selectedYear = $request->input('year', Carbon::now()->year);
        $selectedMonth = $request->input('month', Carbon::now()->month);

        $billingsQuery = Billing::with('penghuni.room', 'payments')
            ->whereYear('due_date', $selectedYear)
            ->whereMonth('due_date', $selectedMonth);

        $lateBillings = $billingsQuery->where('status', '!=', 'paid')->get();
        $lateFeeAmount = 50000; // Tentukan jumlah denda di sini

        foreach ($lateBillings as $billing) {
            $dueDate = Carbon::parse($billing->due_date);

            // Cek jika sudah lewat jatuh tempo lebih dari 7 hari dan denda belum ditambahkan
            if ($dueDate->addDays(7)->isPast() && $billing->late_fee == 0) {

                // Tambahkan denda ke sisa tagihan
                $billing->balance += $lateFeeAmount;
                $billing->late_fee = $lateFeeAmount; // Catat jumlah denda
                $billing->save();
            }
        }
        // --- AKHIR LOGIKA DENDA ---

        // Ambil ulang data setelah update denda
        $billings = $billingsQuery->orderBy('due_date', 'asc')->get();

        return view('billings.index', compact('billings', 'selectedYear', 'selectedMonth'));
    }

    /**
     * Menyimpan data pembayaran baru (termasuk cicilan) untuk sebuah tagihan.
     */
    public function storePayment(Request $request, Billing $billing)
    {
        // Validasi input pembayaran
        $request->validate([
            'amount_paid' => 'required|numeric|min:1|max:' . $billing->balance,
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'payment_date' => 'required|date',
        ]);

        // 1. Simpan file bukti pembayaran
        $path = $request->file('payment_proof')->store('public/payment_proofs');

        // 2. Catat pembayaran baru di tabel 'payments'
        $billing->payments()->create([
            'amount_paid' => $request->amount_paid,
            'payment_date' => $request->payment_date,
            'payment_proof_path' => $path,
        ]);

        // 3. Hitung ulang sisa tagihan (balance)
        $newBalance = $billing->balance - $request->amount_paid;

        // 4. Tentukan status baru berdasarkan sisa tagihan
        $newStatus = 'partial'; // Status default adalah cicilan/partial
        if ($newBalance <= 0) {
            $newBalance = 0;
            $newStatus = 'paid';
        }

        // 5. Perbarui tagihan induk
        $billing->update([
            'balance' => $newBalance,
            'status' => $newStatus,
            'paid_at' => ($newStatus === 'paid') ? Carbon::now() : null,
        ]);

        return redirect()->route('billings.index')->with('success', 'Pembayaran sebesar Rp ' . number_format($request->amount_paid) . ' berhasil dicatat.');
    }

    /**
     * Menghapus tagihan beserta semua riwayat pembayarannya.
     */
    public function destroy(Billing $billing)
    {
        // Hapus semua file bukti bayar dari storage
        foreach ($billing->payments as $payment) {
            if ($payment->payment_proof_path) {
                Storage::delete($payment->payment_proof_path);
            }
        }

        // Hapus data tagihan dari database (akan menghapus payments terkait karena onDelete('cascade'))
        $billing->delete();

        return redirect()->route('billings.index')->with('success', 'Tagihan berhasil dihapus.');
    }

    /**
     * Membuat dan mengunduh invoice dalam format PDF.
     */
    public function generateInvoice(Billing $billing)
    {
        if ($billing->status !== 'paid') {
            return redirect()->route('billings.index')->withErrors(['msg' => 'Invoice hanya bisa dicetak untuk tagihan yang sudah lunas.']);
        }

        $data = [
            'billing' => $billing->load('payments'), // Muat riwayat pembayaran untuk ditampilkan di invoice
            'tanggal_cetak' => Carbon::now()->translatedFormat('d F Y'),
        ];

        $pdf = Pdf::loadView('billings.invoice', $data);

        $fileName = 'INVOICE-' . $billing->invoice_number . '.pdf';

        return $pdf->download($fileName);
    }
}
