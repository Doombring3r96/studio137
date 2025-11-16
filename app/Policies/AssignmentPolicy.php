<?php

namespace App\Policies;

use App\Models\Assignment;
use App\Models\User;

class AssignmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isStaff();
    }

    public function view(User $user, Assignment $assignment): bool
    {
        // Developer ve todo
        if ($user->isDeveloper()) return true;
        
        // CEO ve todo
        if ($user->isCEO()) return true;
        
        // Empleado ve sus asignaciones
        if ($assignment->assigned_to === $user->id) return true;
        
        // Director ve asignaciones de su equipo
        if ($user->isDirectorMarca() || $user->isDirectorCreativo()) {
            $teamUserIds = $user->subordinates()->pluck('id');
            return $teamUserIds->contains($assignment->assigned_to);
        }
        
        return false;
    }

    public function create(User $user): bool
    {
        // Solo directores, CEO y developer pueden crear asignaciones
        return $user->isDeveloper() || $user->isCEO() || 
               $user->isDirectorMarca() || $user->isDirectorCreativo();
    }

    public function update(User $user, Assignment $assignment): bool
    {
        // Developer y CEO pueden editar cualquier asignación
        if ($user->isDeveloper() || $user->isCEO()) return true;
        
        // Empleado puede marcar como completado/en proceso
        if ($assignment->assigned_to === $user->id) {
            return in_array(request()->input('estado'), ['en_proceso', 'completado']);
        }
        
        // Director edita asignaciones de su equipo
        if ($user->isDirectorMarca() || $user->isDirectorCreativo()) {
            $teamUserIds = $user->subordinates()->pluck('id');
            return $teamUserIds->contains($assignment->assigned_to);
        }
        
        return false;
    }

    public function complete(User $user, Assignment $assignment): bool
    {
        // Solo el empleado asignado puede completar
        return $assignment->assigned_to === $user->id;
    }
}