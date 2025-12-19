<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'quotation_id', 'user_id', 'invoice_number', 'items',
        'subtotal', 'tax_amount', 'total',
        'status', 'issue_date', 'due_date'
    ];

    protected $casts = [
        'items' => 'array',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'issue_date' => 'date',
        'due_date' => 'date',
    ];

    public function penawaran()
    {
        return $this->belongsTo(Penawaran::class, 'quotation_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
