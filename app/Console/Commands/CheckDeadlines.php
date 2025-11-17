<?php

namespace App\Console\Commands;

use App\Models\Assignment;
use App\Models\Service;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Console\Command;

class CheckDeadlines extends Command
{
    protected $signature = 'check:deadlines';
    protected $description = 'Check for approaching deadlines and send notifications';

    public function handle(): void
    {
        $this->info('Checking for approaching deadlines...');

        // Verificar servicios próximos a vencer (3 días)
        $services = Service::where('estado', 'activo')
            ->where('fecha_fin', '<=', now()->addDays(3))
            ->where('fecha_fin', '>', now())
            ->get();

        foreach ($services as $service) {
            $this->notifyServiceDeadline($service);
            $this->info("Notified for service: {$service->id}");
        }

        // Verificar asignaciones próximas a vencer (2 días)
        $assignments = Assignment::where('estado', '!=', 'completado')
            ->where('fecha_fin', '<=', now()->addDays(2))
            ->where('fecha_fin', '>', now())
            ->get();

        foreach ($assignments as $assignment) {
            $this->notifyAssignmentDeadline($assignment);
            $this->info("Notified for assignment: {$assignment->id}");
        }

        // Verificar tareas vencidas
        $overdueAssignments = Assignment::where('estado', '!=', 'completado')
            ->where('fecha_fin', '<', now())
            ->get();

        foreach ($overdueAssignments as $assignment) {
            $this->notifyOverdueAssignment($assignment);
            $this->info("Notified for overdue assignment: {$assignment->id}");
        }

        $this->info('Deadline check completed. ' . 
                   "Services: {$services->count()}, " .
                   "Assignments: {$assignments->count()}, " .
                   "Overdue: {$overdueAssignments->count()}");
    }

    private function notifyServiceDeadline(Service $service): void
    {
        $daysLeft = now()->diffInDays($service->fecha_fin);
        
        // Notificar al cliente
        Notification::create([
            'user_id' => $service->cliente_user_id,
            'entidad_tipo' => 'services',
            'entidad_id' => $service->id,
            'tipo' => 'deadline_warning',
            'mensaje' => "Tu servicio {$service->tipo} vence en {$daysLeft} día(s). Fecha límite: {$service->fecha_fin->format('d/m/Y')}",
            'created_by' => 1, // Sistema
        ]);

        // Notificar al CEO
        $ceo = User::whereHas('role', function($q) {
            $q->where('name', 'ceo');
        })->first();

        if ($ceo) {
            Notification::create([
                'user_id' => $ceo->id,
                'entidad_tipo' => 'services',
                'entidad_id' => $service->id,
                'tipo' => 'deadline_warning',
                'mensaje' => "Servicio {$service->tipo} del cliente {$service->cliente->nombre} vence en {$daysLeft} día(s)",
                'created_by' => 1,
            ]);
        }

        // Notificar al director correspondiente
        if ($service->tipo === 'identidad_corporativa') {
            $director = User::whereHas('role', function($q) {
                $q->where('name', 'director_creativo');
            })->first();
        } else {
            $director = User::whereHas('role', function($q) {
                $q->where('name', 'director_marca');
            })->first();
        }

        if ($director) {
            Notification::create([
                'user_id' => $director->id,
                'entidad_tipo' => 'services',
                'entidad_id' => $service->id,
                'tipo' => 'deadline_warning',
                'mensaje' => "Servicio {$service->tipo} asignado a tu área vence en {$daysLeft} día(s)",
                'created_by' => 1,
            ]);
        }
    }

    private function notifyAssignmentDeadline(Assignment $assignment): void
    {
        $daysLeft = now()->diffInDays($assignment->fecha_fin);
        
        // Notificar al empleado asignado
        Notification::create([
            'user_id' => $assignment->assigned_to,
            'entidad_tipo' => 'assignments',
            'entidad_id' => $assignment->id,
            'tipo' => 'deadline_warning',
            'mensaje' => "Tu tarea '{$assignment->tarea_tipo}' vence en {$daysLeft} día(s). Fecha límite: {$assignment->fecha_fin->format('d/m/Y')}",
            'created_by' => 1,
        ]);

        // Notificar al manager del empleado
        if ($assignment->assignedTo->manager) {
            Notification::create([
                'user_id' => $assignment->assignedTo->manager->id,
                'entidad_tipo' => 'assignments',
                'entidad_id' => $assignment->id,
                'tipo' => 'deadline_warning',
                'mensaje' => "Tarea '{$assignment->tarea_tipo}' asignada a {$assignment->assignedTo->nombre} vence en {$daysLeft} día(s)",
                'created_by' => 1,
            ]);
        }
    }

    private function notifyOverdueAssignment(Assignment $assignment): void
    {
        $daysOverdue = now()->diffInDays($assignment->fecha_fin);
        
        // Notificar al empleado asignado
        Notification::create([
            'user_id' => $assignment->assigned_to,
            'entidad_tipo' => 'assignments',
            'entidad_id' => $assignment->id,
            'tipo' => 'deadline_overdue',
            'mensaje' => "⚠️ TU TAREA ESTÁ VENCIDA: '{$assignment->tarea_tipo}' lleva {$daysOverdue} día(s) de retraso",
            'created_by' => 1,
        ]);

        // Notificar al manager del empleado
        if ($assignment->assignedTo->manager) {
            Notification::create([
                'user_id' => $assignment->assignedTo->manager->id,
                'entidad_tipo' => 'assignments',
                'entidad_id' => $assignment->id,
                'tipo' => 'deadline_overdue',
                'mensaje' => "⚠️ TAREA VENCIDA: '{$assignment->tarea_tipo}' asignada a {$assignment->assignedTo->nombre} lleva {$daysOverdue} día(s) de retraso",
                'created_by' => 1,
            ]);
        }

        // Notificar al CEO si lleva más de 2 días de retraso
        if ($daysOverdue > 2) {
            $ceo = User::whereHas('role', function($q) {
                $q->where('name', 'ceo');
            })->first();

            if ($ceo) {
                Notification::create([
                    'user_id' => $ceo->id,
                    'entidad_tipo' => 'assignments',
                    'entidad_id' => $assignment->id,
                    'tipo' => 'deadline_critical',
                    'mensaje' => "🚨 TAREA CRÍTICA: '{$assignment->tarea_tipo}' de {$assignment->assignedTo->nombre} lleva {$daysOverdue} días de retraso",
                    'created_by' => 1,
                ]);
            }
        }
    }
}