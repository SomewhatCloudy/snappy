<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'postcode_id',
        'delivery_radius_km',
    ];

    protected $casts = [
        'delivery_radius_km' => 'decimal:2',
    ];

    public function postcode(): BelongsTo
    {
        return $this->belongsTo(Postcode::class);
    }
}
