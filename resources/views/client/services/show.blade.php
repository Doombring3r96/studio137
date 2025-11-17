<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle del Servicio') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Información General -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">{{ $service->tipo_formateado }}</h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $service->estado_clase }}">
                            {{ $service->estado }}
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-2">Información del Servicio</h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Fecha de inicio</dt>
                                    <dd class="text-sm text-gray-900">{{ $service->fecha_ini->format('d/m/Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Fecha de entrega</dt>
                                    <dd class="text-sm text-gray-900">{{ $service->fecha_fin->format('d/m/Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Costo</dt>
                                    <dd class="text-sm text-gray-900">{{ $service->costo_formateado }}</dd>
                                </div>
                            </dl>
                        </div>
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-2">Progreso</h4>
                            <div class="flex items-center">
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $service->progreso }}%"></div>
                                </div>
                                <span class="ml-2 text-sm font-medium text-gray-700">{{ $service->progreso }}%</span>
                            </div>
                            <p class="mt-2 text-sm text-gray-600">{{ $service->dias_restantes }} días restantes</p>
                        </div>
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-2">Acciones</h4>
                            <div class="space-y-2">
                                @if(!$service->brief)
                                    <a href="{{ route('client.services.brief.create', $service) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                        Completar Brief
                                    </a>
                                @endif
                                <a href="{{ route('client.services.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Volver a Servicios
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Brief -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Brief del Servicio</h3>
                    </div>
                    <div class="p-6">
                        @if($service->brief)
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Brief Completado</p>
                                        <p class="text-sm text-gray-600">Fecha: {{ $service->brief->fecha_recibida->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Completado
                                    </span>
                                </div>
                                
                                @if($service->brief->document_path)
                                    <div>
                                        <a href="{{ asset('storage/' . $service->brief->document_path) }}" 
                                           target="_blank"
                                           class="inline-flex items-center text-blue-600 hover:text-blue-900">
                                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            Ver documento adjunto
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-4">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Brief pendiente</h3>
                                <p class="mt-1 text-sm text-gray-500">Completa el brief para comenzar con tu servicio.</p>
                                <div class="mt-4">
                                    <a href="{{ route('client.services.brief.create', $service) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                        Completar Brief
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Logos (solo para identidad corporativa) -->
                @if($service->tipo === 'identidad_corporativa')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900">Propuestas de Logo</h3>
                            <a href="{{ route('client.services.logos', $service) }}" class="text-sm text-blue-600 hover:text-blue-900">Ver todos</a>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($service->logos->count() > 0)
                            <div class="space-y-4">
                                @foreach($service->logos->take(3) as $logo)
                                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                        <div class="flex items-center">
                                            @if($logo->img_path)
                                                <img src="{{ asset('storage/' . $logo->img_path) }}" alt="Logo" class="h-10 w-10 object-cover rounded">
                                            @endif
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">{{ $logo->tipo }}</p>
                                                <p class="text-sm text-gray-500">{{ $logo->version ?? 'Sin versión' }}</p>
                                            </div>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $logo->estado === 'entregado' ? 'bg-green-100 text-green-800' : 
                                               ($logo->estado === 'enviado' ? 'bg-blue-100 text-blue-800' : 
                                               ($logo->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                            {{ $logo->estado }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 text-center py-4">No hay propuestas de logo aún.</p>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Calendarios (solo para community manager) -->
                @if($service->tipo === 'community_manager')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900">Calendarios de Publicación</h3>
                            <a href="{{ route('client.services.calendars', $service) }}" class="text-sm text-blue-600 hover:text-blue-900">Ver todos</a>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($service->publicationCalendars->count() > 0)
                            <div class="space-y-4">
                                @foreach($service->publicationCalendars->take(3) as $calendar)
                                    <div class="p-3 border border-gray-200 rounded-lg">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $calendar->fecha_ini->format('d/m/Y') }} - {{ $calendar->fecha_fin->format('d/m/Y') }}
                                                </p>
                                                <p class="text-sm text-gray-500">{{ $calendar->artworks->count() }} artes programados</p>
                                            </div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ $calendar->estado === 'entregado' ? 'bg-green-100 text-green-800' : 
                                                   ($calendar->estado === 'enviado' ? 'bg-blue-100 text-blue-800' : 
                                                   ($calendar->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                                {{ $calendar->estado }}
                                            </span>
                                        </div>
                                        @if($calendar->correcciones_count > 0)
                                            <p class="text-xs text-gray-500 mt-2">
                                                {{ $calendar->correcciones_count }}/2 correcciones realizadas
                                            </p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 text-center py-4">No hay calendarios de publicación aún.</p>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Pagos -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg lg:col-span-2">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Pagos del Servicio</h3>
                    </div>
                    <div class="p-6">
                        @if($service->payments->count() > 0)
                            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($service->payments as $payment)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $payment->fecha_pago->format('d/m/Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ ucfirst($payment->tipo) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    $ {{ number_format($payment->cantidad, 2) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                        {{ $payment->estado === 'pagado' ? 'bg-green-100 text-green-800' : 
                                                           ($payment->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                                        {{ ucfirst($payment->estado) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    @if($payment->estado === 'pendiente')
                                                        <a href="{{ route('client.payments.pay', $payment) }}" class="text-blue-600 hover:text-blue-900">
                                                            Pagar
                                                        </a>
                                                    @else
                                                        <span class="text-gray-400">Completado</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-sm text-gray-500 text-center py-4">No hay pagos registrados para este servicio.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>