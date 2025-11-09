<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use SoftDeletes;

    protected $fillable = ["name"];

    protected $dates = ["deleted_at"];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, "position_id");
    }

    public function activeUsers(): HasMany
    {
        return $this->hasMany(User::class, "position_id")->whereNull("deleted_at");
    }
}
