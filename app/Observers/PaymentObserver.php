<?php

namespace App\Observers;

use App\Models\Payment;
use App\Models\Notification;
use App\Models\User;

class PaymentObserver
{
    public function created(Payment $payment): void
    {
        try {
            // Notificar al CEO
            $ceo = User::whereHas('role', function($q) {
                $q->where('name', 'ceo');
            })->first();

            if ($ceo) {
                Notification::create([
                    'user_id' => $ceo->id,
                    'entidad_tipo' => 'payment',
                    'entidad_id' => $payment->id,
                    'tipo' => 'payment_registered',
                    'mensaje' => "Nuevo pago registrado: {$payment->cantidad} - {$payment->tipo}",
                    'created_by' => $payment->created_by ?? 1,
                ]);
            }

            // Notificar al cliente
            Notification::create([
                'user_id' => $payment->cliente_user_id,
                'entidad_tipo' => 'payment',
                'entidad_id' => $payment->id,
                'tipo' => 'payment_registered',
                'mensaje' => "Se ha registrado tu pago de {$payment->cantidad}. Estado: {$payment->estado}",
                'created_by' => $payment->created_by ?? 1,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en PaymentObserver created: ' . $e->getMessage());
        }
    }

    public function updated(Payment $payment): void
    {
        try {
            // Notificar cambio de estado
            if ($payment->isDirty('estado')) {
                $oldStatus = $payment->getOriginal('estado');
                $newStatus = $payment->estado;
                $this->notifyStatusChange($payment, $oldStatus, $newStatus);
            }

            // Notificar específicamente cuando se marca como pagado
            if ($payment->isDirty('estado') && $payment->estado === 'pagado') {
                $this->notifyPaymentCompleted($payment);
            }
        } catch (\Exception $e) {
            \Log::error('Error en PaymentObserver updated: ' . $e->getMessage());
        }
    }

    private function notifyStatusChange(Payment $payment, string $oldStatus, string $newStatus): void
    {
        $message = "El estado de tu pago ha cambiado de {$oldStatus} a {$newStatus}";

        // Notificar al cliente
        Notification::create([
            'user_id' => $payment->cliente_user_id,
            'entidad_tipo' => 'payment',
            'entidad_id' => $payment->id,
            'tipo' => 'payment_status_changed',
            'mensaje' => $message,
            'created_by' => $payment->updated_by ?? 1,
        ]);
    }

    private function notifyPaymentCompleted(Payment $payment): void
    {
        // Notificar al cliente
        Notification::create([
            'user_id' => $payment->cliente_user_id,
            'entidad_tipo' => 'payment',
            'entidad_id' => $payment->id,
            'tipo' => 'payment_confirmed',
            'mensaje' => "¡Tu pago de {$payment->cantidad} ha sido confirmado! Gracias por tu confianza.",
            'created_by' => $payment->updated_by ?? 1,
        ]);

        // Notificar al CEO
        $ceo = User::whereHas('role', function($q) {
            $q->where('name', 'ceo');
        })->first();

        if ($ceo) {
            Notification::create([
                'user_id' => $ceo->id,
                'entidad_tipo' => 'payment',
                'entidad_id' => $payment->id,
                'tipo' => 'payment_confirmed',
                'mensaje' => "Pago confirmado: {$payment->cantidad} del cliente",
                'created_by' => $payment->updated_by ?? 1,
            ]);
        }

        // Notificar al director de marca
        $directorMarca = User::whereHas('role', function($q) {
            $q->where('name', 'director_marca');
        })->first();

        if ($directorMarca) {
            Notification::create([
                'user_id' => $directorMarca->id,
                'entidad_tipo' => 'payment',
                'entidad_id' => $payment->id,
                'tipo' => 'payment_confirmed',
                'mensaje' => "Un pago ha sido confirmado para un servicio activo",
                'created_by' => $payment->updated_by ?? 1,
            ]);
        }
    }
}