<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artwork extends Model
{
    use HasFactory;

    protected $fillable = [
        'calendar_id',
        'autor_id',
        'fecha_pub',
        'titulo',
        'cuerpo',
        'copy',
        'descripcion',
        'img_path',
        'tipo',
        'estado',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'fecha_pub' => 'date',
    ];

    public function calendar()
    {
        return $this->belongsTo(PublicationCalendar::class, 'calendar_id');
    }

    public function autor()
    {
        return $this->belongsTo(User::class, 'autor_id');
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
    public function scopeAprobados($query)
    {
        return $query->where('estado', 'aprobado');
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopePorCalendar($query, $calendarId)
    {
        return $query->where('calendar_id', $calendarId);
    }

    public function scopePorAutor($query, $autorId)
    {
        return $query->where('autor_id', $autorId);
    }

    // Métodos de ayuda
    public function marcarComoAprobado()
    {
        $this->update(['estado' => 'aprobado']);
    }

    public function marcarComoRechazado()
    {
        $this->update(['estado' => 'rechazado']);
    }

    public function getRutaImagenAttribute()
    {
        return $this->img_path ? asset('storage/' . $this->img_path) : null;
    }

    public function esParaVenta()
    {
        return $this->tipo === 'venta';
    }
}