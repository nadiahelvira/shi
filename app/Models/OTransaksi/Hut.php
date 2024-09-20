<?php

namespace App\Models\OTransaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


//ganti 1
class Hut extends Model
{
    use HasFactory;

// ganti 2
    protected $table = 'hut';
    protected $primaryKey = 'NO_ID';
    public $timestamps = false;

//ganti 3
    protected $fillable = 
    [
        "NO_BUKTI", "POSTED", "TGL", "BAYAR", "NO_PO", "NOTES", "PER","KODES", "NAMAS", "BACNO", "BNAMA","GOL", "FLAG", "NO_BANK",
		"USRNM", "TG_SMP", "CETAK", "TGL_CETAK", "created_by", "updated_by",
		"deleted_by", "TOTAL", "LAIN"
    ];
}
