<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class WorkoutDay extends Model
{
    protected $table = 'edzesnap';
    protected $primaryKey = 'edzesnap_id';
    public $timestamps = false;
    protected $guarded = [];

    public function workoutPlan(): BelongsTo
    {
        return $this->belongsTo(WorkoutPlan::class, 'edzesterv_id', 'edzesterv_id');
    }

    public function exercises(): BelongsToMany
    {
        return $this->belongsToMany(
            Exercise::class, 'edzesnap_gyakorlat',
            'edzesnap_id', 'gyakorlat_id'
        )->withPivot([
            'edzesnap_gyakorlat_id', 'sorrend', 'szett_szam',
            'ism_min', 'ism_max', 'ido_mp', 'piheno_mp',
        ])->orderByPivot('sorrend');
    }
}
