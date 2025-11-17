<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Servicios Activos') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Lista de Servicios -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Mis Servicios</h3>
                </div>
                <div class="p-6">
                    @if($services->count() > 0)
                        <div class="space-y-6">
                            @foreach($services as $service)
                                <div class="border border-gray-200 rounded-lg p-6">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="text-lg font-semibold text-gray-900">
                                                {{ $service->tipo_formateado }}
                                            </h4>
                                            <p class="text-sm text-gray-600 mt-1">
                                                <span class="font-medium">Estado:</span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    {{ $service->estado_clase }}">
                                                    {{ $service->estado }}
                                                </span>
                                            </p>
                                            <p class="text-sm text-gray-600 mt-1">
                                                <span class="font-medium">Fecha de inicio:</span>
                                                {{ $service->fecha_ini->format('d/m/Y') }}
                                            </p>
                                            <p class="text-sm text-gray-600 mt-1">
                                                <span class="font-medium">Fecha de entrega:</span>
                                                {{ $service->fecha_fin->format('d/m/Y') }}
                                            </p>
                                            <p class="text-sm text-gray-600 mt-1">
                                                <span class="font-medium">Costo:</span>
                                                {{ $service->costo_formateado }}
                                            </p>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('client.services.show', $service) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Ver Detalles
                                            </a>
                                            @if(!$service->brief)
                                                <a href="{{ route('client.services.brief.create', $service) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                    Completar Brief
                                                </a>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Progreso del Servicio -->
                                    <div class="mt-4">
                                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                                            <span>Progreso del servicio</span>
                                            <span>{{ $service->progreso }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $service->progreso }}%"></div>
                                        </div>
                                    </div>

                                    <!-- Acciones Pendientes -->
                                    @if($service->acciones_pendientes->count() > 0)
                                        <div class="mt-4">
                                            <p class="text-sm font-medium text-gray-900">Acciones pendientes:</p>
                                            <ul class="mt-2 space-y-2">
                                                @foreach($service->acciones_pendientes as $accion)
                                                    <li class="flex items-center text-sm text-gray-600">
                                                        <svg class="h-5 w-5 text-yellow-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                        </svg>
                                                        <a href="{{ $accion['url'] }}" class="text-blue-600 hover:text-blue-900">
                                                            {{ $accion['texto'] }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <!-- Paginación -->
                        <div class="mt-6">
                            {{ $services->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay servicios activos</h3>
                            <p class="mt-1 text-sm text-gray-500">Comienza contratando un nuevo servicio.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>