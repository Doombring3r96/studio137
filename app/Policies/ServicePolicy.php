<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;

class ServicePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isStaff() || $user->isCliente();
    }

    public function view(User $user, Service $service): bool
    {
        // Developer ve todo
        if ($user->isDeveloper()) return true;
        
        // CEO ve todo
        if ($user->isCEO()) return true;
        
        // Cliente solo ve sus servicios
        if ($user->isCliente()) {
            return $service->cliente_user_id === $user->id;
        }
        
        // Directores ven servicios relacionados con su área
        if ($user->isDirectorMarca()) {
            return $service->tipo === 'community_manager' || 
                   $this->isServiceAssignedToMyTeam($user, $service);
        }
        
        if ($user->isDirectorCreativo()) {
            return $service->tipo === 'identidad_corporativa' || 
                   $this->isServiceAssignedToMyTeam($user, $service);
        }
        
        // Empleados ven servicios asignados
        if ($user->isDesigner() || $user->isCM()) {
            return $this->isServiceAssignedToUser($user, $service);
        }
        
        return false;
    }

    public function create(User $user): bool
    {
        // Solo CEO, developer y directores pueden crear servicios
        return $user->isDeveloper() || $user->isCEO() || 
               $user->isDirectorMarca() || $user->isDirectorCreativo();
    }

    public function update(User $user, Service $service): bool
    {
        // Developer y CEO pueden editar cualquier servicio
        if ($user->isDeveloper() || $user->isCEO()) return true;
        
        // Directores editan servicios de su área
        if ($user->isDirectorMarca() && $service->tipo === 'community_manager') return true;
        if ($user->isDirectorCreativo() && $service->tipo === 'identidad_corporativa') return true;
        
        return false;
    }

    public function delete(User $user, Service $service): bool
    {
        // Solo developer y CEO pueden eliminar servicios
        return $user->isDeveloper() || $user->isCEO();
    }

    public function changeStatus(User $user, Service $service): bool
    {
        // Developer, CEO y directores pueden cambiar estado
        return $user->isDeveloper() || $user->isCEO() || 
               $user->isDirectorMarca() || $user->isDirectorCreativo();
    }

    private function isServiceAssignedToUser(User $user, Service $service): bool
    {
        return $service->assignments()
            ->where('assigned_to', $user->id)
            ->exists();
    }

    private function isServiceAssignedToMyTeam(User $director, Service $service): bool
    {
        $teamUserIds = $director->subordinates()->pluck('id');
        
        return $service->assignments()
            ->whereIn('assigned_to', $teamUserIds)
            ->exists();
    }
}