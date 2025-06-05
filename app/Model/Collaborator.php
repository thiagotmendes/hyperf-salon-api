<?php

declare(strict_types=1);

namespace App\Model;



/**
 */
class Collaborator extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'Collaborator';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [
        'salon_id',
        'name',
        'email',
        'phone',
        'role',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [
        'id' => 'integer',
        'salon_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
