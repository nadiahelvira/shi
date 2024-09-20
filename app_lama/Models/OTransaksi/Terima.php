<?php

namespace App\Models\OTransaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


//ganti 1
class Terima extends Model
{
    use HasFactory;

// ganti 2
    protected $table = 'terima';
    protected $primaryKey = 'NO_ID';
    public $timestamps = false;

//ganti 3
    protected $fillable = 
    [
        "NO_BUKTI","TGL", "NO_BL", "NO_PO", "PER","KODES", "NAMAS", "TRUCK", "SOPIR", "ALAMAT", "KOTA", 
		"FLAG", "GOL", "KD_BRG", "NA_BRG", "SATUAN", "NO_CONT", "SEAL", "EMKL","GUDANG", "T_TRUCK", "T_CONT",
		"QTY", "KG1", "HARGA", "TOTAL","ACNOA", "NACNOA", "ACNOB", "NACNOB",    "KG", "RPRATE", "RPHARGA", "RPTOTAL","SUSUT", "RPSISA",  "NOTES", "AJU","BL",
		"USRNM", "TG_SMP", "created_by", "updated_by",
		"deleted_by"
	
    ];
}
