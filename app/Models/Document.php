<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama',
        'alamat',
        'telepon',
        'jasa',
        'kota',
        'prov',
        'negara',
        'kodepos',
        'file_path',
        'status',         
        'client_budget',   
        'director_offer'  
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}