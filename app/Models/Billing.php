<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use HasFactory;

    protected $fillable = [
        'penghuni_id',
        'amount',
        'due_date',
        'paid_at',
        'status',
        'payment_proof_path',
    ];

    /**
     * Mendapatkan data penghuni yang terkait dengan tagihan ini.
     */
    public function penghuni()
    {
        return $this->belongsTo(Penghuni::class);
    }
}