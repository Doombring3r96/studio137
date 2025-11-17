<?php

namespace App\Http\Controllers;

abstract class Controller
{
    // Agregar este método al controlador base
protected function redirectToRoleDashboard()
{
    $user = auth()->user();
    
    if ($user->isCliente()) {
        return redirect()->route('client.dashboard');
    }
    
    // Agregar otros roles aquí después
    return redirect('/dashboard');
}
}
