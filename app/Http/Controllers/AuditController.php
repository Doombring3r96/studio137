<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditController extends Controller
{
    public function index(Request $request): View
    {
        $query = Audit::with(['user']);

        // Filtros
        if ($request->has('entity_type') && $request->entity_type) {
            $query->where('entity_type', $request->entity_type);
        }

        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }

        $audits = $query->latest()->paginate(20);

        $entityTypes = Audit::distinct()->pluck('entity_type');
        $actions = Audit::distinct()->pluck('action');

        return view('audits.index', compact('audits', 'entityTypes', 'actions'));
    }

    public function show(Audit $audit): View
    {
        $audit->load(['user']);

        return view('audits.show', compact('audit'));
    }
}