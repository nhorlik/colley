<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkoutPlan extends Model
{
    protected $table = 'edzesterv';
    protected $primaryKey = 'edzesterv_id';
    public $timestamps = false;
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'kezdo_datum' => 'date',
            'aktiv' => 'boolean',
            'letrehozva' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'felhasznalo_id', 'felhasznalo_id');
    }

    public function days(): HasMany
    {
        return $this->hasMany(WorkoutDay::class, 'edzesterv_id', 'edzesterv_id')
            ->orderBy('nap_sorszam');
    }

    public function focusMuscleGroups(): BelongsToMany
    {
        return $this->belongsToMany(
            MuscleGroup::class, 'edzesterv_fokusz',
            'edzesterv_id', 'izomcsoport_id'
        )->withPivot('fo_fokusz');
    }
}
