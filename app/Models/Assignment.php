<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'servicio_id',
        'tarea_tipo',
        'assigned_to',
        'assigned_by',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, 'servicio_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeCompletados($query)
    {
        return $query->where('estado', 'completado');
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeEnProceso($query)
    {
        return $query->where('estado', 'en_proceso');
    }

    public function scopePorUsuario($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeVencidos($query)
    {
        return $query->where('fecha_fin', '<', now())->where('estado', '!=', 'completado');
    }

    // Métodos de ayuda
    public function marcarComoCompletado()
    {
        $this->update(['estado' => 'completado']);
    }

    public function marcarComoEnProceso()
    {
        $this->update(['estado' => 'en_proceso']);
    }

    public function estaVencido()
    {
        return $this->fecha_fin->isPast() && $this->estado !== 'completado';
    }

    public function getDiasRestantesAttribute()
    {
        return now()->diffInDays($this->fecha_fin, false);
    }

    public function getProgresoAttribute()
    {
        $totalDias = $this->fecha_inicio->diffInDays($this->fecha_fin);
        $diasTranscurridos = $this->fecha_inicio->diffInDays(now());
        
        return min(100, max(0, ($diasTranscurridos / $totalDias) * 100));
    }
}