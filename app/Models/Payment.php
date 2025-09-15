<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'billing_id',
        'amount_paid',
        'payment_date',
        'payment_proof_path',
    ];
}
