<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Propuestas de Logo') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Logos para {{ $service->tipo_formateado }}</h3>
                        <a href="{{ route('client.services.show', $service) }}" class="text-sm text-blue-600 hover:text-blue-900">Volver al servicio</a>
                    </div>
                </div>
                <div class="p-6">
                    @if($logos->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($logos as $logo)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <h4 class="text-md font-medium text-gray-900">{{ $logo->tipo }}</h4>
                                            <p class="text-sm text-gray-500">{{ $logo->version ?? 'Sin versión' }}</p>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $logo->estado === 'entregado' ? 'bg-green-100 text-green-800' : 
                                               ($logo->estado === 'enviado' ? 'bg-blue-100 text-blue-800' : 
                                               ($logo->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 
                                               ($logo->estado === 'rechazado' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))) }}">
                                            {{ $logo->estado }}
                                        </span>
                                    </div>

                                    @if($logo->img_path)
                                        <div class="mb-3">
                                            <img src="{{ asset('storage/' . $logo->img_path) }}" 
                                                 alt="Propuesta de logo {{ $logo->tipo }}"
                                                 class="w-full h-32 object-contain border border-gray-200 rounded">
                                        </div>
                                    @endif

                                    @if($logo->descripcion)
                                        <p class="text-sm text-gray-600 mb-3">{{ $logo->descripcion }}</p>
                                    @endif

                                    <div class="flex justify-between items-center">
                                        <span class="text-xs text-gray-500">
                                            {{ $logo->created_at->format('d/m/Y') }}
                                        </span>
                                        <div class="flex space-x-2">
                                            @if($logo->img_path)
                                                <a href="{{ asset('storage/' . $logo->img_path) }}" 
                                                   target="_blank"
                                                   class="inline-flex items-center text-blue-600 hover:text-blue-900 text-sm">
                                                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Ver
                                                </a>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Acciones para logos enviados -->
                                    @if($logo->estado === 'enviado' || $logo->estado === 'en_revision')
                                        <div class="mt-3 pt-3 border-t border-gray-200">
                                            <p class="text-xs text-gray-500 mb-2">¿Te gusta esta propuesta?</p>
                                            <div class="flex space-x-2">
                                                <form method="POST" action="{{ route('client.services.logos.approve', $logo) }}" class="flex-1">
                                                    @csrf
                                                    <button type="submit" class="w-full inline-flex justify-center items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700">
                                                        Aprobar
                                                    </button>
                                                </form>
                                                <button onclick="openRejectionModal({{ $logo->id }})" 
                                                        class="flex-1 inline-flex justify-center items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-white bg-red-600 hover:bg-red-700">
                                                    Rechazar
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay propuestas de logo</h3>
                            <p class="mt-1 text-sm text-gray-500">Las propuestas de logo aparecerán aquí una vez que el diseñador las envíe.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Rechazo -->
    <div id="rejectionModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <form id="rejectionForm" method="POST">
                    @csrf
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Rechazar Propuesta</h3>
                        <div class="mt-2">
                            <label for="rejection_reason" class="block text-sm font-medium text-gray-700">Motivo del rechazo *</label>
                            <textarea id="rejection_reason" name="rejection_reason" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required placeholder="Por favor, explica qué aspectos no te gustan o qué cambios te gustaría ver..."></textarea>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:col-start-2 sm:text-sm">
                            Enviar Rechazo
                        </button>
                        <button type="button" onclick="closeRejectionModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openRejectionModal(logoId) {
            const modal = document.getElementById('rejectionModal');
            const form = document.getElementById('rejectionForm');
            form.action = `/client/services/logos/${logoId}/reject`;
            modal.classList.remove('hidden');
        }

        function closeRejectionModal() {
            const modal = document.getElementById('rejectionModal');
            modal.classList.add('hidden');
        }
    </script>
    @endpush
</x-app-layout>