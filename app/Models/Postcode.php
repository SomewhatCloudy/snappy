<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Postcode extends Model
{
    use HasFactory;

    protected $fillable = [
        'postcode',
        'place_name',
        'county',
        'country',
        'type',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }

    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->whereFullText(['postcode', 'place_name'], $search);
    }
}
