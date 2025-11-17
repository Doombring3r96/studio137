<?php

namespace App\Observers;

use App\Models\Logo;
use App\Models\Notification;
use App\Models\User;

class LogoObserver
{
    public function created(Logo $logo): void
    {
        try {
            // Notificar al diseñador
            Notification::create([
                'user_id' => $logo->autor_id,
                'entidad_tipo' => 'logo',
                'entidad_id' => $logo->id,
                'tipo' => 'logo_assigned',
                'mensaje' => "Se te ha asignado la creación de un logo. Tipo: {$logo->tipo}",
                'created_by' => $logo->created_by ?? 1,
            ]);

            // Notificar al director creativo
            $directorCreativo = User::whereHas('role', function($q) {
                $q->where('name', 'director_creativo');
            })->first();

            if ($directorCreativo) {
                Notification::create([
                    'user_id' => $directorCreativo->id,
                    'entidad_tipo' => 'logo',
                    'entidad_id' => $logo->id,
                    'tipo' => 'logo_assigned',
                    'mensaje' => "Se ha asignado un nuevo logo a un diseñador de tu equipo",
                    'created_by' => $logo->created_by ?? 1,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error en LogoObserver created: ' . $e->getMessage());
        }
    }

    public function updated(Logo $logo): void
    {
        try {
            // Notificar cambio de estado
            if ($logo->isDirty('estado')) {
                $oldStatus = $logo->getOriginal('estado');
                $newStatus = $logo->estado;
                $this->notifyStatusChange($logo, $oldStatus, $newStatus);
            }

            // Notificar cuando el logo es enviado al cliente
            if ($logo->isDirty('estado') && $logo->estado === 'enviado') {
                $this->notifyLogoSentToClient($logo);
            }

            // Notificar cuando el logo es entregado
            if ($logo->isDirty('estado') && $logo->estado === 'entregado') {
                $this->notifyLogoDelivered($logo);
            }
        } catch (\Exception $e) {
            \Log::error('Error en LogoObserver updated: ' . $e->getMessage());
        }
    }

    private function notifyStatusChange(Logo $logo, string $oldStatus, string $newStatus): void
    {
        $message = "El logo ha cambiado de estado: {$oldStatus} → {$newStatus}";

        // Notificar al autor
        Notification::create([
            'user_id' => $logo->autor_id,
            'entidad_tipo' => 'logo',
            'entidad_id' => $logo->id,
            'tipo' => 'status_changed',
            'mensaje' => $message,
            'created_by' => $logo->updated_by ?? 1,
        ]);

        // Notificar al director creativo
        $directorCreativo = User::whereHas('role', function($q) {
            $q->where('name', 'director_creativo');
        })->first();

        if ($directorCreativo) {
            Notification::create([
                'user_id' => $directorCreativo->id,
                'entidad_tipo' => 'logo',
                'entidad_id' => $logo->id,
                'tipo' => 'status_changed',
                'mensaje' => "El logo asignado a tu equipo ha cambiado a: {$newStatus}",
                'created_by' => $logo->updated_by ?? 1,
            ]);
        }
    }

    private function notifyLogoSentToClient(Logo $logo): void
    {
        // Notificar al cliente
        if ($logo->service && $logo->service->cliente_user_id) {
            Notification::create([
                'user_id' => $logo->service->cliente_user_id,
                'entidad_tipo' => 'logo',
                'entidad_id' => $logo->id,
                'tipo' => 'logo_for_review',
                'mensaje' => "Tienes una nueva propuesta de logo para revisar",
                'created_by' => $logo->updated_by ?? 1,
            ]);
        }

        // Notificar al director de marca
        $directorMarca = User::whereHas('role', function($q) {
            $q->where('name', 'director_marca');
        })->first();

        if ($directorMarca) {
            Notification::create([
                'user_id' => $directorMarca->id,
                'entidad_tipo' => 'logo',
                'entidad_id' => $logo->id,
                'tipo' => 'logo_sent_to_client',
                'mensaje' => "Un logo ha sido enviado al cliente para revisión",
                'created_by' => $logo->updated_by ?? 1,
            ]);
        }
    }

    private function notifyLogoDelivered(Logo $logo): void
    {
        // Notificar al cliente
        if ($logo->service && $logo->service->cliente_user_id) {
            Notification::create([
                'user_id' => $logo->service->cliente_user_id,
                'entidad_tipo' => 'logo',
                'entidad_id' => $logo->id,
                'tipo' => 'logo_delivered',
                'mensaje' => "¡Tu logo ha sido entregado exitosamente!",
                'created_by' => $logo->updated_by ?? 1,
            ]);
        }

        // Notificar al CEO
        $ceo = User::whereHas('role', function($q) {
            $q->where('name', 'ceo');
        })->first();

        if ($ceo) {
            Notification::create([
                'user_id' => $ceo->id,
                'entidad_tipo' => 'logo',
                'entidad_id' => $logo->id,
                'tipo' => 'logo_delivered',
                'mensaje' => "Un proyecto de logo ha sido completado y entregado",
                'created_by' => $logo->updated_by ?? 1,
            ]);
        }
    }
}