<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Artes - Calendario') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Información del Calendario -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">
                                Artes para: {{ $calendar->fecha_ini->format('d/m/Y') }} - {{ $calendar->fecha_fin->format('d/m/Y') }}
                            </h3>
                            <p class="mt-1 text-sm text-gray-600">
                                Cliente: {{ $calendar->service->cliente->nombre }} | 
                                Estado: 
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                    {{ $calendar->estado === 'entregado' ? 'bg-green-100 text-green-800' : 
                                       ($calendar->estado === 'enviado' ? 'bg-blue-100 text-blue-800' : 
                                       ($calendar->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ $calendar->estado }}
                                </span>
                            </p>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('cm.calendars.show', $calendar) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Volver al Calendario
                            </a>
                            <a href="{{ route('cm.calendars.artworks.create', $calendar) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Nuevo Arte
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen de Artes -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-gray-900">{{ $artworks->count() }}</p>
                        <p class="text-sm text-gray-600">Total de Artes</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-green-600">{{ $artworks->where('estado', 'aprobado')->count() }}</p>
                        <p class="text-sm text-green-600">Aprobados</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-yellow-600">{{ $artworks->where('estado', 'pendiente')->count() }}</p>
                        <p class="text-sm text-yellow-600">Pendientes</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-blue-600">{{ $artworks->where('estado', 'enviado')->count() }}</p>
                        <p class="text-sm text-blue-600">Enviados</p>
                    </div>
                </div>
            </div>

            <!-- Lista de Artes -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Lista de Artes Programados</h3>
                        <div class="flex space-x-2">
                            <select id="statusFilter" class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                <option value="">Todos los estados</option>
                                <option value="pendiente">Pendiente</option>
                                <option value="enviado">Enviado</option>
                                <option value="aprobado">Aprobado</option>
                                <option value="rechazado">Rechazado</option>
                            </select>
                            <select id="typeFilter" class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                <option value="">Todos los tipos</option>
                                <option value="color">Color</option>
                                <option value="venta">Venta</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @if($artworks->count() > 0)
                        <div class="space-y-4">
                            @foreach($artworks as $artwork)
                                <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex-1">
                                            <h4 class="text-lg font-semibold text-gray-900">{{ $artwork->titulo }}</h4>
                                            <div class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                                                <div>
                                                    <span class="font-medium">Fecha de publicación:</span>
                                                    {{ $artwork->fecha_pub ? $artwork->fecha_pub->format('d/m/Y') : 'Sin fecha' }}
                                                </div>
                                                <div>
                                                    <span class="font-medium">Tipo:</span>
                                                    {{ ucfirst($artwork->tipo) }}
                                                </div>
                                                <div>
                                                    <span class="font-medium">Creado:</span>
                                                    {{ $artwork->created_at->format('d/m/Y') }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2 ml-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                                {{ $artwork->estado === 'aprobado' ? 'bg-green-100 text-green-800' : 
                                                   ($artwork->estado === 'enviado' ? 'bg-blue-100 text-blue-800' : 
                                                   ($artwork->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">
                                                {{ ucfirst($artwork->estado) }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Contenido del Arte -->
                                    @if($artwork->cuerpo || $artwork->copy || $artwork->descripcion)
                                        <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                                            @if($artwork->cuerpo)
                                                <div class="mb-2">
                                                    <span class="text-sm font-medium text-gray-700">Cuerpo:</span>
                                                    <p class="text-sm text-gray-600 mt-1">{{ $artwork->cuerpo }}</p>
                                                </div>
                                            @endif
                                            @if($artwork->copy)
                                                <div class="mb-2">
                                                    <span class="text-sm font-medium text-gray-700">Copy:</span>
                                                    <p class="text-sm text-gray-600 mt-1">{{ $artwork->copy }}</p>
                                                </div>
                                            @endif
                                            @if($artwork->descripcion)
                                                <div>
                                                    <span class="text-sm font-medium text-gray-700">Descripción:</span>
                                                    <p class="text-sm text-gray-600 mt-1">{{ $artwork->descripcion }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Imagen -->
                                    @if($artwork->img_path)
                                        <div class="mb-4">
                                            <span class="text-sm font-medium text-gray-700">Imagen:</span>
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $artwork->img_path) }}" 
                                                     alt="{{ $artwork->titulo }}"
                                                     class="max-w-xs max-h-32 object-cover rounded border border-gray-200">
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Acciones -->
                                    <div class="flex flex-wrap gap-2 pt-4 border-t border-gray-200">
                                        <a href="{{ route('cm.calendars.artworks.edit', [$calendar, $artwork]) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Editar
                                        </a>

                                        @if($artwork->img_path)
                                            <a href="{{ asset('storage/' . $artwork->img_path) }}" 
                                               target="_blank"
                                               class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Ver Imagen
                                            </a>
                                        @endif

                                        @if($artwork->estado === 'pendiente')
                                            <form method="POST" action="{{ route('cm.calendars.artworks.update', [$calendar, $artwork]) }}" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="estado" value="enviado">
                                                <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Enviar para Aprobación
                                                </button>
                                            </form>
                                        @endif

                                        <form method="POST" action="{{ route('cm.calendars.artworks.destroy', [$calendar, $artwork]) }}" class="inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este arte?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Paginación -->
                        <div class="mt-6">
                            {{ $artworks->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">No hay artes programados</h3>
                            <p class="mt-2 text-sm text-gray-500">Comienza agregando artes a este calendario.</p>
                            <div class="mt-6">
                                <a href="{{ route('cm.calendars.artworks.create', $calendar) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Agregar Primer Arte
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
            const typeFilter = document.getElementById('typeFilter');
            
            function applyFilters() {
                const status = statusFilter.value;
                const type = typeFilter.value;
                const url = new URL(window.location.href);
                
                if (status) {
                    url.searchParams.set('estado', status);
                } else {
                    url.searchParams.delete('estado');
                }
                
                if (type) {
                    url.searchParams.set('tipo', type);
                } else {
                    url.searchParams.delete('tipo');
                }
                
                window.location.href = url.toString();
            }
            
            statusFilter.addEventListener('change', applyFilters);
            typeFilter.addEventListener('change', applyFilters);
            
            // Establecer valores actuales de los filtros
            const urlParams = new URLSearchParams(window.location.search);
            statusFilter.value = urlParams.get('estado') || '';
            typeFilter.value = urlParams.get('tipo') || '';
        });
    </script>
    @endpush
</x-app-layout>