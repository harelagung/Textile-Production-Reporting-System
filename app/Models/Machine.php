<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Machine extends Model
{
    use SoftDeletes;

    protected $fillable = ["kd_mach", "construction_id"];

    protected $dates = ["deleted_at"];

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, "machine_id");
    }

    public function activeReports(): HasMany
    {
        return $this->hasMany(Report::class, "machine_id")->whereNull("deleted_at");
    }

    public function construction(): BelongsTo
    {
        return $this->belongsTo(Construction::class, "construction_id");
    }
}
