<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peraturan extends Model
{
    use HasFactory;

    protected $table = 'bpm_msdokumen';

    protected $primaryKey = "dok_id";

    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        "kdo_id",
        "men_id",
        "dok_judul",
        "dok_nomor_induk",
        "dok_tgl_berlaku",
        "dok_tgl_kadaluarsa",
        "dok_file",
        "dok_control",
        "dok_status_file",
        "dok_referensi",
        "dok_revisi",
        "dok_bagian_prosedur",
        "dok_tgl_unduh",
        "dok_status",
        "dok_created_by",
        "dok_created_date",
        "dok_modif_by",
        "dok_modif_date",
    ];
}
