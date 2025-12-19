<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'user_id', 'nama', 'alamat', 'telepon', 'jasa',
        'kota', 'prov', 'negara', 'kodepos',
        'file_path', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function penawaran()
    {
        return $this->hasOne(Penawaran::class);
    }
}
