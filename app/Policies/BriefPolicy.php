<?php

namespace App\Policies;

use App\Models\Brief;
use App\Models\User;

class BriefPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isStaff();
    }

    public function view(User $user, Brief $brief): bool
    {
        // Developer ve todo
        if ($user->isDeveloper()) return true;
        
        // CEO ve todo
        if ($user->isCEO()) return true;
        
        // Cliente ve solo sus briefs
        if ($user->isCliente()) {
            return $brief->service->cliente_user_id === $user->id;
        }
        
        // Directores ven briefs de su área
        if ($user->isDirectorMarca() && $brief->service->tipo === 'community_manager') return true;
        if ($user->isDirectorCreativo() && $brief->service->tipo === 'identidad_corporativa') return true;
        
        return false;
    }

    public function create(User $user): bool
    {
        // Clientes pueden crear briefs para sus servicios
        // Staff también puede crear briefs
        return $user->isCliente() || $user->isStaff();
    }

    public function update(User $user, Brief $brief): bool
    {
        // Developer y CEO pueden editar cualquier brief
        if ($user->isDeveloper() || $user->isCEO()) return true;
        
        // Cliente puede editar sus briefs si el servicio está activo
        if ($user->isCliente() && $brief->service->cliente_user_id === $user->id) {
            return $brief->service->estado === 'activo';
        }
        
        // Directores editan briefs de su área
        if ($user->isDirectorMarca() && $brief->service->tipo === 'community_manager') return true;
        if ($user->isDirectorCreativo() && $brief->service->tipo === 'identidad_corporativa') return true;
        
        return false;
    }
}