<?php

declare(strict_types=1);

namespace App\Model;



/**
 */
class Appointment extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'Appointment';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [
        'salon_id',
        'collaborator_id',
        'client_name',
        'client_phone',
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
        'salon_id' => 'integer',
        'collaborator_id' => 'integer',
        'date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
