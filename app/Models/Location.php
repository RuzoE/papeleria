<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'zone',
        'module',
        'shelf',
        'level',
        'position',
        'description',
    ];

    public function getFullCodeAttribute(): string
    {
        $parts = array_filter([
            $this->zone ? 'Zona ' . $this->zone : null,
            $this->module ? 'Mód. ' . $this->module : null,
            $this->shelf ? 'Est. ' . $this->shelf : null,
            $this->level ? 'Niv. ' . $this->level : null,
            $this->position ? 'Pos. ' . $this->position : null,
        ]);

        return implode(' - ', $parts) ?: 'Sin especificar';
    }
}
