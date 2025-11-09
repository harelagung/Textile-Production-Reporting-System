<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Construction extends Model
{
    use SoftDeletes;

    protected $fillable = ["name", "stock"];

    protected $dates = ["deleted_at"];

    protected $casts = [
        "stock" => "decimal:1",
    ];

    public function machines(): HasMany
    {
        return $this->hasMany(Machine::class, "construction_id");
    }

    public function activeMachines(): HasMany
    {
        return $this->hasMany(Machine::class, "construction_id")->whereNull("deleted_at");
    }

    public function getActiveMachinesCountAttribute(): int
    {
        return $this->activeMachines()->count();
    }

    /**
     * Get machine codes as array
     */
    public function getMachineCodesAttribute(): array
    {
        return $this->activeMachines()->pluck("kd_mach")->toArray();
    }

    /**
     * Check if construction is currently in production
     */
    public function getIsInProductionAttribute(): bool
    {
        return $this->activeMachines()->exists();
    }

    /**
     * Get production status text
     */
    public function getProductionStatusAttribute(): string
    {
        return $this->is_in_production ? "Sedang Produksi" : "Tidak Aktif";
    }
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, "construction_id");
    }
    public function activeReport(): HasMany
    {
        return $this->hasMany(Report::class, "construction_id")->whereNull("deleted_at");
    }
}
