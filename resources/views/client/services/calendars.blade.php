<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Calendarios de Publicación') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Calendarios para {{ $service->tipo_formateado }}</h3>
                        <a href="{{ route('client.services.show', $service) }}" class="text-sm text-blue-600 hover:text-blue-900">Volver al servicio</a>
                    </div>
                </div>
                <div class="p-6">
                    @if($calendars->count() > 0)
                        <div class="space-y-6">
                            @foreach($calendars as $calendar)
                                <div class="border border-gray-200 rounded-lg p-6">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h4 class="text-lg font-semibold text-gray-900">
                                                Calendario {{ $calendar->fecha_ini->format('d/m/Y') }} - {{ $calendar->fecha_fin->format('d/m/Y') }}
                                            </h4>
                                            <p class="text-sm text-gray-600 mt-1">
                                                {{ $calendar->artworks->count() }} artes programados
                                            </p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ $calendar->estado === 'entregado' ? 'bg-green-100 text-green-800' : 
                                                   ($calendar->estado === 'enviado' ? 'bg-blue-100 text-blue-800' : 
                                                   ($calendar->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 
                                                   ($calendar->estado === 'rechazado' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))) }}">
                                                {{ $calendar->estado }}
                                            </span>
                                            @if($calendar->correcciones_count > 0)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                    {{ $calendar->correcciones_count }}/2 correcciones
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    @if($calendar->document_path)
                                        <div class="mb-4">
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

                                    <!-- Artes del calendario -->
                                    @if($calendar->artworks->count() > 0)
                                        <div class="mb-4">
                                            <h5 class="text-md font-medium text-gray-900 mb-2">Artes Programados</h5>
                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                                @foreach($calendar->artworks as $artwork)
                                                    <div class="border border-gray-200 rounded p-3">
                                                        <p class="text-sm font-medium text-gray-900">{{ $artwork->titulo }}</p>
                                                        <p class="text-xs text-gray-500">
                                                            {{ $artwork->fecha_pub ? $artwork->fecha_pub->format('d/m/Y') : 'Sin fecha' }}
                                                        </p>
                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium 
                                                            {{ $artwork->estado === 'aprobado' ? 'bg-green-100 text-green-800' : 
                                                               ($artwork->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                                            {{ $artwork->estado }}
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Acciones -->
                                    @if($calendar->estado === 'enviado' || $calendar->estado === 'en_revision')
                                        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                                            <form method="POST" action="{{ route('client.services.calendars.approve', $calendar) }}">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                                    Aprobar Calendario
                                                </button>
                                            </form>
                                            <button onclick="openCorrectionModal({{ $calendar->id }})" 
                                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700">
                                                Solicitar Corrección
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay calendarios de publicación</h3>
                            <p class="mt-1 text-sm text-gray-500">Los calendarios de publicación aparecerán aquí una vez que el community manager los envíe.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Corrección -->
    <div id="correctionModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <form id="correctionForm" method="POST">
                    @csrf
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Solicitar Corrección</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 mb-3">
                                Puedes solicitar hasta 2 correcciones por calendario. Por favor, sé específico en tus comentarios.
                            </p>
                            <label for="correction_notes" class="block text-sm font-medium text-gray-700">Comentarios de corrección *</label>
                            <textarea id="correction_notes" name="correction_notes" rows="6" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required placeholder="Describe detalladamente qué aspectos del calendario necesitan corrección..."></textarea>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-600 text-base font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:col-start-2 sm:text-sm">
                            Enviar Corrección
                        </button>
                        <button type="button" onclick="closeCorrectionModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openCorrectionModal(calendarId) {
            const modal = document.getElementById('correctionModal');
            const form = document.getElementById('correctionForm');
            form.action = `/client/services/calendars/${calendarId}/correct`;
            modal.classList.remove('hidden');
        }

        function closeCorrectionModal() {
            const modal = document.getElementById('correctionModal');
            modal.classList.add('hidden');
        }
    </script>
    @endpush
</x-app-layout>