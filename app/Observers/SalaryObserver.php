<?php

namespace App\Observers;

use App\Models\Salary;
use App\Models\Notification;

class SalaryObserver
{
    public function created(Salary $salary): void
    {
        try {
            // Notificar al empleado
            Notification::create([
                'user_id' => $salary->empleado_id,
                'entidad_tipo' => 'salary',
                'entidad_id' => $salary->id,
                'tipo' => 'salary_registered',
                'mensaje' => "Se ha registrado tu sueldo de {$salary->cantidad} para pago el {$salary->fecha_pago->format('d/m/Y')}",
                'created_by' => $salary->created_by ?? 1,
            ]);

            // Notificar al pagador
            if ($salary->pagador_id !== $salary->empleado_id) {
                Notification::create([
                    'user_id' => $salary->pagador_id,
                    'entidad_tipo' => 'salary',
                    'entidad_id' => $salary->id,
                    'tipo' => 'salary_registered',
                    'mensaje' => "Has registrado un sueldo de {$salary->cantidad} para {$salary->empleado->nombre}",
                    'created_by' => $salary->created_by ?? 1,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error en SalaryObserver created: ' . $e->getMessage());
        }
    }

    public function updated(Salary $salary): void
    {
        try {
            // Notificar cambio de estado
            if ($salary->isDirty('estado')) {
                $oldStatus = $salary->getOriginal('estado');
                $newStatus = $salary->estado;
                $this->notifyStatusChange($salary, $oldStatus, $newStatus);
            }

            // Notificar específicamente cuando se marca como pagado
            if ($salary->isDirty('estado') && $salary->estado === 'pagado') {
                $this->notifySalaryPaid($salary);
            }
        } catch (\Exception $e) {
            \Log::error('Error en SalaryObserver updated: ' . $e->getMessage());
        }
    }

    private function notifyStatusChange(Salary $salary, string $oldStatus, string $newStatus): void
    {
        $message = "El estado de tu sueldo ha cambiado de {$oldStatus} a {$newStatus}";

        // Notificar al empleado
        Notification::create([
            'user_id' => $salary->empleado_id,
            'entidad_tipo' => 'salary',
            'entidad_id' => $salary->id,
            'tipo' => 'salary_status_changed',
            'mensaje' => $message,
            'created_by' => $salary->updated_by ?? 1,
        ]);
    }

    private function notifySalaryPaid(Salary $salary): void
    {
        // Notificar al empleado
        Notification::create([
            'user_id' => $salary->empleado_id,
            'entidad_tipo' => 'salary',
            'entidad_id' => $salary->id,
            'tipo' => 'salary_paid',
            'mensaje' => "¡Tu sueldo de {$salary->cantidad} ha sido pagado!",
            'created_by' => $salary->updated_by ?? 1,
        ]);

        // Notificar al pagador
        if ($salary->pagador_id !== $salary->empleado_id) {
            Notification::create([
                'user_id' => $salary->pagador_id,
                'entidad_tipo' => 'salary',
                'entidad_id' => $salary->id,
                'tipo' => 'salary_paid',
                'mensaje' => "Has marcado como pagado el sueldo de {$salary->empleado->nombre}",
                'created_by' => $salary->updated_by ?? 1,
            ]);
        }
    }
}