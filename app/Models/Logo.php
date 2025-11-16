<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logo extends Model
{
    use HasFactory;

    protected $fillable = [
        'servicio_id',
        'autor_id',
        'tipo',
        'img_path',
        'estado',
        'version',
        'descripcion',
        'manual_id',
        'created_by',
        'updated_by',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, 'servicio_id');
    }

    public function autor()
    {
        return $this->belongsTo(User::class, 'autor_id');
    }

    public function manual()
    {
        return $this->belongsTo(Manual::class, 'manual_id');
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

    public function scopePorAutor($query, $autorId)
    {
        return $query->where('autor_id', $autorId);
    }

    // Métodos de ayuda
    public function marcarComoEnviado()
    {
        $this->update(['estado' => 'enviado']);
    }

    public function marcarComoEntregado()
    {
        $this->update(['estado' => 'entregado']);
    }

    public function requiereCorreccion()
    {
        return in_array($this->estado, ['rechazado', 'en_revision']);
    }

    public function getRutaImagenAttribute()
    {
        return asset('storage/' . $this->img_path);
    }
}