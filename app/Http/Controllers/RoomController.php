<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::all();
        return view('rooms.index', compact('rooms'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'room_number' => 'required|string|unique:rooms|max:255',
            'type' => 'required|in:Reguler,Eksklusif', 
            'price' => 'required|numeric',
            'status' => 'required|in:available,occupied',
        ]);

        Room::create($request->all());

        return redirect()->route('rooms.index')
            ->with('success', 'Kamar berhasil ditambahkan.');
    }
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'room_number' => 'required|string|max:255|unique:rooms,room_number,' . $room->id,
            'type' => 'required|in:Reguler,Eksklusif', 
            'price' => 'required|numeric',
            'status' => 'required|in:available,occupied',
        ]);

        $room->update($request->all());

        return redirect()->route('rooms.index')
            ->with('success', 'Data kamar berhasil diperbarui.');
    }
    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()->route('rooms.index')
            ->with('success', 'Data kamar berhasil dihapus.');
    }
}
