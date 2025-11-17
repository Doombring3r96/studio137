<?php

namespace App\Observers;

use App\Models\Brief;
use App\Models\Notification;
use App\Models\User;

class BriefObserver
{
    public function created(Brief $brief): void
    {
        try {
            // Notificar al director correspondiente según el tipo de servicio
            $directorType = $brief->service->tipo === 'identidad_corporativa' ? 'director_creativo' : 'director_marca';
            
            $director = User::whereHas('role', function($q) use ($directorType) {
                $q->where('name', $directorType);
            })->first();

            if ($director) {
                Notification::create([
                    'user_id' => $director->id,
                    'entidad_tipo' => 'brief',
                    'entidad_id' => $brief->id,
                    'tipo' => 'brief_completed',
                    'mensaje' => "Se ha completado un nuevo brief para un servicio de {$brief->service->tipo}",
                    'created_by' => $brief->created_by ?? 1,
                ]);
            }

            // Notificar al CEO
            $ceo = User::whereHas('role', function($q) {
                $q->where('name', 'ceo');
            })->first();

            if ($ceo) {
                Notification::create([
                    'user_id' => $ceo->id,
                    'entidad_tipo' => 'brief',
                    'entidad_id' => $brief->id,
                    'tipo' => 'brief_completed',
                    'mensaje' => "Un cliente ha completado el brief para un nuevo servicio",
                    'created_by' => $brief->created_by ?? 1,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error en BriefObserver created: ' . $e->getMessage());
        }
    }

    public function updated(Brief $brief): void
    {
        try {
            // Notificar actualización del brief
            if ($brief->isDirty('contenido_json')) {
                $this->notifyContentUpdate($brief);
            }

            // Notificar cuando se sube un documento
            if ($brief->isDirty('document_path') && $brief->document_path) {
                $this->notifyDocumentUpload($brief);
            }
        } catch (\Exception $e) {
            \Log::error('Error en BriefObserver updated: ' . $e->getMessage());
        }
    }

    private function notifyContentUpdate(Brief $brief): void
    {
        // Notificar al director correspondiente
        $directorType = $brief->service->tipo === 'identidad_corporativa' ? 'director_creativo' : 'director_marca';
        
        $director = User::whereHas('role', function($q) use ($directorType) {
            $q->where('name', $directorType);
        })->first();

        if ($director) {
            Notification::create([
                'user_id' => $director->id,
                'entidad_tipo' => 'brief',
                'entidad_id' => $brief->id,
                'tipo' => 'brief_updated',
                'mensaje' => "El brief del servicio ha sido actualizado con nueva información",
                'created_by' => $brief->updated_by ?? 1,
            ]);
        }
    }

    private function notifyDocumentUpload(Brief $brief): void
    {
        // Notificar al cliente
        Notification::create([
            'user_id' => $brief->service->cliente_user_id,
            'entidad_tipo' => 'brief',
            'entidad_id' => $brief->id,
            'tipo' => 'document_uploaded',
            'mensaje' => "Se ha subido un documento al brief de tu servicio",
            'created_by' => $brief->updated_by ?? 1,
        ]);
    }
}