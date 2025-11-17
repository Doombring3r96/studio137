<?php

namespace App\Observers;

use App\Models\PublicationCalendar;
use App\Models\Notification;
use App\Models\User;

class PublicationCalendarObserver
{
    public function created(PublicationCalendar $calendar): void
    {
        try {
            // Notificar al creador
            Notification::create([
                'user_id' => $calendar->creador_id,
                'entidad_tipo' => 'publication_calendar',
                'entidad_id' => $calendar->id,
                'tipo' => 'calendar_created',
                'mensaje' => "Se ha creado un nuevo calendario de publicación para el servicio",
                'created_by' => $calendar->created_by ?? 1,
            ]);

            // Notificar al director de marca
            $directorMarca = User::whereHas('role', function($q) {
                $q->where('name', 'director_marca');
            })->first();

            if ($directorMarca) {
                Notification::create([
                    'user_id' => $directorMarca->id,
                    'entidad_tipo' => 'publication_calendar',
                    'entidad_id' => $calendar->id,
                    'tipo' => 'calendar_created',
                    'mensaje' => "Se ha creado un nuevo calendario de publicación que requiere tu revisión",
                    'created_by' => $calendar->created_by ?? 1,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error en PublicationCalendarObserver created: ' . $e->getMessage());
        }
    }

    public function updated(PublicationCalendar $calendar): void
    {
        try {
            // Notificar cambio de estado
            if ($calendar->isDirty('estado')) {
                $oldStatus = $calendar->getOriginal('estado');
                $newStatus = $calendar->estado;
                $this->notifyStatusChange($calendar, $oldStatus, $newStatus);
            }

            // Notificar correcciones
            if ($calendar->isDirty('correcciones_count')) {
                $this->notifyCorrection($calendar);
            }
        } catch (\Exception $e) {
            \Log::error('Error en PublicationCalendarObserver updated: ' . $e->getMessage());
        }
    }

    private function notifyStatusChange(PublicationCalendar $calendar, string $oldStatus, string $newStatus): void
    {
        $message = "El calendario de publicación ha cambiado de estado: {$oldStatus} → {$newStatus}";

        // Notificar al creador
        Notification::create([
            'user_id' => $calendar->creador_id,
            'entidad_tipo' => 'publication_calendar',
            'entidad_id' => $calendar->id,
            'tipo' => 'status_changed',
            'mensaje' => $message,
            'created_by' => $calendar->updated_by ?? 1,
        ]);

        // Notificar al cliente
        if ($calendar->service && $calendar->service->cliente_user_id) {
            Notification::create([
                'user_id' => $calendar->service->cliente_user_id,
                'entidad_tipo' => 'publication_calendar',
                'entidad_id' => $calendar->id,
                'tipo' => 'status_changed',
                'mensaje' => "El estado del calendario de tu servicio ha cambiado a: {$newStatus}",
                'created_by' => $calendar->updated_by ?? 1,
            ]);
        }

        // Notificar al director correspondiente cuando se envía para revisión
        if ($newStatus === 'enviado') {
            $directorMarca = User::whereHas('role', function($q) {
                $q->where('name', 'director_marca');
            })->first();

            if ($directorMarca) {
                Notification::create([
                    'user_id' => $directorMarca->id,
                    'entidad_tipo' => 'publication_calendar',
                    'entidad_id' => $calendar->id,
                    'tipo' => 'needs_review',
                    'mensaje' => "Un calendario de publicación ha sido enviado para tu revisión",
                    'created_by' => $calendar->updated_by ?? 1,
                ]);
            }
        }
    }

    private function notifyCorrection(PublicationCalendar $calendar): void
    {
        $correccionesCount = $calendar->correcciones_count;
        $message = "Se ha registrado una corrección en el calendario. Total de correcciones: {$correccionesCount}/2";

        // Notificar al creador
        Notification::create([
            'user_id' => $calendar->creador_id,
            'entidad_tipo' => 'publication_calendar',
            'entidad_id' => $calendar->id,
            'tipo' => 'correction_added',
            'mensaje' => $message,
            'created_by' => $calendar->updated_by ?? 1,
        ]);

        // Notificar al director de marca sobre correcciones
        if ($correccionesCount > 0) {
            $directorMarca = User::whereHas('role', function($q) {
                $q->where('name', 'director_marca');
            })->first();

            if ($directorMarca) {
                Notification::create([
                    'user_id' => $directorMarca->id,
                    'entidad_tipo' => 'publication_calendar',
                    'entidad_id' => $calendar->id,
                    'tipo' => 'correction_notification',
                    'mensaje' => "El calendario tiene {$correccionesCount} corrección(es) registrada(s)",
                    'created_by' => $calendar->updated_by ?? 1,
                ]);
            }
        }
    }
}