<?php

namespace App\Observers;

use App\Models\Service;
use App\Models\Notification;
use App\Models\User;

class ServiceObserver
{
    /**
     * Handle the Service "created" event.
     */
    public function created(Service $service): void
    {
        try {
            // Notificar al cliente sobre nuevo servicio
            Notification::create([
                'user_id' => $service->cliente_user_id,
                'entidad_tipo' => 'service',
                'entidad_id' => $service->id,
                'tipo' => 'service_created',
                'mensaje' => "Se ha creado un nuevo servicio de {$service->tipo} para tu empresa. Fecha de entrega: {$service->fecha_fin->format('d/m/Y')}",
                'created_by' => $service->created_by ?? 1,
            ]);

            // Notificar al CEO si existe
            $ceo = User::whereHas('role', function($q) {
                $q->where('name', 'ceo');
            })->first();

            if ($ceo) {
                Notification::create([
                    'user_id' => $ceo->id,
                    'entidad_tipo' => 'service',
                    'entidad_id' => $service->id,
                    'tipo' => 'service_created',
                    'mensaje' => "Nuevo servicio {$service->tipo} creado para el cliente",
                    'created_by' => $service->created_by ?? 1,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error en ServiceObserver created: ' . $e->getMessage());
        }
    }

    /**
     * Handle the Service "updated" event.
     */
    public function updated(Service $service): void
    {
        try {
            // Notificar cambio de estado
            if ($service->isDirty('estado')) {
                $oldStatus = $service->getOriginal('estado');
                $newStatus = $service->estado;
                $this->notifyStatusChange($service, $oldStatus, $newStatus);
            }

            // Notificar cambio de fecha de entrega
            if ($service->isDirty('fecha_fin')) {
                $oldDate = $service->getOriginal('fecha_fin');
                $newDate = $service->fecha_fin;
                $this->notifyDateChange($service, $oldDate, $newDate);
            }
        } catch (\Exception $e) {
            \Log::error('Error en ServiceObserver updated: ' . $e->getMessage());
        }
    }

    private function notifyStatusChange(Service $service, string $oldStatus, string $newStatus): void
    {
        $message = "El estado de tu servicio {$service->tipo} ha cambiado de {$oldStatus} a {$newStatus}";

        // Notificar al cliente
        Notification::create([
            'user_id' => $service->cliente_user_id,
            'entidad_tipo' => 'service',
            'entidad_id' => $service->id,
            'tipo' => 'status_changed',
            'mensaje' => $message,
            'created_by' => $service->updated_by ?? 1,
        ]);
    }

    private function notifyDateChange(Service $service, $oldDate, $newDate): void
    {
        $oldDateFormatted = $oldDate instanceof \Carbon\Carbon ? $oldDate->format('d/m/Y') : $oldDate;
        $newDateFormatted = $newDate instanceof \Carbon\Carbon ? $newDate->format('d/m/Y') : $newDate;
        
        $message = "La fecha de entrega de tu servicio {$service->tipo} ha cambiado de {$oldDateFormatted} a {$newDateFormatted}";

        Notification::create([
            'user_id' => $service->cliente_user_id,
            'entidad_tipo' => 'service',
            'entidad_id' => $service->id,
            'tipo' => 'date_changed',
            'mensaje' => $message,
            'created_by' => $service->updated_by ?? 1,
        ]);
    }
}