<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\PublicationCalendar;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ReportController extends Controller
{
    public function index(): View
    {
        $reports = Report::with(['calendar', 'createdBy'])
            ->latest()
            ->paginate(10);

        return view('reports.index', compact('reports'));
    }

    public function create(): View
    {
        $calendars = PublicationCalendar::all();

        return view('reports.create', compact('calendars'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'calendar_id' => 'required|exists:publication_calendars,id',
            'document_path' => 'required|string|max:500',
        ]);

        $validated['created_by'] = auth()->id();

        Report::create($validated);

        return redirect()->route('reports.index')
            ->with('success', 'Reporte creado exitosamente.');
    }

    public function show(Report $report): View
    {
        $report->load(['calendar', 'createdBy']);
        
        return view('reports.show', compact('report'));
    }

    public function edit(Report $report): View
    {
        $calendars = PublicationCalendar::all();

        return view('reports.edit', compact('report', 'calendars'));
    }

    public function update(Request $request, Report $report): RedirectResponse
    {
        $validated = $request->validate([
            'calendar_id' => 'required|exists:publication_calendars,id',
            'document_path' => 'required|string|max:500',
        ]);

        $report->update($validated);

        return redirect()->route('reports.index')
            ->with('success', 'Reporte actualizado exitosamente.');
    }

    public function destroy(Report $report): RedirectResponse
    {
        $report->delete();

        return redirect()->route('reports.index')
            ->with('success', 'Reporte eliminado exitosamente.');
    }
}