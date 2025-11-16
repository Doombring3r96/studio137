<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manual extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo_id',
        'servicio_id',
        'manual_path',
        'created_by',
        'updated_by',
    ];

    public function logo()
    {
        return $this->belongsTo(Logo::class, 'logo_id');
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

    // Métodos de ayuda
    public function getRutaManualAttribute()
    {
        return asset('storage/' . $this->manual_path);
    }
}