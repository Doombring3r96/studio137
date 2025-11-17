<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nuevo Calendario') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Información del Calendario</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Completa la información para crear un nuevo calendario de publicación.
                    </p>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('cm.calendars.store') }}">
                        @csrf

                        <div class="space-y-6">
                            <!-- Servicio -->
                            <div>
                                <label for="servicio_id" class="block text-sm font-medium text-gray-700">Servicio *</label>
                                <select id="servicio_id" name="servicio_id" required
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="">Selecciona un servicio</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}" {{ old('servicio_id') == $service->id ? 'selected' : '' }}>
                                            {{ $service->cliente->nombre }} - {{ $this->getServiceTypeName($service->tipo) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('servicio_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Fechas -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="fecha_ini" class="block text-sm font-medium text-gray-700">Fecha de Inicio *</label>
                                    <input type="date" name="fecha_ini" id="fecha_ini" required
                                           value="{{ old('fecha_ini') }}"
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    @error('fecha_ini')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="fecha_fin" class="block text-sm font-medium text-gray-700">Fecha de Fin *</label>
                                    <input type="date" name="fecha_fin" id="fecha_fin" required
                                           value="{{ old('fecha_fin') }}"
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    @error('fecha_fin')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Descripción -->
                            <div>
                                <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción (Opcional)</label>
                                <textarea name="descripcion" id="descripcion" rows="4"
                                          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                          placeholder="Describe los objetivos y características de este calendario de publicación...">{{ old('descripcion') }}</textarea>
                                @error('descripcion')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Documento -->
                            <div>
                                <label for="document_path" class="block text-sm font-medium text-gray-700">Ruta del Documento (Opcional)</label>
                                <input type="text" name="document_path" id="document_path"
                                       value="{{ old('document_path') }}"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       placeholder="ruta/del/documento.pdf">
                                <p class="mt-1 text-sm text-gray-500">
                                    Puedes subir el documento después de crear el calendario.
                                </p>
                                @error('document_path')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('cm.calendars.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancelar
                            </a>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Crear Calendario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fechaIni = document.getElementById('fecha_ini');
            const fechaFin = document.getElementById('fecha_fin');
            
            // Establecer fecha mínima para fecha_fin
            fechaIni.addEventListener('change', function() {
                fechaFin.min = this.value;
            });
            
            // Establecer fechas por defecto
            const today = new Date().toISOString().split('T')[0];
            const nextWeek = new Date();
            nextWeek.setDate(nextWeek.getDate() + 7);
            const nextWeekFormatted = nextWeek.toISOString().split('T')[0];
            
            if (!fechaIni.value) {
                fechaIni.value = today;
            }
            if (!fechaFin.value) {
                fechaFin.value = nextWeekFormatted;
            }
            fechaFin.min = fechaIni.value;
        });
    </script>
    @endpush
</x-app-layout>