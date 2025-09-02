<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Central
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 sm:grid-cols-2 gap-6">

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="text-sm text-gray-500">Tenants creados</div>
                <div class="mt-2 text-3xl font-bold text-gray-800">
                    {{ $tenantsCount }}
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="text-sm text-gray-500">Usuarios (todos los tenants)</div>
                <div class="mt-2 text-3xl font-bold text-gray-800">
                    {{ $usersCount }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
