<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nuevo Arte') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Nuevo Arte para Calendario</h3>
                            <p class="mt-1 text-sm text-gray-600">
                                Calendario: {{ $calendar->fecha_ini->format('d/m/Y') }} - {{ $calendar->fecha_fin->format('d/m/Y') }}
                            </p>
                        </div>
                        <a href="{{ route('cm.calendars.artworks.index', $calendar) }}" class="text-sm text-blue-600 hover:text-blue-900">Volver a artes</a>
                    </div>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('cm.calendars.artworks.store', $calendar) }}" enctype="multipart/form-data">
                        @csrf

                        <div class="space-y-6">
                            <!-- Información Básica -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="titulo" class="block text-sm font-medium text-gray-700">Título del Arte *</label>
                                    <input type="text" name="titulo" id="titulo" required
                                           value="{{ old('titulo') }}"
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                           placeholder="Ej: Promoción de Verano">
                                    @error('titulo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="fecha_pub" class="block text-sm font-medium text-gray-700">Fecha de Publicación *</label>
                                    <input type="date" name="fecha_pub" id="fecha_pub" required
                                           value="{{ old('fecha_pub') }}"
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    @error('fecha_pub')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Tipo y Estado -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="tipo" class="block text-sm font-medium text-gray-700">Tipo de Arte *</label>
                                    <select name="tipo" id="tipo" required
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <option value="">Selecciona un tipo</option>
                                        <option value="color" {{ old('tipo') == 'color' ? 'selected' : '' }}>Color</option>
                                        <option value="venta" {{ old('tipo') == 'venta' ? 'selected' : '' }}>Venta</option>
                                    </select>
                                    @error('tipo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="estado" class="block text-sm font-medium text-gray-700">Estado *</label>
                                    <select name="estado" id="estado" required
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <option value="">Selecciona un estado</option>
                                        <option value="pendiente" {{ old('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="enviado" {{ old('estado') == 'enviado' ? 'selected' : '' }}>Enviado</option>
                                        <option value="aprobado" {{ old('estado') == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                                        <option value="rechazado" {{ old('estado') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                                    </select>
                                    @error('estado')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Contenido -->
                            <div>
                                <label for="cuerpo" class="block text-sm font-medium text-gray-700">Cuerpo del Mensaje</label>
                                <textarea name="cuerpo" id="cuerpo" rows="3"
                                          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                          placeholder="Contenido principal del mensaje...">{{ old('cuerpo') }}</textarea>
                                @error('cuerpo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="copy" class="block text-sm font-medium text-gray-700">Copy (Texto Publicitario)</label>
                                <textarea name="copy" id="copy" rows="3"
                                          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                          placeholder="Texto publicitario o llamada a la acción...">{{ old('copy') }}</textarea>
                                @error('copy')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción Adicional</label>
                                <textarea name="descripcion" id="descripcion" rows="3"
                                          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                          placeholder="Instrucciones adicionales o detalles específicos...">{{ old('descripcion') }}</textarea>
                                @error('descripcion')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Imagen -->
                            <div>
                                <label for="img_path" class="block text-sm font-medium text-gray-700">Imagen del Arte</label>
                                <input type="file" name="img_path" id="img_path"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       accept="image/jpeg,image/png,image/jpg,image/gif">
                                <p class="mt-1 text-sm text-gray-500">
                                    Formatos aceptados: JPG, JPEG, PNG, GIF (Tamaño máximo: 2MB)
                                </p>
                                @error('img_path')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Vista Previa de la Imagen -->
                            <div id="imagePreview" class="hidden">
                                <label class="block text-sm font-medium text-gray-700">Vista Previa</label>
                                <div class="mt-2">
                                    <img id="preview" class="max-w-xs max-h-32 object-cover rounded border border-gray-200">
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('cm.calendars.artworks.index', $calendar) }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancelar
                            </a>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Crear Arte
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
            const imgInput = document.getElementById('img_path');
            const preview = document.getElementById('preview');
            const imagePreview = document.getElementById('imagePreview');
            
            // Vista previa de imagen
            imgInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                    }
                    
                    reader.readAsDataURL(this.files[0]);
                } else {
                    imagePreview.classList.add('hidden');
                }
            });
            
            // Establecer fecha mínima para fecha_pub
            const fechaPub = document.getElementById('fecha_pub');
            const today = new Date().toISOString().split('T')[0];
            
            if (!fechaPub.value) {
                fechaPub.value = today;
            }
            fechaPub.min = today;
        });
    </script>
    @endpush
</x-app-layout>