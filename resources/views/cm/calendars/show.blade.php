<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle del Calendario') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Información General -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">
                                Calendario: {{ $calendar->fecha_ini->format('d/m/Y') }} - {{ $calendar->fecha_fin->format('d/m/Y') }}
                            </h3>
                            <p class="mt-1 text-sm text-gray-600">
                                Cliente: {{ $calendar->service->cliente->nombre }} | 
                                Servicio: {{ $calendar->service->tipo_formateado }}
                            </p>
                        </div>
                        <div class="flex items-center space-x-2">
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
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-3">Información del Calendario</h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Fecha de inicio</dt>
                                    <dd class="text-sm text-gray-900">{{ $calendar->fecha_ini->format('d/m/Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Fecha de fin</dt>
                                    <dd class="text-sm text-gray-900">{{ $calendar->fecha_fin->format('d/m/Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Días restantes</dt>
                                    <dd class="text-sm text-gray-900">{{ $calendar->dias_restantes }} días</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Creado por</dt>
                                    <dd class="text-sm text-gray-900">{{ $calendar->creador->nombre }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-3">Progreso</h4>
                            <div class="flex items-center mb-2">
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $calendar->progreso }}%"></div>
                                </div>
                                <span class="ml-2 text-sm font-medium text-gray-700">{{ $calendar->progreso }}%</span>
                            </div>
                            <div class="text-sm text-gray-600 space-y-1">
                                <p><span class="font-medium">Total de artes:</span> {{ $calendar->artworks->count() }}</p>
                                <p><span class="font-medium">Artes aprobados:</span> {{ $calendar->artworks->where('estado', 'aprobado')->count() }}</p>
                                <p><span class="font-medium">Artes pendientes:</span> {{ $calendar->artworks->where('estado', 'pendiente')->count() }}</p>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-3">Acciones</h4>
                            <div class="space-y-2">
                                <a href="{{ route('cm.calendars.artworks.index', $calendar) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Gestionar Artes
                                </a>
                                
                                @if($calendar->estado === 'pendiente' || $calendar->estado === 'en_revision')
                                    <a href="{{ route('cm.calendars.edit', $calendar) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Editar Calendario
                                    </a>
                                @endif

                                @if($calendar->estado === 'pendiente' && $calendar->artworks->count() > 0)
                                    <form method="POST" action="{{ route('cm.calendars.submit', $calendar) }}" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Enviar para Aprobación
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('cm.calendars.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Volver a Calendarios
                                </a>
                            </div>
                        </div>
                    </div>

                    @if($calendar->descripcion)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="text-md font-medium text-gray-900 mb-2">Descripción</h4>
                            <p class="text-sm text-gray-600">{{ $calendar->descripcion }}</p>
                        </div>
                    @endif

                    @if($calendar->document_path)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="text-md font-medium text-gray-900 mb-2">Documento Adjunto</h4>
                            <a href="{{ asset('storage/' . $calendar->document_path) }}" 
                               target="_blank"
                               class="inline-flex items-center text-blue-600 hover:text-blue-900">
                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Ver documento del calendario
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Resumen de Artes -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Resumen de Artes</h3>
                        <a href="{{ route('cm.calendars.artworks.index', $calendar) }}" class="text-sm text-blue-600 hover:text-blue-900">Ver todos los artes</a>
                    </div>
                </div>
                <div class="p-6">
                    @if($calendar->artworks->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                            <div class="bg-gray-50 p-4 rounded-lg text-center">
                                <p class="text-2xl font-bold text-gray-900">{{ $calendar->artworks->count() }}</p>
                                <p class="text-sm text-gray-600">Total de Artes</p>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg text-center">
                                <p class="text-2xl font-bold text-green-600">{{ $calendar->artworks->where('estado', 'aprobado')->count() }}</p>
                                <p class="text-sm text-green-600">Aprobados</p>
                            </div>
                            <div class="bg-yellow-50 p-4 rounded-lg text-center">
                                <p class="text-2xl font-bold text-yellow-600">{{ $calendar->artworks->where('estado', 'pendiente')->count() }}</p>
                                <p class="text-sm text-yellow-600">Pendientes</p>
                            </div>
                            <div class="bg-blue-50 p-4 rounded-lg text-center">
                                <p class="text-2xl font-bold text-blue-600">{{ $calendar->artworks->where('estado', 'enviado')->count() }}</p>
                                <p class="text-sm text-blue-600">Enviados</p>
                            </div>
                        </div>

                        <!-- Lista de artes recientes -->
                        <h4 class="text-md font-medium text-gray-900 mb-3">Artes Recientes</h4>
                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($calendar->artworks->take(5) as $artwork)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $artwork->titulo }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $artwork->fecha_pub ? $artwork->fecha_pub->format('d/m/Y') : 'Sin fecha' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ ucfirst($artwork->tipo) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    {{ $artwork->estado === 'aprobado' ? 'bg-green-100 text-green-800' : 
                                                       ($artwork->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                                    {{ ucfirst($artwork->estado) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay artes programados</h3>
                            <p class="mt-1 text-sm text-gray-500">Comienza agregando artes a este calendario.</p>
                            <div class="mt-4">
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
</x-app-layout>