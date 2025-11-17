<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard - Cliente') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Estadísticas Rápidas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Servicios Activos -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium">Servicios Activos</p>
                                <p class="text-2xl font-bold">{{ $stats['active_services'] }}</p>
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

                <!-- Próximo Pago -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-gradient-to-r from-green-500 to-green-600 text-white">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium">Próximo Pago</p>
                                <p class="text-lg font-bold">
                                    @if($nextPayment)
                                        {{ $nextPayment->fecha_pago->format('d/m/Y') }}
                                    @else
                                        No hay pagos
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones Pendientes -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-gradient-to-r from-red-500 to-red-600 text-white">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium">Acciones Pendientes</p>
                                <p class="text-2xl font-bold">{{ $stats['pending_actions'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Gráfico de Estado de Servicios -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Estado de Servicios</h3>
                    </div>
                    <div class="p-6">
                        <canvas id="serviceStatusChart" height="250"></canvas>
                    </div>
                </div>

                <!-- Próximos Vencimientos -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Próximos Vencimientos</h3>
                    </div>
                    <div class="p-6">
                        @if($upcomingDeadlines->count() > 0)
                            <div class="space-y-4">
                                @foreach($upcomingDeadlines as $deadline)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $deadline->tipo }}</p>
                                            <p class="text-sm text-gray-600">{{ $deadline->nombre }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $deadline->fecha_fin->format('d/m/Y') }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $deadline->fecha_fin->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">No hay vencimientos próximos</p>
                        @endif
                    </div>
                </div>

                <!-- Acciones Requeridas -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg lg:col-span-2">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900">Acciones Requeridas</h3>
                            <a href="{{ route('client.services.index') }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                Ver todos los servicios
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($pendingActions->count() > 0)
                            <div class="space-y-4">
                                @foreach($pendingActions as $action)
                                    <div class="flex items-center justify-between p-4 border border-yellow-200 bg-yellow-50 rounded-lg">
                                        <div class="flex items-center">
                                            <svg class="h-5 w-5 text-yellow-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $action['title'] }}</p>
                                                <p class="text-sm text-gray-600">{{ $action['description'] }}</p>
                                            </div>
                                        </div>
                                        <div>
                                            <a href="{{ $action['action_url'] }}" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                {{ $action['action_text'] }}
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">No hay acciones pendientes</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gráfico de estado de servicios
            const serviceStatusCtx = document.getElementById('serviceStatusChart').getContext('2d');
            const serviceStatusChart = new Chart(serviceStatusCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($serviceStatuses->pluck('estado')) !!},
                    datasets: [{
                        data: {!! json_encode($serviceStatuses->pluck('count')) !!},
                        backgroundColor: [
                            '#10B981', // activo - verde
                            '#F59E0B', // inactivo - amarillo
                            '#EF4444', // cancelado - rojo
                            '#3B82F6', // culminado - azul
                            '#6B7280', // otros - gris
                        ],
                        borderWidth: 2,
                        borderColor: '#FFFFFF',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        title: {
                            display: true,
                            text: 'Distribución de Servicios por Estado'
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>