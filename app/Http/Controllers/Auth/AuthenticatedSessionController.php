<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return $this->redirectToRoleDashboard();
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Redirect users based on their role
     */
    protected function redirectToRoleDashboard(): RedirectResponse
    {
        $user = Auth::user();

        if ($user->isCliente()) {
            return redirect()->route('client.dashboard');
        } elseif ($user->isCM()) {
            return redirect()->route('cm.dashboard');
        } elseif ($user->isDeveloper() || $user->isCEO()) {
            return redirect()->route('dashboard');
        } elseif ($user->isDirectorMarca() || $user->isDirectorCreativo()) {
            return redirect()->route('dashboard');
        } elseif ($user->isDesigner()) {
            return redirect()->route('dashboard');
        }

        // Default redirect
        return redirect()->route('dashboard');
    }
    protected $policies = [
    // ... políticas existentes
    \App\Models\PublicationCalendar::class => \App\Policies\PublicationCalendarPolicy::class,
    \App\Models\Artwork::class => \App\Policies\ArtworkPolicy::class,
];
}