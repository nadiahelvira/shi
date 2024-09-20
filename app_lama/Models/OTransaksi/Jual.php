<?php

namespace App\Models\OTransaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


//ganti 1
class Jual extends Model
{
    use HasFactory;

// ganti 2
    protected $table = 'jual';
    protected $primaryKey = 'NO_ID';
    public $timestamps = false;

//ganti 3
    protected $fillable = 
    [
        "NO_BUKTI","TGL", "NO_SO", "PER","KODEC", "NAMAC", "TRUCK", "SOPIR", "ALAMAT", "KOTA", "FLAG", 
		"GOL", "KD_BRG", "NA_BRG", "SATUAN", "DPP", "PPN","GDG",
		"QTY", "KG", "HARGA", "TOTAL", "RPTOTAL", "RPSISA",  "NOTES", "ACNOA","NACNOA","ACNOB","NACNOB", 
	    "BACNO", "BNAMA", "NO_BANK", "USRNM", "TG_SMP","created_by", "updated_by",
		"deleted_by"
       
    ];
}
