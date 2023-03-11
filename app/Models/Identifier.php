<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Identifier extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value',
    ];

    /**
     * Get the owning identifiable models.
     */
    public function identifiable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get HTML of associated QR code.
     */
    public function QrCode(): string
    {
        return QrCode::style('round')
                     ->eyeColor(0, 220, 38, 38, 0, 0, 0)
                     ->size(200)
                     ->generate('hydrofon:'.$this->value);
    }
}
