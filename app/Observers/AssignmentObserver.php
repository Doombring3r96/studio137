<?php

namespace App\Observers;

use App\Models\Assignment;
use App\Models\Notification;

class AssignmentObserver
{
    /**
     * Handle the Assignment "created" event.
     */
    public function created(Assignment $assignment): void
    {
        try {
            // Notificar al empleado asignado
            Notification::create([
                'user_id' => $assignment->assigned_to,
                'entidad_tipo' => 'assignment',
                'entidad_id' => $assignment->id,
                'tipo' => 'assignment_created',
                'mensaje' => "Se te ha asignado una nueva tarea: '{$assignment->tarea_tipo}'. Fecha límite: {$assignment->fecha_fin->format('d/m/Y')}",
                'created_by' => $assignment->created_by ?? 1,
            ]);

            // Notificar al manager si existe
            if ($assignment->assignedTo && $assignment->assignedTo->manager) {
                Notification::create([
                    'user_id' => $assignment->assignedTo->manager->id,
                    'entidad_tipo' => 'assignment',
                    'entidad_id' => $assignment->id,
                    'tipo' => 'assignment_created',
                    'mensaje' => "Has asignado la tarea '{$assignment->tarea_tipo}' a {$assignment->assignedTo->nombre}",
                    'created_by' => $assignment->created_by ?? 1,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error en AssignmentObserver created: ' . $e->getMessage());
        }
    }

    /**
     * Handle the Assignment "updated" event.
     */
    public function updated(Assignment $assignment): void
    {
        try {
            // Notificar cambio de estado
            if ($assignment->isDirty('estado')) {
                $this->notifyStatusChange($assignment);
            }
        } catch (\Exception $e) {
            \Log::error('Error en AssignmentObserver updated: ' . $e->getMessage());
        }
    }

    private function notifyStatusChange(Assignment $assignment): void
    {
        $message = "La tarea '{$assignment->tarea_tipo}' ha cambiado a estado: {$assignment->estado}";

        // Notificar al empleado
        Notification::create([
            'user_id' => $assignment->assigned_to,
            'entidad_tipo' => 'assignment',
            'entidad_id' => $assignment->id,
            'tipo' => 'status_changed',
            'mensaje' => $message,
            'created_by' => $assignment->updated_by ?? 1,
        ]);

        // Notificar al manager si existe
        if ($assignment->assignedTo && $assignment->assignedTo->manager) {
            Notification::create([
                'user_id' => $assignment->assignedTo->manager->id,
                'entidad_tipo' => 'assignment',
                'entidad_id' => $assignment->id,
                'tipo' => 'status_changed',
                'mensaje' => "{$assignment->assignedTo->nombre} ha cambiado el estado de '{$assignment->tarea_tipo}' a {$assignment->estado}",
                'created_by' => $assignment->updated_by ?? 1,
            ]);
        }
    }
}