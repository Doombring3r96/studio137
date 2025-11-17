<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Informes') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informes de Marketing</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Aquí puedes encontrar todos los informes generados por el equipo de marketing para tus servicios.
                    </p>
                </div>
                <div class="p-6">
                    @if($reports->count() > 0)
                        <div class="space-y-6">
                            @foreach($reports as $report)
                                <div class="border border-gray-200 rounded-lg p-6">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="text-lg font-semibold text-gray-900">
                                                Informe del {{ $report->created_at->format('d/m/Y') }}
                                            </h4>
                                            <p class="text-sm text-gray-600 mt-1">
                                                <span class="font-medium">Servicio:</span>
                                                {{ $report->calendar->service->tipo_formateado ?? 'N/A' }}
                                            </p>
                                            <p class="text-sm text-gray-600 mt-1">
                                                <span class="font-medium">Período:</span>
                                                {{ $report->calendar->fecha_ini->format('d/m/Y') }} - {{ $report->calendar->fecha_fin->format('d/m/Y') }}
                                            </p>
                                        </div>
                                        <div class="flex space-x-2">
                                            @if($report->document_path)
                                                <a href="{{ asset('storage/' . $report->document_path) }}" 
                                                   target="_blank"
                                                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    Ver PDF
                                                </a>
                                            @endif
                                            <a href="{{ route('client.reports.show', $report) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Detalles
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Paginación -->
                        <div class="mt-6">
                            {{ $reports->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay informes disponibles</h3>
                            <p class="mt-1 text-sm text-gray-500">Los informes aparecerán aquí una vez que el equipo de marketing los genere.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>