<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Exercise extends Model
{
    protected $table = 'gyakorlat';
    protected $primaryKey = 'gyakorlat_id';
    public $timestamps = false;
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'otthon_vegezheto' => 'boolean',
            'terapias' => 'boolean',
            'aktiv' => 'boolean',
            'nehezseg' => 'integer',
        ];
    }

    public function muscleGroups(): BelongsToMany
    {
        return $this->belongsToMany(
            MuscleGroup::class, 'gyakorlat_izomcsoport',
            'gyakorlat_id', 'izomcsoport_id'
        )->withPivot('elsodleges');
    }
}
