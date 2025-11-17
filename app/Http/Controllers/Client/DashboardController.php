<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Payment;
use App\Models\Assignment;
use App\Models\Logo;
use App\Models\PublicationCalendar;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        
        // Estadísticas básicas
        $stats = [
            'active_services' => Service::where('cliente_user_id', $user->id)
                ->where('estado', 'activo')
                ->count(),
            'pending_payments' => Payment::where('cliente_user_id', $user->id)
                ->where('estado', 'pendiente')
                ->count(),
            'pending_actions' => $this->getPendingActionsCount($user),
        ];

        // Próximo pago
        $nextPayment = Payment::where('cliente_user_id', $user->id)
            ->where('estado', 'pendiente')
            ->where('fecha_pago', '>=', now())
            ->orderBy('fecha_pago')
            ->first();

        // Estado de servicios para el gráfico
        $serviceStatuses = Service::where('cliente_user_id', $user->id)
            ->selectRaw('estado, count(*) as count')
            ->groupBy('estado')
            ->get();

        // Próximos vencimientos
        $upcomingDeadlines = $this->getUpcomingDeadlines($user);

        // Acciones pendientes
        $pendingActions = $this->getPendingActions($user);

        return view('client.dashboard', compact(
            'stats', 
            'nextPayment', 
            'serviceStatuses', 
            'upcomingDeadlines',
            'pendingActions'
        ));
    }

    private function getPendingActionsCount($user): int
    {
        $count = 0;

        // Servicios que requieren brief
        $count += Service::where('cliente_user_id', $user->id)
            ->where('estado', 'activo')
            ->doesntHave('brief')
            ->count();

        // Logos que requieren revisión
        $count += Logo::whereHas('service', function($query) use ($user) {
                $query->where('cliente_user_id', $user->id);
            })
            ->whereIn('estado', ['enviado', 'en_revision'])
            ->count();

        // Calendarios que requieren revisión
        $count += PublicationCalendar::whereHas('service', function($query) use ($user) {
                $query->where('cliente_user_id', $user->id);
            })
            ->whereIn('estado', ['enviado', 'en_revision'])
            ->count();

        return $count;
    }

    private function getUpcomingDeadlines($user)
    {
        // Servicios próximos a vencer (7 días)
        $services = Service::where('cliente_user_id', $user->id)
            ->where('estado', 'activo')
            ->where('fecha_fin', '<=', now()->addDays(7))
            ->where('fecha_fin', '>=', now())
            ->select('id', 'tipo', 'fecha_fin')
            ->get()
            ->map(function($service) {
                return (object)[
                    'tipo' => 'Servicio: ' . $this->getServiceTypeName($service->tipo),
                    'nombre' => 'Fecha de entrega',
                    'fecha_fin' => $service->fecha_fin
                ];
            });

        // Pagos próximos a vencer (7 días)
        $payments = Payment::where('cliente_user_id', $user->id)
            ->where('estado', 'pendiente')
            ->where('fecha_pago', '<=', now()->addDays(7))
            ->where('fecha_pago', '>=', now())
            ->with('service')
            ->get()
            ->map(function($payment) {
                return (object)[
                    'tipo' => 'Pago: ' . $this->getServiceTypeName($payment->service->tipo),
                    'nombre' => 'Pago ' . $payment->tipo,
                    'fecha_fin' => $payment->fecha_pago
                ];
            });

        return $services->merge($payments)->sortBy('fecha_fin')->take(5);
    }

    private function getPendingActions($user)
    {
        $actions = [];

        // Servicios que requieren brief
        $servicesWithoutBrief = Service::where('cliente_user_id', $user->id)
            ->where('estado', 'activo')
            ->doesntHave('brief')
            ->get();

        foreach ($servicesWithoutBrief as $service) {
            $actions[] = [
                'title' => 'Completar Brief',
                'description' => 'El servicio ' . $this->getServiceTypeName($service->tipo) . ' requiere que completes el brief',
                'action_text' => 'Completar Brief',
                'action_url' => route('client.services.brief.create', $service)
            ];
        }

        // Logos que requieren revisión
        $logosPending = Logo::whereHas('service', function($query) use ($user) {
                $query->where('cliente_user_id', $user->id);
            })
            ->whereIn('estado', ['enviado', 'en_revision'])
            ->with('service')
            ->get();

        foreach ($logosPending as $logo) {
            $actions[] = [
                'title' => 'Revisar Logo',
                'description' => 'Tienes una propuesta de logo pendiente de revisión',
                'action_text' => 'Revisar',
                'action_url' => route('client.services.logos', $logo->service)
            ];
        }

        // Calendarios que requieren revisión
        $calendarsPending = PublicationCalendar::whereHas('service', function($query) use ($user) {
                $query->where('cliente_user_id', $user->id);
            })
            ->whereIn('estado', ['enviado', 'en_revision'])
            ->with('service')
            ->get();

        foreach ($calendarsPending as $calendar) {
            $actions[] = [
                'title' => 'Revisar Calendario',
                'description' => 'Tienes un calendario de publicación pendiente de revisión',
                'action_text' => 'Revisar',
                'action_url' => route('client.services.calendars', $calendar->service)
            ];
        }

        return collect($actions)->take(3);
    }

    private function getServiceTypeName($type): string
    {
        return match($type) {
            'identidad_corporativa' => 'Identidad Corporativa',
            'community_manager' => 'Community Manager',
            'marketing_digital' => 'Marketing Digital',
            default => $type
        };
    }
}