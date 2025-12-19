<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penawaran extends Model
{
    protected $table = 'penawarans';
    
    protected $fillable = [
        'document_id', 'user_id', 'quotation_number', 'description', 'items',
        'subtotal', 'tax_percentage', 'tax_amount', 'total',
        'client_counter_offer', 'client_notes',
        'admin_counter_offer', 'admin_notes',
        'status'
    ];

    protected $casts = [
        'items' => 'array',
        'subtotal' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'client_counter_offer' => 'decimal:2',
        'admin_counter_offer' => 'decimal:2',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'quotation_id');
    }
}
