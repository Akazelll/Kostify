<?php

namespace App\Http\Controllers;

use App\Models\Penghuni;
use App\Models\Room;
use App\Models\Billing; // <-- Pastikan ini ditambahkan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon; // <-- Pastikan ini ditambahkan

class PenghuniController extends Controller
{
    /**
     * Menampilkan daftar semua penghuni.
     */
    public function index()
    {
        $penghunis = Penghuni::with('room')->get();
        $availableRooms = Room::where('status', 'available')->get();
        return view('penghunis.index', compact('penghunis', 'availableRooms'));
    }

    /**
     * Menyimpan data penghuni baru dan membuat tagihan pertama.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'identity_card' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'room_id' => 'required|exists:rooms,id',
        ]);

        // 1. Simpan file tanda pengenal
        $path = $request->file('identity_card')->store('public/identity_cards');

        // 2. Buat data penghuni baru
        $penghuni = Penghuni::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'identity_card_path' => $path,
            'room_id' => $request->room_id,
        ]);

        // 3. Update status kamar menjadi 'occupied'
        $room = Room::find($request->room_id);
        $room->status = 'occupied';
        $room->save();

        // 4. Buat tagihan pertama secara otomatis
        Billing::create([
            'penghuni_id' => $penghuni->id,
            'amount' => $room->price,
            'due_date' => Carbon::now()->addDays(7), // Jatuh tempo 7 hari dari sekarang
            'status' => 'unpaid',
        ]);

        return redirect()->route('penghunis.index')
            ->with('success', 'Penghuni baru berhasil ditambahkan dan tagihan pertama telah dibuat.');
    }

    /**
     * Memperbarui data penghuni.
     */
    public function update(Request $request, Penghuni $penghuni)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'identity_card' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'room_id' => 'required|exists:rooms,id',
        ]);

        $data = $request->only('name', 'phone_number', 'room_id');

        if ($request->hasFile('identity_card')) {
            Storage::delete($penghuni->identity_card_path);
            $path = $request->file('identity_card')->store('public/identity_cards');
            $data['identity_card_path'] = $path;
        }

        if ($penghuni->room_id != $request->room_id) {
            $oldRoom = Room::find($penghuni->room_id);
            if ($oldRoom) {
                $oldRoom->status = 'available';
                $oldRoom->save();
            }
            $newRoom = Room::find($request->room_id);
            $newRoom->status = 'occupied';
            $newRoom->save();
        }

        $penghuni->update($data);

        return redirect()->route('penghunis.index')
            ->with('success', 'Data penghuni berhasil diperbarui.');
    }

    /**
     * Menghapus data penghuni.
     */
    public function destroy(Penghuni $penghuni)
    {
        $room = Room::find($penghuni->room_id);
        if ($room) {
            $room->status = 'available';
            $room->save();
        }

        Storage::delete($penghuni->identity_card_path);
        $penghuni->delete(); // Tagihan terkait akan terhapus otomatis karena 'onDelete('cascade')'

        return redirect()->route('penghunis.index')
            ->with('success', 'Data penghuni berhasil dihapus.');
    }
}
