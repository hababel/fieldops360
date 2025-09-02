<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard — Tenant: {{ $stats['tenant'] }}
            </h2>
            <span class="text-sm text-gray-500">Usuario: {{ auth()->user()->name }}</span>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Tarjetas de métricas --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <x-tenant.stat-card title="Usuarios" :value="$stats['users_count']" />
                {{-- <x-tenant.stat-card title="Órdenes" :value="$stats['orders_count'] ?? 0" /> --}}
                {{-- <x-tenant.stat-card title="Abiertas" :value="$stats['open_orders'] ?? 0" /> --}}
                {{-- <x-tenant.stat-card title="Cerradas" :value="$stats['closed_orders'] ?? 0" /> --}}
            </div>

            {{-- Últimos usuarios --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Usuarios recientes</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-left text-gray-500">
                                <tr>
                                    <th class="py-2 pr-4">ID</th>
                                    <th class="py-2 pr-4">Nombre</th>
                                    <th class="py-2 pr-4">Email</th>
                                    <th class="py-2">Creado</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse($latestUsers as $u)
                                    <tr class="border-t">
                                        <td class="py-2 pr-4">{{ $u->id }}</td>
                                        <td class="py-2 pr-4">{{ $u->name }}</td>
                                        <td class="py-2 pr-4">{{ $u->email }}</td>
                                        <td class="py-2">{{ $u->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="py-4 text-gray-400">Sin usuarios aún.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Placeholder: últimas órdenes (cuando conectes tu modelo) --}}
            {{-- 
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Órdenes recientes</h3>
                    @if($latestOrders->isEmpty())
                        <p class="text-gray-400">Conecta tu modelo Order para mostrar datos aquí.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="text-left text-gray-500">
                                    <tr>
                                        <th class="py-2 pr-4">ID</th>
                                        <th class="py-2 pr-4">Título</th>
                                        <th class="py-2 pr-4">Estado</th>
                                        <th class="py-2">Creado</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-700">
                                    @foreach($latestOrders as $o)
                                        <tr class="border-t">
                                            <td class="py-2 pr-4">{{ $o->id }}</td>
                                            <td class="py-2 pr-4">{{ $o->title }}</td>
                                            <td class="py-2 pr-4">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs
                                                    {{ $o->status === 'open' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
                                                    {{ ucfirst($o->status) }}
                                                </span>
                                            </td>
                                            <td class="py-2">{{ \Illuminate\Support\Carbon::parse($o->created_at)->format('Y-m-d H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
            --}}
        </div>
    </div>
</x-app-layout>
