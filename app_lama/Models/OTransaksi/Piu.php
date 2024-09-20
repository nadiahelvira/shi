<?php

namespace App\Models\OTransaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


//ganti 1
class Piu extends Model
{
    use HasFactory;

// ganti 2
    protected $table = 'piu';
    protected $primaryKey = 'NO_ID';
    public $timestamps = false;

//ganti 3
    protected $fillable = 
    [
       "NO_BUKTI", "TGL", "NO_SO", "PER","KODEC", "NAMAC",  "TOTAL", "BAYAR", "GOL", "BACNO", "BNAMA", "FLAG", "NO_BANK",
	   "USRNM", "TG_SMP", "NOTES","created_by", "updated_by",
       "deleted_by"
    ];
}
