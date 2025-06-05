<?php

declare(strict_types=1);

namespace App\Model;



/**
 */
class Slot extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'Slot';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [
        'collaborator_id',
        'date',
        'start_time',
        'end_time',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [
        'id' => 'integer',
        'collaborator_id' => 'integer',
        'date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
