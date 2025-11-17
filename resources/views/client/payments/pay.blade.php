<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Procesar Pago') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Completar Pago</h3>
                    <p class="mt-1 text-sm text-gray-600">Sube tu comprobante de pago para procesar esta transacción.</p>
                </div>
                <div class="p-6">
                    <!-- Información del pago -->
                    <div class="bg-blue-50 p-4 rounded-lg mb-6">
                        <h4 class="text-md font-medium text-blue-900">Resumen del Pago</h4>
                        <dl class="mt-2 space-y-2">
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-blue-700">Servicio</dt>
                                <dd class="text-sm text-blue-900">{{ $payment->service->tipo_formateado }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-blue-700">Tipo de pago</dt>
                                <dd class="text-sm text-blue-900">{{ ucfirst($payment->tipo) }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-blue-700">Fecha de vencimiento</dt>
                                <dd class="text-sm text-blue-900">{{ $payment->fecha_pago->format('d/m/Y') }}</dd>
                            </div>
                            <div class="flex justify-between border-t border-blue-200 pt-2">
                                <dt class="text-base font-bold text-blue-900">Total a pagar</dt>
                                <dd class="text-base font-bold text-blue-900">$ {{ number_format($payment->cantidad, 2) }}</dd>
                            </div>
                        </dl>
                    </div>

                    <form method="POST" action="{{ route('client.payments.process', $payment) }}" enctype="multipart/form-data">
                        @csrf

                        <div class="space-y-6">
                            <!-- Información de pago -->
                            <div>
                                <h4 class="text-md font-medium text-gray-900 mb-3">Información de Pago</h4>
                                
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label for="payment_method" class="block text-sm font-medium text-gray-700">Método de Pago *</label>
                                        <select id="payment_method" name="payment_method" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                            <option value="">Selecciona un método</option>
                                            <option value="transferencia">Transferencia Bancaria</option>
                                            <option value="deposito">Depósito</option>
                                            <option value="efectivo">Efectivo</option>
                                            <option value="tarjeta">Tarjeta de Crédito/Débito</option>
                                            <option value="otro">Otro</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="payment_date" class="block text-sm font-medium text-gray-700">Fecha de Pago *</label>
                                        <input type="date" name="payment_date" id="payment_date" 
                                               value="{{ old('payment_date', now()->format('Y-m-d')) }}"
                                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                    </div>

                                    <div>
                                        <label for="reference_number" class="block text-sm font-medium text-gray-700">Número de Referencia (Opcional)</label>
                                        <input type="text" name="reference_number" id="reference_number" 
                                               value="{{ old('reference_number') }}"
                                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                               placeholder="Número de transacción, depósito, etc.">
                                    </div>
                                </div>
                            </div>

                            <!-- Comprobante -->
                            <div>
                                <h4 class="text-md font-medium text-gray-900 mb-3">Comprobante de Pago</h4>
                                
                                <div>
                                    <label for="comprobante" class="block text-sm font-medium text-gray-700">Archivo del Comprobante *</label>
                                    <input type="file" name="comprobante" id="comprobante" 
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                           required 
                                           accept=".pdf,.jpg,.jpeg,.png">
                                    <p class="mt-1 text-sm text-gray-500">Formatos aceptados: PDF, JPG, JPEG, PNG (Tamaño máximo: 2MB)</p>
                                </div>

                                <div class="mt-3">
                                    <label for="notes" class="block text-sm font-medium text-gray-700">Notas Adicionales (Opcional)</label>
                                    <textarea name="notes" id="notes" rows="3" 
                                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                              placeholder="Agrega cualquier información adicional sobre el pago...">{{ old('notes') }}</textarea>
                                </div>
                            </div>

                            <!-- Información de cuenta bancaria -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-md font-medium text-gray-900 mb-2">Información Bancaria</h4>
                                <div class="space-y-2 text-sm text-gray-600">
                                    <p><strong>Banco:</strong> Nombre del Banco</p>
                                    <p><strong>Tipo de Cuenta:</strong> Cuenta Corriente</p>
                                    <p><strong>Número de Cuenta:</strong> XXXX-XXXX-XXXX-XXXX</p>
                                    <p><strong>Beneficiario:</strong> Nombre de la Empresa</p>
                                    <p><strong>RUT:</strong> XX.XXX.XXX-X</p>
                                    <p><strong>Email para confirmación:</strong> pagos@empresa.com</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('client.payments.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancelar
                            </a>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                </svg>
                                Subir Comprobante
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>