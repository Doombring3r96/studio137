<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brief extends Model
{
    use HasFactory;

    protected $fillable = [
        'servicio_id',
        'tipo',
        'document_path',
        'fecha_recibida',
        'contenido_json',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'fecha_recibida' => 'datetime',
        'contenido_json' => 'array',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, 'servicio_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Métodos de ayuda
    public function getContenidoAttribute()
    {
        return $this->contenido_json ?? [];
    }

    public function setContenidoAttribute($value)
    {
        $this->attributes['contenido_json'] = json_encode($value);
    }
}