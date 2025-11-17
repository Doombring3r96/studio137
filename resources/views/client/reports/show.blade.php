<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle del Informe') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Informe del {{ $report->created_at->format('d/m/Y') }}</h3>
                        <a href="{{ route('client.reports.index') }}" class="text-sm text-blue-600 hover:text-blue-900">Volver a informes</a>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-3">Información del Informe</h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Servicio</dt>
                                    <dd class="text-sm text-gray-900">{{ $report->calendar->service->tipo_formateado }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Período del Calendario</dt>
                                    <dd class="text-sm text-gray-900">
                                        {{ $report->calendar->fecha_ini->format('d/m/Y') }} - {{ $report->calendar->fecha_fin->format('d/m/Y') }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Fecha de generación</dt>
                                    <dd class="text-sm text-gray-900">{{ $report->created_at->format('d/m/Y H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Generado por</dt>
                                    <dd class="text-sm text-gray-900">{{ $report->createdBy->nombre ?? 'Sistema' }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-3">Documento</h4>
                            @if($report->document_path)
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                    <div class="flex items-center">
                                        <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">Informe PDF</p>
                                            <p class="text-sm text-gray-500">{{ $report->created_at->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ asset('storage/' . $report->document_path) }}" 
                                       target="_blank"
                                       class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Descargar
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-4 border border-gray-200 rounded-lg">
                                    <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500">No hay documento adjunto</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Resumen del calendario -->
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-md font-medium text-gray-900 mb-3">Resumen del Calendario</h4>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="text-center">
                                    <p class="text-sm font-medium text-gray-500">Total de Artes</p>
                                    <p class="text-2xl font-semibold text-gray-900">{{ $report->calendar->artworks->count() }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-sm font-medium text-gray-500">Artes Aprobados</p>
                                    <p class="text-2xl font-semibold text-green-600">
                                        {{ $report->calendar->artworks->where('estado', 'aprobado')->count() }}
                                    </p>
                                </div>
                                <div class="text-center">
                                    <p class="text-sm font-medium text-gray-500">Artes Pendientes</p>
                                    <p class="text-2xl font-semibold text-yellow-600">
                                        {{ $report->calendar->artworks->where('estado', 'pendiente')->count() }}
                                    </p>
                                </div>
                                <div class="text-center">
                                    <p class="text-sm font-medium text-gray-500">Artes Rechazados</p>
                                    <p class="text-2xl font-semibold text-red-600">
                                        {{ $report->calendar->artworks->where('estado', 'rechazado')->count() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detalle de artes -->
                    @if($report->calendar->artworks->count() > 0)
                        <div class="border-t border-gray-200 pt-6">
                            <h4 class="text-md font-medium text-gray-900 mb-3">Detalle de Artes</h4>
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
                                        @foreach($report->calendar->artworks as $artwork)
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
                                                           ($artwork->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                                        {{ ucfirst($artwork->estado) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>