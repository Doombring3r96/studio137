<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Assignment;
use App\Models\Payment;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $stats = [];

        if ($user->isDeveloper() || $user->isCEO()) {
            // Dashboard para administradores
            $stats = [
                'total_services' => Service::count(),
                'active_services' => Service::where('estado', 'activo')->count(),
                'pending_assignments' => Assignment::where('estado', 'pendiente')->count(),
                'pending_payments' => Payment::where('estado', 'pendiente')->count(),
                'total_users' => User::count(),
            ];
        } elseif ($user->isCliente()) {
            // Dashboard para clientes
            $stats = [
                'my_services' => Service::where('cliente_user_id', $user->id)->count(),
                'active_services' => Service::where('cliente_user_id', $user->id)
                    ->where('estado', 'activo')->count(),
                'pending_payments' => Payment::where('cliente_user_id', $user->id)
                    ->where('estado', 'pendiente')->count(),
            ];
        } elseif ($user->isDesigner() || $user->isCM()) {
            // Dashboard para empleados
            $stats = [
                'my_assignments' => Assignment::where('assigned_to', $user->id)->count(),
                'pending_assignments' => Assignment::where('assigned_to', $user->id)
                    ->where('estado', 'pendiente')->count(),
                'completed_assignments' => Assignment::where('assigned_to', $user->id)
                    ->where('estado', 'completado')->count(),
            ];
        }

        // Notificaciones no leídas
        $unreadNotifications = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        // Asignaciones pendientes del usuario
        $myPendingAssignments = Assignment::with(['service'])
            ->where('assigned_to', $user->id)
            ->where('estado', 'pendiente')
            ->orderBy('fecha_fin')
            ->limit(5)
            ->get();

        // Últimas notificaciones
        $recentNotifications = Notification::with(['createdBy'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'stats', 
            'unreadNotifications', 
            'myPendingAssignments', 
            'recentNotifications'
        ));
    }

    public function stats(Request $request)
    {
        // Endpoint para estadísticas en tiempo real (para gráficos)
        if ($request->ajax()) {
            $user = auth()->user();

            if ($user->isDeveloper() || $user->isCEO()) {
                $servicesByType = Service::groupBy('tipo')
                    ->selectRaw('tipo, count(*) as count')
                    ->get();

                $assignmentsByStatus = Assignment::groupBy('estado')
                    ->selectRaw('estado, count(*) as count')
                    ->get();

                return response()->json([
                    'services_by_type' => $servicesByType,
                    'assignments_by_status' => $assignmentsByStatus,
                ]);
            }
        }

        return response()->json([]);
    }
}