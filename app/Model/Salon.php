<?php

declare(strict_types=1);

namespace App\Model;



/**
 */
class Salon extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'Salon';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [
        'name',
        'address',
        'phone',
        'owner_id',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [
        'id' => 'integer',
        'owner_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
