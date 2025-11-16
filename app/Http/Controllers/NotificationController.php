<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class NotificationController extends Controller
{
    public function index(): View
    {
        $notifications = Notification::with(['user', 'createdBy'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    public function show(Notification $notification): View
    {
        // Marcar como leída al verla
        if (!$notification->is_read) {
            $notification->marcarComoLeida();
        }

        return view('notifications.show', compact('notification'));
    }

    public function markAllAsRead(): RedirectResponse
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return redirect()->back()
            ->with('success', 'Todas las notificaciones marcadas como leídas.');
    }

    public function markAsRead(Notification $notification): RedirectResponse
    {
        if ($notification->user_id !== auth()->id()) {
            return redirect()->back()
                ->with('error', 'No tienes permiso para esta acción.');
        }

        $notification->marcarComoLeida();

        return redirect()->back()
            ->with('success', 'Notificación marcada como leída.');
    }

    public function destroy(Notification $notification): RedirectResponse
    {
        if ($notification->user_id !== auth()->id()) {
            return redirect()->back()
                ->with('error', 'No tienes permiso para eliminar esta notificación.');
        }

        $notification->delete();

        return redirect()->route('notifications.index')
            ->with('success', 'Notificación eliminada exitosamente.');
    }

    public function getUnreadCount(Request $request)
    {
        if ($request->ajax()) {
            $count = Notification::where('user_id', auth()->id())
                ->where('is_read', false)
                ->count();

            return response()->json(['count' => $count]);
        }

        return response()->json(['count' => 0]);
    }
}