<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo',
        'fecha_ini',
        'fecha_fin',
        'costo',
        'cliente_user_id',
        'estado',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'fecha_ini' => 'date',
        'fecha_fin' => 'date',
        'costo' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relaciones
    public function cliente()
    {
        return $this->belongsTo(User::class, 'cliente_user_id');
    }

    public function brief()
    {
        return $this->hasOne(Brief::class, 'servicio_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'servicio_id');
    }

    public function logos()
    {
        return $this->hasMany(Logo::class, 'servicio_id');
    }

    public function publicationCalendars()
    {
        return $this->hasMany(PublicationCalendar::class, 'servicio_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'servicio_id');
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
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    // Métodos de ayuda
    public function getDuracionDiasAttribute()
    {
        return $this->fecha_ini->diffInDays($this->fecha_fin);
    }

    public function getCostoFormateadoAttribute()
    {
        return '$ ' . number_format($this->costo, 2);
    }
}