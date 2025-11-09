<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use SoftDeletes;

    protected $fillable = ["user_id", "shift_id", "machine_id", "contruction_id", "stock", "eff", "overtime"];

    protected $dates = ["deleted_at"];

    protected $casts = [
        "stock" => "decimal:1",
        "integer" => "integer",
        "counter" => "decimal:2",
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class, "shift_id");
    }

    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class, "machine_id");
    }

    public function construction(): BelongsTo
    {
        return $this->belongsTo(Construction::class, "construction_id");
    }

    public function getDepartmentAttribute()
    {
        return $this->user->department ?? null;
    }

    public function getPositionAttribute()
    {
        return $this->user->position ?? null;
    }
}
