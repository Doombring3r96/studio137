<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicationCalendar extends Model
{
    use HasFactory;

    protected $fillable = [
        'servicio_id',
        'creador_id',
        'fecha_ini',
        'fecha_fin',
        'estado',
        'correcciones_count',
        'ultimo_autor_correccion',
        'document_path',
        'fecha_entrega_real',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'fecha_ini' => 'date',
        'fecha_fin' => 'date',
        'fecha_entrega_real' => 'date',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, 'servicio_id');
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'creador_id');
    }

    public function ultimoAutorCorreccion()
    {
        return $this->belongsTo(User::class, 'ultimo_autor_correccion');
    }

    public function artworks()
    {
        return $this->hasMany(Artwork::class, 'calendar_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'calendar_id');
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
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeEntregados($query)
    {
        return $query->where('estado', 'entregado');
    }

    public function scopePorServicio($query, $servicioId)
    {
        return $query->where('servicio_id', $servicioId);
    }

    // Métodos de ayuda
    public function puedeSerCorregido()
    {
        return $this->correcciones_count < 2;
    }

    public function incrementarCorrecciones()
    {
        $this->increment('correcciones_count');
    }

    public function marcarComoEntregado()
    {
        $this->update([
            'estado' => 'entregado',
            'fecha_entrega_real' => now(),
        ]);
    }

    public function getDuracionSemanasAttribute()
    {
        return $this->fecha_ini->diffInWeeks($this->fecha_fin);
    }
}