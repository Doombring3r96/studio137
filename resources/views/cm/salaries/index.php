<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pago Sueldo - Historial de Sueldos') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Resumen de Sueldos -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Pagados</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['pagados'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Pendientes</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['pendientes'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Total Recibido</p>
                            <p class="text-2xl font-bold text-gray-900">$ {{ number_format($stats['total_recibido'], 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Sueldos -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Historial de Sueldos</h3>
                </div>
                <div class="p-6">
                    @if($salaries->count() > 0)
                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha de Pago</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pagador</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comprobante</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($salaries as $salary)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $salary->fecha_pago->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                $ {{ number_format($salary->cantidad, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $salary->pagador->nombre ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    {{ $salary->estado === 'pagado' ? 'bg-green-100 text-green-800' : 
                                                       ($salary->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                                    {{ ucfirst($salary->estado) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($salary->comprobante_path)
                                                    <a href="{{ asset('storage/' . $salary->comprobante_path) }}" 
                                                       target="_blank"
                                                       class="text-blue-600 hover:text-blue-900">
                                                        Ver comprobante
                                                    </a>
                                                @else
                                                    <span class="text-gray-400">No disponible</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('cm.salaries.show', $salary) }}" class="text-blue-600 hover:text-blue-900">
                                                    Ver detalles
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div class="mt-6">
                            {{ $salaries->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay registros de sueldos</h3>
                            <p class="mt-1 text-sm text-gray-500">Los sueldos aparecerán aquí una vez que se generen.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Información de Pago -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Información de Pago</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-3">Datos Bancarios</h4>
                            <dl class="space-y-2 text-sm text-gray-600">
                                <div>
                                    <dt class="font-medium">Nombre del Titular:</dt>
                                    <dd>{{ auth()->user()->nombre }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium">Tipo de Cuenta:</dt>
                                    <dd>Cuenta Corriente</dd>
                                </div>
                                <div>
                                    <dt class="font-medium">Número de Cuenta:</dt>
                                    <dd>XXXX-XXXX-XXXX-XXXX</dd>
                                </div>
                                <div>
                                    <dt class="font-medium">Banco:</dt>
                                    <dd>Nombre del Banco</dd>
                                </div>
                            </dl>
                        </div>
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-3">Contacto</h4>
                            <dl class="space-y-2 text-sm text-gray-600">
                                <div>
                                    <dt class="font-medium">Para consultas de pago:</dt>
                                    <dd>contabilidad@empresa.com</dd>
                                </div>
                                <div>
                                    <dt class="font-medium">Teléfono:</dt>
                                    <dd>+1 234 567 890</dd>
                                </div>
                                <div>
                                    <dt class="font-medium">Horario de atención:</dt>
                                    <dd>Lunes a Viernes 9:00 - 18:00</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>