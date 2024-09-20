<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// ganti 1
class Cust extends Model
{
    use HasFactory;

// ganti 2

    protected $table = 'cust';
    protected $primaryKey = 'NO_ID';
    public $timestamps = false;

// ganti 3
    protected $fillable = 
    [
        "KODEC", "NAMAC", "ALAMAT", "KOTA", 'GOL', "TELPON1", "FAX", "HP", "KONTAK", "NPWP", "EMAIL", "KET", "BANK", "BANK_CAB", "BANK_KOTA", "BANK_NAMA", "BANK_REK", "LIM", "HARI", "USRNM", "TG_SMP"
    ];
}
