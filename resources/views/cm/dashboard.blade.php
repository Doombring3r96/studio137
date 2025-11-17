<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard - Community Manager') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Estadísticas Rápidas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Tareas Pendientes -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium">Tareas Pendientes</p>
                                <p class="text-2xl font-bold">{{ $stats['pending_assignments'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Calendarios Activos -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-gradient-to-r from-green-500 to-green-600 text-white">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium">Calendarios Activos</p>
                                <p class="text-2xl font-bold">{{ $stats['active_calendars'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Calendarios Completados -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-gradient-to-r from-purple-500 to-purple-600 text-white">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium">Calendarios Completados</p>
                                <p class="text-2xl font-bold">{{ $stats['completed_calendars'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagos Pendientes -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium">Pagos Pendientes</p>
                                <p class="text-2xl font-bold">{{ $stats['pending_payments'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Tareas Pendientes por Cliente -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900">Tareas por Cliente</h3>
                            <a href="{{ route('cm.calendars.index') }}" class="text-sm text-blue-600 hover:text-blue-900">Ver todas las tareas</a>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($calendarsByClient->count() > 0)
                            <div class="space-y-4">
                                @foreach($calendarsByClient as $clientId => $clientCalendars)
                                    @php $client = $clientCalendars->first()->service->cliente; @endphp
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex justify-between items-center mb-2">
                                            <h4 class="font-medium text-gray-900">{{ $client->nombre }}</h4>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $clientCalendars->count() }} calendarios
                                            </span>
                                        </div>
                                        <div class="space-y-2">
                                            @foreach($clientCalendars->take(3) as $calendar)
                                                <div class="flex justify-between items-center text-sm">
                                                    <span class="text-gray-600">
                                                        {{ $calendar->fecha_ini->format('d/m/Y') }} - {{ $calendar->fecha_fin->format('d/m/Y') }}
                                                    </span>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                                        {{ $calendar->estado === 'entregado' ? 'bg-green-100 text-green-800' : 
                                                           ($calendar->estado === 'enviado' ? 'bg-blue-100 text-blue-800' : 
                                                           ($calendar->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                                        {{ $calendar->estado }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">No hay calendarios asignados</p>
                        @endif
                    </div>
                </div>

                <!-- Tareas Pendientes -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900">Tareas Pendientes</h3>
                            <a href="{{ route('cm.calendars.index') }}" class="text-sm text-blue-600 hover:text-blue-900">Ver todas</a>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($assignments->count() > 0)
                            <div class="space-y-4">
                                @foreach($assignments as $assignment)
                                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $assignment->tarea_tipo }}</p>
                                            <p class="text-sm text-gray-600">{{ $assignment->service->tipo_formateado ?? 'N/A' }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $assignment->fecha_fin->format('d/m/Y') }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $assignment->fecha_fin->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">No hay tareas pendientes</p>
                        @endif
                    </div>
                </div>

                <!-- Calendarios Recientes -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900">Calendarios Recientes</h3>
                            <a href="{{ route('cm.calendars.index') }}" class="text-sm text-blue-600 hover:text-blue-900">Ver todos</a>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($calendars->count() > 0)
                            <div class="space-y-4">
                                @foreach($calendars as $calendar)
                                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                        <div>
                                            <p class="font-medium text-gray-900">
                                                {{ $calendar->service->tipo_formateado ?? 'N/A' }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                {{ $calendar->fecha_ini->format('d/m/Y') }} - {{ $calendar->fecha_fin->format('d/m/Y') }}
                                            </p>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $calendar->estado === 'entregado' ? 'bg-green-100 text-green-800' : 
                                               ($calendar->estado === 'enviado' ? 'bg-blue-100 text-blue-800' : 
                                               ($calendar->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                            {{ $calendar->estado }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">No hay calendarios</p>
                        @endif
                    </div>
                </div>

                <!-- Últimos Sueldos -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900">Historial de Sueldos</h3>
                            <a href="{{ route('cm.salaries.index') }}" class="text-sm text-blue-600 hover:text-blue-900">Ver todos</a>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($salaries->count() > 0)
                            <div class="space-y-3">
                                @foreach($salaries as $salary)
                                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                        <div>
                                            <p class="font-medium text-gray-900">
                                                $ {{ number_format($salary->cantidad, 2) }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                {{ $salary->fecha_pago->format('d/m/Y') }}
                                            </p>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $salary->estado === 'pagado' ? 'bg-green-100 text-green-800' : 
                                               ($salary->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                            {{ ucfirst($salary->estado) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">No hay registros de sueldos</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Acciones Rápidas</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('cm.calendars.create') }}" class="inline-flex flex-col items-center justify-center p-6 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="h-8 w-8 text-blue-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <span class="text-sm font-medium text-gray-900">Nuevo Calendario</span>
                            <span class="text-xs text-gray-500 mt-1">Crear calendario de publicación</span>
                        </a>

                        <a href="{{ route('cm.calendars.index') }}" class="inline-flex flex-col items-center justify-center p-6 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="h-8 w-8 text-green-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="text-sm font-medium text-gray-900">Gestionar Tareas</span>
                            <span class="text-xs text-gray-500 mt-1">Ver todas las tareas pendientes</span>
                        </a>

                        <a href="{{ route('cm.salaries.index') }}" class="inline-flex flex-col items-center justify-center p-6 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="h-8 w-8 text-yellow-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                            <span class="text-sm font-medium text-gray-900">Mis Sueldos</span>
                            <span class="text-xs text-gray-500 mt-1">Ver historial de pagos</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>