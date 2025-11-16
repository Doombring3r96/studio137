<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_user_id',
        'servicio_id',
        'cantidad',
        'fecha_pago',
        'tipo',
        'comprobante_path',
        'estado',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'fecha_pago' => 'datetime',
        'cantidad' => 'decimal:2',
    ];

    public function cliente()
    {
        return $this->belongsTo(User::class, 'cliente_user_id');
    }

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

    // Scopes
    public function scopePagados($query)
    {
        return $query->where('estado', 'pagado');
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeMensuales($query)
    {
        return $query->where('tipo', 'mensual');
    }

    public function scopeUnicos($query)
    {
        return $query->where('tipo', 'unico');
    }

    // Métodos de ayuda
    public function getCantidadFormateadaAttribute()
    {
        return '$ ' . number_format($this->cantidad, 2);
    }

    public function marcarComoPagado()
    {
        $this->update(['estado' => 'pagado']);
    }
}