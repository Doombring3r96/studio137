<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'entity_type',
        'entity_id',
        'action',
        'changes',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scopes
    public function scopePorEntidad($query, $entityType, $entityId = null)
    {
        $query = $query->where('entity_type', $entityType);
        
        if ($entityId) {
            $query = $query->where('entity_id', $entityId);
        }
        
        return $query;
    }

    public function scopePorUsuario($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeRecientes($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Métodos de ayuda
    public function getEntityAttribute()
    {
        return app($this->entity_type)->find($this->entity_id);
    }

    public function getCambiosFormateadosAttribute()
    {
        if (!$this->changes) {
            return [];
        }

        return $this->changes;
    }
}