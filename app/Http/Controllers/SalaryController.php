<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SalaryController extends Controller
{
    public function index(): View
    {
        $salaries = Salary::with(['pagador', 'empleado', 'createdBy'])
            ->latest()
            ->paginate(10);

        return view('salaries.index', compact('salaries'));
    }

    public function create(): View
    {
        $empleados = User::whereHas('role', function($query) {
            $query->whereIn('name', ['designer', 'cm', 'director_creativo', 'director_marca']);
        })->get();

        $pagadores = User::whereHas('role', function($query) {
            $query->whereIn('name', ['ceo', 'developer']);
        })->get();

        return view('salaries.create', compact('empleados', 'pagadores'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'pagador_id' => 'required|exists:users,id',
            'empleado_id' => 'required|exists:users,id',
            'cantidad' => 'required|numeric|min:0',
            'fecha_pago' => 'required|date',
            'comprobante_path' => 'nullable|string|max:500',
            'estado' => 'required|in:pendiente,pagado,revisado',
        ]);

        $validated['created_by'] = auth()->id();

        Salary::create($validated);

        return redirect()->route('salaries.index')
            ->with('success', 'Sueldo registrado exitosamente.');
    }

    public function show(Salary $salary): View
    {
        $salary->load(['pagador', 'empleado', 'createdBy', 'updatedBy']);
        
        return view('salaries.show', compact('salary'));
    }

    public function edit(Salary $salary): View
    {
        $empleados = User::whereHas('role', function($query) {
            $query->whereIn('name', ['designer', 'cm', 'director_creativo', 'director_marca']);
        })->get();

        $pagadores = User::whereHas('role', function($query) {
            $query->whereIn('name', ['ceo', 'developer']);
        })->get();

        return view('salaries.edit', compact('salary', 'empleados', 'pagadores'));
    }

    public function update(Request $request, Salary $salary): RedirectResponse
    {
        $validated = $request->validate([
            'pagador_id' => 'required|exists:users,id',
            'empleado_id' => 'required|exists:users,id',
            'cantidad' => 'required|numeric|min:0',
            'fecha_pago' => 'required|date',
            'comprobante_path' => 'nullable|string|max:500',
            'estado' => 'required|in:pendiente,pagado,revisado',
        ]);

        $validated['updated_by'] = auth()->id();

        $salary->update($validated);

        return redirect()->route('salaries.index')
            ->with('success', 'Sueldo actualizado exitosamente.');
    }

    public function destroy(Salary $salary): RedirectResponse
    {
        $salary->delete();

        return redirect()->route('salaries.index')
            ->with('success', 'Sueldo eliminado exitosamente.');
    }

    public function markAsPaid(Salary $salary): RedirectResponse
    {
        $salary->update([
            'estado' => 'pagado',
            'updated_by' => auth()->id(),
        ]);

        return redirect()->back()
            ->with('success', 'Sueldo marcado como pagado.');
    }
}