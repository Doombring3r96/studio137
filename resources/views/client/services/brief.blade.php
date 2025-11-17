<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Completar Brief') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Brief para {{ $service->tipo_formateado }}</h3>
                        <a href="{{ route('client.services.show', $service) }}" class="text-sm text-blue-600 hover:text-blue-900">Volver al servicio</a>
                    </div>
                    <p class="mt-1 text-sm text-gray-600">
                        Completa la siguiente información para que podamos entender mejor tus necesidades y comenzar con el desarrollo de tu servicio.
                    </p>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('client.services.brief.store', $service) }}" enctype="multipart/form-data">
                        @csrf

                        @if($service->tipo === 'identidad_corporativa')
                            <!-- Brief para Identidad Corporativa -->
                            <div class="space-y-6">
                                <div>
                                    <h4 class="text-lg font-medium text-gray-900 mb-4">Información de la Empresa</h4>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="nombre_empresa" class="block text-sm font-medium text-gray-700">Nombre de la Empresa *</label>
                                            <input type="text" name="contenido_json[nombre_empresa]" id="nombre_empresa" required
                                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        </div>

                                        <div>
                                            <label for="rubro" class="block text-sm font-medium text-gray-700">Rubro o Industria *</label>
                                            <input type="text" name="contenido_json[rubro]" id="rubro" required
                                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <label for="descripcion_negocio" class="block text-sm font-medium text-gray-700">Descripción del Negocio *</label>
                                        <textarea name="contenido_json[descripcion_negocio]" id="descripcion_negocio" rows="3" required
                                                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                  placeholder="¿A qué se dedica tu empresa? ¿Qué productos o servicios ofrece?"></textarea>
                                    </div>

                                    <div class="mt-4">
                                        <label for="valores_empresa" class="block text-sm font-medium text-gray-700">Valores y Misión *</label>
                                        <textarea name="contenido_json[valores_empresa]" id="valores_empresa" rows="3" required
                                                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                  placeholder="¿Cuáles son los valores principales de tu empresa?"></textarea>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-lg font-medium text-gray-900 mb-4">Público Objetivo</h4>
                                    
                                    <div class="mt-4">
                                        <label for="publico_objetivo" class="block text-sm font-medium text-gray-700">Describe tu público objetivo *</label>
                                        <textarea name="contenido_json[publico_objetivo]" id="publico_objetivo" rows="3" required
                                                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                  placeholder="Edad, género, intereses, nivel socioeconómico, etc."></textarea>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-lg font-medium text-gray-900 mb-4">Preferencias de Diseño</h4>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="estilo_preferido" class="block text-sm font-medium text-gray-700">Estilo Preferido *</label>
                                            <select name="contenido_json[estilo_preferido]" id="estilo_preferido" required
                                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                <option value="">Selecciona un estilo</option>
                                                <option value="moderno">Moderno</option>
                                                <option value="clasico">Clásico</option>
                                                <option value="minimalista">Minimalista</option>
                                                <option value="elegante">Elegante</option>
                                                <option value="playful">Playful/Divertido</option>
                                                <option value="profesional">Profesional</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label for="colores_preferidos" class="block text-sm font-medium text-gray-700">Colores Preferidos *</label>
                                            <input type="text" name="contenido_json[colores_preferidos]" id="colores_preferidos" required
                                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                   placeholder="Ej: Azul, blanco, gris">
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <label for="colores_evitar" class="block text-sm font-medium text-gray-700">Colores a Evitar</label>
                                        <input type="text" name="contenido_json[colores_evitar]" id="colores_evitar"
                                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-lg font-medium text-gray-900 mb-4">Referencias e Inspiración</h4>
                                    
                                    <div class="mt-4">
                                        <label for="referencias" class="block text-sm font-medium text-gray-700">Logos o Marcas que te Gustan</label>
                                        <textarea name="contenido_json[referencias]" id="referencias" rows="3"
                                                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                  placeholder="Menciona marcas o logos que admires y por qué"></textarea>
                                    </div>

                                    <div class="mt-4">
                                        <label for="elementos_obligatorios" class="block text-sm font-medium text-gray-700">Elementos Obligatorios</label>
                                        <textarea name="contenido_json[elementos_obligatorios]" id="elementos_obligatorios" rows="2"
                                                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                  placeholder="¿Hay algún elemento que deba incluirse obligatoriamente?"></textarea>
                                    </div>
                                </div>
                            </div>

                        @elseif($service->tipo === 'community_manager')
                            <!-- Brief para Community Manager -->
                            <div class="space-y-6">
                                <div>
                                    <h4 class="text-lg font-medium text-gray-900 mb-4">Objetivos de Marketing</h4>
                                    
                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700">Objetivos Principales *</label>
                                        <div class="mt-2 space-y-2">
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" name="contenido_json[objetivos][]" value="aumentar_seguidores" 
                                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                <span class="ml-2">Aumentar seguidores</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" name="contenido_json[objetivos][]" value="generar_ventas" 
                                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                <span class="ml-2">Generar ventas</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" name="contenido_json[objetivos][]" value="engagement" 
                                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                <span class="ml-2">Aumentar engagement</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" name="contenido_json[objetivos][]" value="brand_awareness" 
                                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                <span class="ml-2">Brand awareness</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <label for="objetivos_especificos" class="block text-sm font-medium text-gray-700">Objetivos Específicos *</label>
                                        <textarea name="contenido_json[objetivos_especificos]" id="objetivos_especificos" rows="3" required
                                                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                  placeholder="Describe tus objetivos específicos con más detalle"></textarea>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-lg font-medium text-gray-900 mb-4">Público Objetivo</h4>
                                    
                                    <div class="mt-4">
                                        <label for="publico_detalle" class="block text-sm font-medium text-gray-700">Describe tu público objetivo *</label>
                                        <textarea name="contenido_json[publico_detalle]" id="publico_detalle" rows="3" required
                                                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                  placeholder="Edad, intereses, hábitos de consumo, redes sociales que usan, etc."></textarea>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-lg font-medium text-gray-900 mb-4">Redes Sociales</h4>
                                    
                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700">Redes a Gestionar *</label>
                                        <div class="mt-2 grid grid-cols-2 gap-2">
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" name="contenido_json[redes_sociales][]" value="facebook" 
                                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                <span class="ml-2">Facebook</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" name="contenido_json[redes_sociales][]" value="instagram" 
                                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                <span class="ml-2">Instagram</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" name="contenido_json[redes_sociales][]" value="twitter" 
                                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                <span class="ml-2">Twitter</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" name="contenido_json[redes_sociales][]" value="linkedin" 
                                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                <span class="ml-2">LinkedIn</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <label for="frecuencia_publicacion" class="block text-sm font-medium text-gray-700">Frecuencia de Publicación Deseada *</label>
                                        <select name="contenido_json[frecuencia_publicacion]" id="frecuencia_publicacion" required
                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                            <option value="">Selecciona una frecuencia</option>
                                            <option value="diaria">Publicación diaria</option>
                                            <option value="3_4_semana">3-4 veces por semana</option>
                                            <option value="2_3_semana">2-3 veces por semana</option>
                                            <option value="semanal">1 vez por semana</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-lg font-medium text-gray-900 mb-4">Tono y Estilo</h4>
                                    
                                    <div class="mt-4">
                                        <label for="tono_comunicacion" class="block text-sm font-medium text-gray-700">Tono de Comunicación *</label>
                                        <select name="contenido_json[tono_comunicacion]" id="tono_comunicacion" required
                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                            <option value="">Selecciona un tono</option>
                                            <option value="formal">Formal y profesional</option>
                                            <option value="amigable">Amigable y cercano</option>
                                            <option value="divertido">Divertido y casual</option>
                                            <option value="inspirador">Inspirador y motivador</option>
                                            <option value="educativo">Educativo e informativo</option>
                                        </select>
                                    </div>

                                    <div class="mt-4">
                                        <label for="temas_contenido" class="block text-sm font-medium text-gray-700">Temas de Contenido *</label>
                                        <textarea name="contenido_json[temas_contenido]" id="temas_contenido" rows="3" required
                                                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                  placeholder="¿Sobre qué temas te gustaría que publiquemos?"></textarea>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-lg font-medium text-gray-900 mb-4">Competencia y Referencias</h4>
                                    
                                    <div class="mt-4">
                                        <label for="competencia_directa" class="block text-sm font-medium text-gray-700">Competencia Directa</label>
                                        <textarea name="contenido_json[competencia_directa]" id="competencia_directa" rows="2"
                                                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                  placeholder="Menciona a tus principales competidores"></textarea>
                                    </div>

                                    <div class="mt-4">
                                        <label for="cuentas_referencia" class="block text-sm font-medium text-gray-700">Cuentas que Admiras</label>
                                        <textarea name="contenido_json[cuentas_referencia]" id="cuentas_referencia" rows="2"
                                                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                  placeholder="Menciona cuentas de redes sociales que te gustan"></textarea>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Documento adjunto -->
                        <div class="mt-6">
                            <label for="document_path" class="block text-sm font-medium text-gray-700">Documento Adjunto (Opcional)</label>
                            <input type="file" name="document_path" id="document_path" 
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <p class="mt-1 text-sm text-gray-500">Puedes subir un documento adicional con información relevante (PDF, Word, imágenes).</p>
                        </div>

                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('client.services.show', $service) }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancelar
                            </a>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Guardar Brief
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>