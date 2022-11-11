<?php

namespace App\Models;

use Yajra\Auditable\AuditableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory, AuditableTrait;

    protected $table = 'company';

    protected $primaryKey = 'kode_company';

    protected $connection = 'mysql2';

    public $incrementing = false;

    protected $fillable = [
    	'kode_company',
        'nama_company',
        'alamat',
        'telp',
        'npwp',
        'status',
        'kode_lokasi',
    ];
}
