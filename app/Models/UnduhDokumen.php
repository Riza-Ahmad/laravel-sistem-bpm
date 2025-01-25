<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnduhDokumen extends Model
{
    protected $table = 'bpm_trunduhdokumen';

    protected $primaryKey = 'udo_id';

    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'dok_id',
        'rol_id',
        'kry_id',
        'rol_deskripsi',
        'udo_status_baca',
        'udo_jenis_penyalinan',
        'udo_tgl_unduh',
        'udo_status',
        'dok_ref',
        'udo_created_by',
        'udo_created_date',
        'udo_modif_by',
        'udo_modif_date',
    ];
}
