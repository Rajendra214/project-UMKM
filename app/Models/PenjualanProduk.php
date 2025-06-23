<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanProduk extends Model
{
    use HasFactory;
    
    protected $fillable = ['produk_id', 'bulan', 'tahun', 'jumlah_penjualan'];
    
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
