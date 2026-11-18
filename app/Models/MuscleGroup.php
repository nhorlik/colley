<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MuscleGroup extends Model
{
    protected $table = 'izomcsoport';
    protected $primaryKey = 'izomcsoport_id';
    public $timestamps = false;
    protected $guarded = [];

    public function exercises(): BelongsToMany
    {
        return $this->belongsToMany(
            Exercise::class, 'gyakorlat_izomcsoport',
            'izomcsoport_id', 'gyakorlat_id'
        )->withPivot('elsodleges');
    }

    public function workoutPlans(): BelongsToMany
    {
        return $this->belongsToMany(
            WorkoutPlan::class, 'edzesterv_fokusz',
            'izomcsoport_id', 'edzesterv_id'
        )->withPivot('fo_fokusz');
    }
}
