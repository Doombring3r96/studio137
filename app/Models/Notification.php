<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'entidad_tipo',
        'entidad_id',
        'tipo',
        'mensaje',
        'is_read',
        'delivery_push',
        'created_by',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'delivery_push' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeNoLeidas($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeLeidas($query)
    {
        return $query->where('is_read', true);
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
    public function marcarComoLeida()
    {
        $this->update(['is_read' => true]);
    }

    public function marcarComoNoLeida()
    {
        $this->update(['is_read' => false]);
    }

    public function getEntidadAttribute()
    {
        if (!$this->entidad_tipo || !$this->entidad_id) {
            return null;
        }

        return app($this->entidad_tipo)->find($this->entidad_id);
    }
}