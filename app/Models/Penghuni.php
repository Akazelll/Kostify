<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penghuni extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone_number',
        'identity_card_path',
        'room_id',
    ];

    /**
     * Mendapatkan data kamar yang ditempati penghuni.
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}