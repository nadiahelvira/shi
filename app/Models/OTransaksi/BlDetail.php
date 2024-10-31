<?php

namespace App\Models\OTransaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlDetail extends Model
{
    use HasFactory;

    protected $table = 'bld';
    protected $primaryKey = 'NO_ID';
    public $timestamps = false;

    protected $fillable =
    [
        "REC", "NO_BUKTI", "ID",  "KG_BL",
		"FLAG", "PER", "NO_CONT", "SEAL", "created_by", "updated_by",
		"deleted_by", "EMKL", "KG", "KET", "TGL_DTG"
    ];
}
