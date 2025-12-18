<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'invoice_code',
        'service_name',
        'amount',
        'status',
        'due_date',
        'paid_at',
        'payment_method'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}