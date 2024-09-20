<?php

namespace App\Models\OTransaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beli extends Model
{
    use HasFactory;

    protected $table = 'beli';
    protected $primaryKey = 'NO_ID';
    public $timestamps = false;

    protected $fillable = 
    [
        "NO_BUKTI","TGL", "NO_PO", "PER","KODES", "NAMAS", "TRUCK", "SOPIR", "ALAMAT", "KOTA", 
		"FLAG", "GOL", "KD_BRG", "NA_BRG", "SATUAN", "KG", "HARGA", "TOTAL", "RPTOTAL",
	    "QTY", "LAIN", "SISA", "NOTES", "ACNOA", "NACNOA",  "RPLAIN", "RPSISA", "RPRATE", "RPHARGA", 
		"ACNOB","NACNOB", "BACNO", "BNAMA", "NO_BANK","AJU", "BL", "EMKL", "JCONT", "SCONT", "TOTAL_KG", 
		"USRNM", "TG_SMP", "TGL_BL",
         "CETAK", "TGL_CETAK", "created_by", "updated_by",
         "deleted_by", "TRUCK","BA" , "BP", "BAG", "KA", "REF", "RP", "JUMREF", "KGII", "POTII", "KGBAG", 
		 "POTONG", "TOTAL", "GUDANG", "KAPAL"
    ];
}