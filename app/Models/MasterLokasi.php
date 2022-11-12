<?php

namespace App\Models;

use Yajra\Auditable\AuditableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterLokasi extends Model
{
    use HasFactory, AuditableTrait;

    protected $connection = 'mysql2';

    protected $table = 'master_lokasi';

    protected $primaryKey = 'kode_lokasi';
    
    public $incrementing = false;

    protected $fillable = [
        'kode_lokasi',
        'nama_lokasi',
        'alamat',
        'status',
    ];
}
