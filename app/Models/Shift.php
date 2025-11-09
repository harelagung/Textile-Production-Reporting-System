<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shift extends Model
{
    use SoftDeletes;

    protected $fillable = ["name", "start_time", "end_time", "duration_hours"];

    protected $dates = ["deleted_at"];

    protected $casts = [
        "start_time" => "datetime:H:i:s",
        "end_time" => "datetime:H:i:s",
        "duration_hours" => "integer",
    ];

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, "shift_id");
    }

    public function activeReports(): HasMany
    {
        return $this->hasMany(Report::class, "shift_id")->whereNull("deleted_at");
    }
}
