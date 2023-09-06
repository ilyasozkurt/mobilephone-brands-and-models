<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'devices';

    /**
     * @var string[]
     */
    protected $fillable = [
        'url_hash',
        'url',
        'brand_id',
        'name',
        'picture',
        'released_at',
        'body',
        'os',
        'storage',
        'display_size',
        'display_resolution',
        'camera_pixels',
        'video_pixels',
        'ram',
        'chipset',
        'battery_size',
        'battery_type',
        'specifications',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'specifications' => 'array'
    ];

    /**
     * Returns devices of brand.
     *
     * @return BelongsTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo('App\Models\Device');
    }
}
