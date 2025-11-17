<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle del Sueldo') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Detalle del Sueldo</h3>
                        <a href="{{ route('cm.salaries.index') }}" class="text-sm text-blue-600 hover:text-blue-900">Volver a sueldos</a>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-3">Información del Pago</h4>
                            <dl class="space-y-3">
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Cantidad</dt>
                                    <dd class="text-sm text-gray-900 font-semibold">$ {{ number_format($salary->cantidad, 2) }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Fecha de Pago</dt>
                                    <dd class="text-sm text-gray-900">{{ $salary->fecha_pago->format('d/m/Y') }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Estado</dt>
                                    <dd>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $salary->estado === 'pagado' ? 'bg-green-100 text-green-800' : 
                                               ($salary->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                            {{ ucfirst($salary->estado) }}
                                        </span>
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Pagador</dt>
                                    <dd class="text-sm text-gray-900">{{ $salary->pagador->nombre ?? 'N/A' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Empleado</dt>
                                    <dd class="text-sm text-gray-900">{{ $salary->empleado->nombre }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-3">Comprobante</h4>
                            @if($salary->comprobante_path)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">Comprobante de pago</p>
                                            <p class="text-sm text-gray-500">Subido el {{ $salary->updated_at->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ asset('storage/' . $salary->comprobante_path) }}" 
                                           target="_blank"
                                           class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            Descargar Comprobante
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-8 border border-gray-200 rounded-lg">
                                    <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500">No hay comprobante disponible</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Información Adicional -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-md font-medium text-gray-900 mb-3">Información Adicional</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                            <div>
                                <p><span class="font-medium">Creado:</span> {{ $salary->created_at->format('d/m/Y H:i') }}</p>
                                <p><span class="font-medium">Actualizado:</span> {{ $salary->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                @if($salary->created_by)
                                    <p><span class="font-medium">Registrado por:</span> {{ $salary->createdBy->nombre ?? 'Sistema' }}</p>
                                @endif
                                @if($salary->updated_by)
                                    <p><span class="font-medium">Actualizado por:</span> {{ $salary->updatedBy->nombre ?? 'Sistema' }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>