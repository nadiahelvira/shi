<?php

namespace App\Models\OTransaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


//ganti 1
class So extends Model
{
    use HasFactory;

// ganti 2
    protected $table = 'so';
    protected $primaryKey = 'NO_ID';
    public $timestamps = false;

//ganti 3
    protected $fillable = 
    [
        "NO_SO", "TGL", "PER","KODEC", "NAMAC", "ALAMAT", "KOTA", "NO_ORDER", "FLAG", "GOL", "KD_BRG", "NA_BRG", "QTY", "KG", 'SISA', "HARGA", "TOTAL", "NOTES", 
        "FLAG", "GOL", "USRNM", "TG_SMP", "KODET", "NAMAT","created_by", "updated_by", "NO_ORDER", "PO",
		"deleted_by", "RPRATE", "RPHARGA", "RPTOTAL", "JTEMPO"
    ];
}
