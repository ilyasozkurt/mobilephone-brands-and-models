<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'brands';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name'
    ];

    /**
     * Returns devices of brand.
     *
     * @return HasMany
     */
    public function devices(): HasMany
    {
        return $this->hasMany('App\Models\Device');
    }
}
