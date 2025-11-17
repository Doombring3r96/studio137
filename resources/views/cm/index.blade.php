<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tareas Pendientes - Calendarios de Publicación') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filtros y Acciones -->
            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                        <div class="flex space-x-4">
                            <select id="statusFilter" class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Todos los estados</option>
                                <option value="pendiente">Pendiente</option>
                                <option value="en_proceso">En Proceso</option>
                                <option value="enviado">Enviado</option>
                                <option value="en_revision">En Revisión</option>
                                <option value="entregado">Entregado</option>
                                <option value="rechazado">Rechazado</option>
                            </select>
                            
                            <select id="clientFilter" class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Todos los clientes</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <a href="{{ route('cm.calendars.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Nuevo Calendario
                        </a>
                    </div>
                </div>
            </div>

            <!-- Lista de Calendarios -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Mis Calendarios de Publicación</h3>
                </div>
                <div class="p-6">
                    @if($calendars->count() > 0)
                        <div class="space-y-6">
                            @foreach($calendars as $calendar)
                                <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex-1">
                                            <h4 class="text-lg font-semibold text-gray-900">
                                                Calendario: {{ $calendar->fecha_ini->format('d/m/Y') }} - {{ $calendar->fecha_fin->format('d/m/Y') }}
                                            </h4>
                                            <div class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                                                <div>
                                                    <span class="font-medium">Cliente:</span>
                                                    {{ $calendar->service->cliente->nombre }}
                                                </div>
                                                <div>
                                                    <span class="font-medium">Servicio:</span>
                                                    {{ $calendar->service->tipo_formateado }}
                                                </div>
                                                <div>
                                                    <span class="font-medium">Artes:</span>
                                                    {{ $calendar->artworks->count() }} programados
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2 ml-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                                {{ $calendar->estado === 'entregado' ? 'bg-green-100 text-green-800' : 
                                                   ($calendar->estado === 'enviado' ? 'bg-blue-100 text-blue-800' : 
                                                   ($calendar->estado === 'en_revision' ? 'bg-yellow-100 text-yellow-800' : 
                                                   ($calendar->estado === 'pendiente' ? 'bg-gray-100 text-gray-800' : 
                                                   ($calendar->estado === 'rechazado' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')))) }}">
                                                {{ $calendar->estado }}
                                            </span>
                                            @if($calendar->correcciones_count > 0)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                    {{ $calendar->correcciones_count }}/2 correcciones
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Progreso y Metadatos -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                                <span>Progreso del calendario</span>
                                                <span>{{ $calendar->progreso }}%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $calendar->progreso }}%"></div>
                                            </div>
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            <p><span class="font-medium">Días restantes:</span> {{ $calendar->dias_restantes }}</p>
                                            <p><span class="font-medium">Fecha límite:</span> {{ $calendar->fecha_fin->format('d/m/Y') }}</p>
                                        </div>
                                    </div>

                                    <!-- Acciones -->
                                    <div class="flex flex-wrap gap-2 pt-4 border-t border-gray-200">
                                        <a href="{{ route('cm.calendars.show', $calendar) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Ver Detalles
                                        </a>
                                        
                                        <a href="{{ route('cm.calendars.artworks.index', $calendar) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Gestionar Artes ({{ $calendar->artworks->count() }})
                                        </a>

                                        @if($calendar->estado === 'pendiente' || $calendar->estado === 'en_revision')
                                            <a href="{{ route('cm.calendars.edit', $calendar) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Editar
                                            </a>
                                        @endif

                                        @if($calendar->estado === 'pendiente' && $calendar->artworks->count() > 0)
                                            <form method="POST" action="{{ route('cm.calendars.submit', $calendar) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Enviar para Aprobación
                                                </button>
                                            </form>
                                        @endif

                                        @if($calendar->estado === 'entregado')
                                            <form method="POST" action="{{ route('cm.calendars.mark-completed', $calendar) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Marcar como Completado
                                                </button>
                                            </form>
                                        @endif

                                        @if($calendar->estado === 'pendiente')
                                            <form method="POST" action="{{ route('cm.calendars.destroy', $calendar) }}" class="inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este calendario?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Eliminar
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Paginación -->
                        <div class="mt-6">
                            {{ $calendars->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">No hay calendarios asignados</h3>
                            <p class="mt-2 text-sm text-gray-500">Comienza creando tu primer calendario de publicación.</p>
                            <div class="mt-6">
                                <a href="{{ route('cm.calendars.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Crear Primer Calendario
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusFilter = document.getElementById('statusFilter');
            const clientFilter = document.getElementById('clientFilter');
            
            function applyFilters() {
                const status = statusFilter.value;
                const client = clientFilter.value;
                const url = new URL(window.location.href);
                
                if (status) {
                    url.searchParams.set('estado', status);
                } else {
                    url.searchParams.delete('estado');
                }
                
                if (client) {
                    url.searchParams.set('cliente', client);
                } else {
                    url.searchParams.delete('cliente');
                }
                
                window.location.href = url.toString();
            }
            
            statusFilter.addEventListener('change', applyFilters);
            clientFilter.addEventListener('change', applyFilters);
            
            // Establecer valores actuales de los filtros
            const urlParams = new URLSearchParams(window.location.search);
            statusFilter.value = urlParams.get('estado') || '';
            clientFilter.value = urlParams.get('cliente') || '';
        });
    </script>
    @endpush
</x-app-layout>