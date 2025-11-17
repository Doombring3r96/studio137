<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $reports = Report::whereHas('calendar.service', function($query) {
                $query->where('cliente_user_id', auth()->id());
            })
            ->with(['calendar.service'])
            ->latest()
            ->paginate(10);

        // Formatear tipo de servicio para la vista
        $reports->each(function ($report) {
            if ($report->calendar && $report->calendar->service) {
                $report->calendar->service->tipo_formateado = $this->getServiceTypeName($report->calendar->service->tipo);
            }
        });

        return view('client.reports.index', compact('reports'));
    }

    public function show(Report $report): View
    {
        $this->authorize('view', $report);

        $report->load(['calendar.service', 'createdBy']);

        $report->calendar->service->tipo_formateado = $this->getServiceTypeName($report->calendar->service->tipo);

        return view('client.reports.show', compact('report'));
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