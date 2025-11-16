<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nombre',
        'email',
        'password_hash',
        'telefono',
        'role_id',
        'manager_id',
        'razon_social',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    // Relaciones
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function subordinates()
    {
        return $this->hasMany(User::class, 'manager_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function clientServices()
    {
        return $this->hasMany(Service::class, 'cliente_user_id');
    }

    public function salaries()
    {
        return $this->hasMany(Salary::class, 'empleado_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'cliente_user_id');
    }

    public function logos()
    {
        return $this->hasMany(Logo::class, 'autor_id');
    }

    public function publicationCalendars()
    {
        return $this->hasMany(PublicationCalendar::class, 'creador_id');
    }

    public function artworks()
    {
        return $this->hasMany(Artwork::class, 'autor_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'assigned_to');
    }

    public function assignedTasks()
    {
        return $this->hasMany(Assignment::class, 'assigned_by');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function audits()
    {
        return $this->hasMany(Audit::class);
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePorRol($query, $roleName)
    {
        return $query->whereHas('role', function($q) use ($roleName) {
            $q->where('name', $roleName);
        });
    }

    // Métodos de ayuda
    public function isDeveloper()
    {
        return $this->role->name === 'developer';
    }

    public function isCliente()
    {
        return $this->role->name === 'cliente';
    }

    public function isDesigner()
    {
        return $this->role->name === 'designer';
    }

    public function isCM()
    {
        return $this->role->name === 'cm';
    }

    public function isDirectorMarca()
    {
        return $this->role->name === 'director_marca';
    }

    public function isDirectorCreativo()
    {
        return $this->role->name === 'director_creativo';
    }

    public function isCEO()
    {
        return $this->role->name === 'ceo';
    }

    public function getNombreCompletoAttribute()
    {
        return $this->nombre;
    }

    public function tienePermiso($permiso)
    {
        // Implementar lógica de permisos según sea necesario
        return true;
    }

    // Para autenticación de Laravel
    public function getAuthPassword()
    {
        return $this->password_hash;
    }
}