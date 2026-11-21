<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Trait HasUuidV7
 *
 * Auto-generate UUIDv7 sebagai primary key saat model dibuat.
 * UUIDv7 bersifat time-ordered, jadi lebih ramah untuk index database
 * dibanding UUIDv4 biasa (mirip seperti auto-increment tapi tetap UUID).
 *
 * Cara pakai:
 * 1. Pastikan kolom primary key di migration bertipe uuid/char(36), contoh:
 *      $table->uuid('id')->primary();
 * 2. Pakai trait ini di model:
 *      use App\Traits\HasUuidV7;
 *      class Ticket extends Model
 *      {
 *          use HasUuidV7;
 *      }
 */
trait HasUuidV7
{
    /**
     * Boot trait, hook ke event "creating" model.
     */
    protected static function bootHasUuidV7(): void
    {
        static::creating(function (Model $model) {
            $keyName = $model->getKeyName();

            if (empty($model->{$keyName})) {
                $model->{$keyName} = (string) Str::uuid7();
            }
        });
    }

    /**
     * Primary key bertipe string, bukan auto-increment.
     */
    public function initializeHasUuidV7(): void
    {
        $this->keyType = 'string';
        $this->incrementing = false;
    }
}