<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'felhasznalo';
    protected $primaryKey = 'felhasznalo_id';
    public $timestamps = false;

    protected $fillable = [
        'email', 'jelszo_hash', 'nev', 'cel', 'szint', 'testalkat', 'nem',
        'szuletesi_ev', 'magassag_cm', 'testsuly_kg', 'heti_edzesszam',
        'otthon_edz', 'rehab_mod', 'orvosi_engedely',
        'fajdalom_hozzajarulas', 'gdpr_hozzajarulas',
    ];

    protected $hidden = ['jelszo_hash'];

    protected function casts(): array
    {
        return [
            'jelszo_hash' => 'hashed',
            'szuletesi_ev' => 'integer',
            'magassag_cm' => 'integer',
            'testsuly_kg' => 'decimal:2',
            'heti_edzesszam' => 'integer',
            'otthon_edz' => 'boolean',
            'rehab_mod' => 'boolean',
            'orvosi_engedely' => 'datetime',
            'fajdalom_hozzajarulas' => 'datetime',
            'gdpr_hozzajarulas' => 'datetime',
            'regisztralt' => 'datetime',
        ];
    }

    public function getAuthPassword(): string
    {
        return $this->jelszo_hash;
    }

    public function getAuthPasswordName(): string
    {
        return 'jelszo_hash';
    }
}
