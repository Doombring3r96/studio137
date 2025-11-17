<?php

namespace App\Http\Controllers\Cm;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\PublicationCalendar;
use App\Models\Salary;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        
        // Obtener las asignaciones del CM
        $assignments = Assignment::where('assigned_to', $user->id)
            ->with(['service'])
            ->where('estado', 'pendiente')
            ->orderBy('fecha_fin')
            ->limit(5)
            ->get();

        // Obtener los calendarios asignados al CM
        $calendars = PublicationCalendar::where('creador_id', $user->id)
            ->with(['service'])
            ->orderBy('fecha_fin')
            ->limit(5)
            ->get();

        // Obtener los sueldos del CM
        $salaries = Salary::where('empleado_id', $user->id)
            ->orderBy('fecha_pago', 'desc')
            ->limit(5)
            ->get();

        // Estadísticas
        $stats = [
            'pending_assignments' => Assignment::where('assigned_to', $user->id)
                ->where('estado', 'pendiente')
                ->count(),
            'active_calendars' => PublicationCalendar::where('creador_id', $user->id)
                ->whereIn('estado', ['pendiente', 'en_proceso', 'en_revision'])
                ->count(),
            'completed_calendars' => PublicationCalendar::where('creador_id', $user->id)
                ->where('estado', 'entregado')
                ->count(),
            'pending_payments' => Salary::where('empleado_id', $user->id)
                ->where('estado', 'pendiente')
                ->count(),
        ];

        // Calendarios agrupados por cliente
        $calendarsByClient = PublicationCalendar::where('creador_id', $user->id)
            ->with(['service.cliente'])
            ->get()
            ->groupBy('service.cliente_user_id');

        return view('cm.dashboard', compact(
            'assignments', 
            'calendars', 
            'salaries', 
            'stats',
            'calendarsByClient'
        ));
    }
}