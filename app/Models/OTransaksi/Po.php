<?php

namespace App\Models\OTransaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


//ganti 1
class Po extends Model
{
    use HasFactory;

// ganti 2
    protected $table = 'po';
    protected $primaryKey = 'NO_ID';
    public $timestamps = true;

//ganti 3
    protected $fillable = 
    [
        "NO_PO", "TGL", "PER","KODES", "NAMAS", "ALAMAT", "KOTA", "FLAG", "GOL", "KD_BRG", "NA_BRG", 
		"QTY", "KG", "HARGA", "SISA",  "TOTAL", "NOTES", "USRNM", 
        "TG_SMP","created_by", "updated_by", "KODEC", "NAMAC", "ALAMAT2", "KOTA2", "NO_SO", "TGL_SO", "KD_BRG2", "NA_BRG2", 
		"deleted_by", "JTEMPO", "RPHARGA", "RPTOTAL"
    ];
}
