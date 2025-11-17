<?php

namespace App\Http\Controllers\Cm;

use App\Http\Controllers\Controller;
use App\Models\Salary;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SalaryController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        
        $salaries = Salary::where('empleado_id', $user->id)
            ->with(['pagador'])
            ->orderBy('fecha_pago', 'desc')
            ->paginate(10);

        // Estadísticas
        $stats = [
            'pagados' => Salary::where('empleado_id', $user->id)
                ->where('estado', 'pagado')
                ->count(),
            'pendientes' => Salary::where('empleado_id', $user->id)
                ->where('estado', 'pendiente')
                ->count(),
            'total_recibido' => Salary::where('empleado_id', $user->id)
                ->where('estado', 'pagado')
                ->sum('cantidad'),
        ];

        return view('cm.salaries.index', compact('salaries', 'stats'));
    }

    public function show(Salary $salary): View
    {
        $this->authorize('view', $salary);

        $salary->load(['pagador', 'empleado']);

        return view('cm.salaries.show', compact('salary'));
    }
}