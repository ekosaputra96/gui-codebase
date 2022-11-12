<?php

namespace App\Models;

use App\Models\Company;
use App\Models\MasterLokasi;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'kode_company',
        'kode_lokasi'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // adminlte additional function
    public function adminlte_image()
    {
        return 'https://picsum.photos/300/300';
    }

    public function adminlte_desc()
    {
        return Company::select('nama_company')->where('kode_company', auth()->user()->kode_company)->first()->nama_company . ' ('.MasterLokasi::select('nama_lokasi')->where('kode_lokasi', auth()->user()->kode_lokasi)->first()->nama_lokasi.')';
    }

    public function adminlte_profile_url()
    {
        return 'admin/settings';
    }

    public function company(){
        return $this->belongsTo(Company::class, 'kode_company');
    }
}
